--临时表:发券表
drop table #tmp_hive_table1#;
create table #tmp_hive_table1# as
select a.activity_coupon_id
      ,b.member_no
      ,count(*) as cnt
      ,min(a.create_time) as create_time
from hawkeye.t_market_coupon_user_45d a
join #hive_table# b on a.user_id=b.member_no --s-- 关联活动表 仅查询当前活动的效果
where a.activity_coupon_id in (#coupos#)    
  and #adate#
group by a.activity_coupon_id
        ,b.member_no
;

--临时表:发券客户交易表
drop table #tmp_hive_table2#;
create table #tmp_hive_table2# as
select b.member_no
      ,d.activity_coupon_id as trans_coupon_id
      ,a.activity_coupon_id as issue_coupon_id
      ,case when d.activity_coupon_id in (#coupos#) then 1 else 0 end is_coupon_trans --s-- 券ID
      ,case when d.activity_coupon_id=a.activity_coupon_id then 1 else 0 end as coupon_trans_eq_issue
      ,e.name             --券名称
      ,c.product_name     --用券交易产品名称
      ,datediff(c.due_date,c.inception_date) as invest_days --产品投资天数
      ,b.trans_time       --用券交易时间
      ,b.fund_trans_amount--用券交易金额
      ,b.fund_trans_type  --交易类型
      ,f.satisfied_amount --券使用最小金额
      ,d.deduction_amt    --现金券折扣金额
      ,case when e.name like '%加息券%'
              and (b.fund_trans_amount*(f.raise_interest_rates/10000)*datediff(c.due_date,c.inception_date)/365)<=e.max_allowance_amount
            then coalesce((b.fund_trans_amount*(f.raise_interest_rates/10000)*datediff(c.due_date,c.inception_date)/365),0)
            when e.name like '%加息券%'
              and (b.fund_trans_amount*(f.raise_interest_rates/10000)*datediff(c.due_date,c.inception_date)/365)> e.max_allowance_amount
            then e.max_allowance_amount
            else 0
       end as add_rate_amt --加息金额
      ,a.create_time
from(select activity_coupon_id, member_no, min(create_time) as create_time from #tmp_hive_table1# group by activity_coupon_id, member_no)a
join eif_ftc.t_ftc_fund_trans_order b on a.member_no=b.member_no
join eif_fis.t_fis_prod_info c on b.product_id=c.id
left outer join eif_market.t_market_use_rec d on d.order_no=b.business_order_item_no
left outer join eif_market.t_market_activity_coupon e on d.activity_coupon_id=e.id
left outer join eif_market.t_market_rule f on e.rule_id=f.id
where b.status in (6,8,9,11)
  and b.trans_time>=a.create_time
  and #bdate#
;

--效果查询
select t.activity_coupon_id                                                  --券id
      ,coalesce(t2.coupon_name, '')        as coupon_name                    --券名称
      ,coalesce(t.issue_user_cnt       ,0) as issue_user_cnt                 --券发放人数       
      ,coalesce(t.issue_cnt            ,0) as issue_cnt                      --券发放张数
      ,t.trans_date                                                          --交易日期
      ,coalesce(t.trans_amt            ,0) as trans_amt                      --投资总金额       
      ,coalesce(t.trans_user_cnt       ,0) as trans_user_cnt                 --投资人数        
      ,coalesce(t.coupon_trans_amt     ,0) as coupon_trans_amt               --用券投资额       
      ,coalesce(t.coupon_trans_user_cnt,0) as coupon_trans_user_cnt          --用券投资人数 
      ,coalesce(concat(cast(t.coupon_trans_user_cnt/issue_user_cnt*100 as decimal(10,4)),'%'),0) as coupon_trans_rate --转化率     
      ,coalesce(t.coupon_trans_cnt     ,0) as coupon_trans_cnt               --券使用张数  
      ,coalesce(concat(cast(t.coupon_trans_cnt/issue_cnt*100 as decimal(10,4)),'%'),0) as coupon_use_rate             --券使用率     
      ,coalesce(t.coupon_deduction_amt ,0) as coupon_deduction_amt           --折扣金额        
      ,coalesce(round(t.coupon_add_rate_amt,2),0) as coupon_add_rate_amt     --加息金额 
      ,coalesce(t.coupon_deduction_amt+coupon_add_rate_amt) as coupon_cost   --券成本
      ,round(case when t.activity_coupon_id='TOTAL' and t.trans_date='TOTAL' then t.issue_user_cnt*0.032 else 0 end,0) as sms_cost --短信成本
      ,coalesce(cast(t.coupon_deduction_amt
       +t.coupon_add_rate_amt
       +(case when t.activity_coupon_id='TOTAL' and t.trans_date='TOTAL' then t.issue_user_cnt*0.032 else 0 end)
       as decimal(10,4)),0) as markting_cost                                 --营销总成本
      ,coalesce(cast(t.coupon_trans_amt_all as decimal(20,0)) ,0) as coupon_trans_amt_all           --用券人全部投资额
      ,coalesce(cast(t1.trans_amt as decimal(20,0)),0) as trans_amt_all                             --查询日期总交易额	
      ,coalesce(concat(cast(t.coupon_trans_amt_all/t1.trans_amt*100 as decimal(10,4)),'%'),0) as coupon_trans_all_rate --交易占比

from(
------part1:按照券ID 交易日汇总部分-----
select t1.activity_coupon_id        --券id         
      ,t1.issue_cnt                 --券发放张数       
      ,t1.issue_user_cnt            --券发放人数       
      ,t2.trans_date                --交易日期        
      ,t2.trans_amt                 --投资总金额       
      ,t2.trans_user_cnt            --投资人数        
      ,t3.coupon_trans_amt          --用券投资额       
      ,t3.coupon_trans_user_cnt     --用券投资人数      
      ,t3.coupon_trans_cnt          --券使用张数       
      ,t3.coupon_deduction_amt      --折扣金额        
      ,t3.coupon_add_rate_amt       --加息金额        
      ,t4.coupon_trans_amt_all      --用券人全部投资额
from
(--发放张数 发放人数
select activity_coupon_id
      ,sum(cnt) as issue_cnt
      ,count(*) as issue_user_cnt
from #tmp_hive_table1#
group by activity_coupon_id
)t1
left outer join
(--投资总金额 投资人数
select issue_coupon_id as activity_coupon_id
      ,to_date(trans_time) as trans_date
      ,sum(fund_trans_amount) as trans_amt
      ,count(distinct member_no) as trans_user_cnt
from #tmp_hive_table2# where fund_trans_type=1
group by issue_coupon_id, to_date(trans_time)
)t2 on t1.activity_coupon_id=t2.activity_coupon_id
left outer join
(--用券投资额 用券投资人数 券使用张数 折扣金额 加息金额
select trans_coupon_id as activity_coupon_id
      ,to_date(trans_time) as trans_date
      ,sum(fund_trans_amount) as coupon_trans_amt
      ,count(distinct member_no) as coupon_trans_user_cnt
      ,count(*) as coupon_trans_cnt
      ,sum(deduction_amt) as coupon_deduction_amt
      ,sum(add_rate_amt) as coupon_add_rate_amt
from #tmp_hive_table2#
where is_coupon_trans=1 and trans_coupon_id=issue_coupon_id and fund_trans_type=1
group by trans_coupon_id
        ,to_date(trans_time)
)t3 on t2.activity_coupon_id=t3.activity_coupon_id and t2.trans_date=t3.trans_date
left outer join
(--用券人全部投资额 
select a.activity_coupon_id
      ,to_date(b.trans_time) as trans_date
      ,count(*) as coupon_trans_cnt_all
      ,count(distinct a.member_no) as coupon_trans_user_cnt_all
      ,sum(b.fund_trans_amount) as coupon_trans_amt_all
from(
select trans_coupon_id as activity_coupon_id
      ,member_no
      ,to_date(trans_time) as trans_date 
from #tmp_hive_table2#
where is_coupon_trans=1 and trans_coupon_id=issue_coupon_id and fund_trans_type=1
group by trans_coupon_id
        ,member_no
        ,to_date(trans_time)
)a
join
(select member_no
       ,trans_time
       ,fund_trans_amount 
from #tmp_hive_table2# where fund_trans_type=1
group by member_no, trans_time, fund_trans_amount
)b on a.member_no=b.member_no and a.trans_date=to_date(b.trans_time)
group by a.activity_coupon_id
        ,to_date(b.trans_time)
)t4 on t3.activity_coupon_id=t4.activity_coupon_id and t3.trans_date=t4.trans_date

union all

------part2:按照券ID汇总部分-----
select t1.activity_coupon_id        --券id         
      ,t1.issue_cnt                 --券发放张数       
      ,t1.issue_user_cnt            --券发放人数       
      ,'TOTAL' as trans_date        --交易日期        
      ,t2.trans_amt                 --投资总金额       
      ,t2.trans_user_cnt            --投资人数        
      ,t3.coupon_trans_amt          --用券投资额       
      ,t3.coupon_trans_user_cnt     --用券投资人数      
      ,t3.coupon_trans_cnt          --券使用张数       
      ,t3.coupon_deduction_amt      --折扣金额        
      ,t3.coupon_add_rate_amt       --加息金额        
      ,t4.coupon_trans_amt_all      --用券人全部投资额
from
(--发放张数 发放人数
select activity_coupon_id
      ,sum(cnt) as issue_cnt
      ,count(*) as issue_user_cnt
from #tmp_hive_table1#
group by activity_coupon_id
)t1
left outer join
(--投资总金额 投资人数
select issue_coupon_id as activity_coupon_id
      ,sum(fund_trans_amount) as trans_amt
      ,count(distinct member_no) as trans_user_cnt
from #tmp_hive_table2# where fund_trans_type=1
group by issue_coupon_id
)t2 on t1.activity_coupon_id=t2.activity_coupon_id
left outer join
(--用券投资额 用券投资人数 券使用张数 折扣金额 加息金额
select trans_coupon_id as activity_coupon_id
      ,sum(fund_trans_amount) as coupon_trans_amt
      ,count(distinct member_no) as coupon_trans_user_cnt
      ,count(*) as coupon_trans_cnt
      ,sum(deduction_amt) as coupon_deduction_amt
      ,sum(add_rate_amt) as coupon_add_rate_amt
from #tmp_hive_table2#
where is_coupon_trans=1 and trans_coupon_id=issue_coupon_id and fund_trans_type=1
group by trans_coupon_id
)t3 on t2.activity_coupon_id=t3.activity_coupon_id
left outer join
(--用券人全部投资额 
select a.activity_coupon_id
      ,count(*) as coupon_trans_cnt_all
      ,count(distinct a.member_no) as coupon_trans_user_cnt_all
      ,sum(b.fund_trans_amount) as coupon_trans_amt_all
from(
select trans_coupon_id as activity_coupon_id
      ,member_no
      ,to_date(trans_time) as trans_date 
from #tmp_hive_table2#
where is_coupon_trans=1 and trans_coupon_id=issue_coupon_id and fund_trans_type=1
group by trans_coupon_id
        ,member_no
        ,to_date(trans_time)
)a
join 
(select member_no
       ,trans_time
       ,fund_trans_amount 
from #tmp_hive_table2# where fund_trans_type=1
group by member_no, trans_time, fund_trans_amount
)b on a.member_no=b.member_no and a.trans_date=to_date(b.trans_time)
group by a.activity_coupon_id
)t4 on t3.activity_coupon_id=t4.activity_coupon_id

union all

------part3:按照所有汇总部分-----
select t1.activity_coupon_id        --券id         
      ,t1.issue_cnt                 --券发放张数       
      ,t1.issue_user_cnt            --券发放人数       
      ,'TOTAL' as trans_date        --交易日期        
      ,t2.trans_amt                 --投资总金额       
      ,t2.trans_user_cnt            --投资人数        
      ,t3.coupon_trans_amt          --用券投资额       
      ,t3.coupon_trans_user_cnt     --用券投资人数      
      ,t3.coupon_trans_cnt          --券使用张数       
      ,t3.coupon_deduction_amt      --折扣金额        
      ,t3.coupon_add_rate_amt       --加息金额        
      ,t4.coupon_trans_amt_all      --用券人全部投资额
from
(--发放张数 发放人数
select 'TOTAL' as activity_coupon_id
      ,sum(cnt) as issue_cnt
      ,count(distinct member_no) as issue_user_cnt
from #tmp_hive_table1#
)t1
left outer join
(--投资总金额 投资人数
select 'TOTAL' as activity_coupon_id
      ,sum(fund_trans_amount) as trans_amt
      ,count(distinct member_no) as trans_user_cnt
from(select member_no, trans_time, fund_trans_amount from #tmp_hive_table2# where fund_trans_type=1 group by member_no, trans_time, fund_trans_amount)t
)t2 on t1.activity_coupon_id=t2.activity_coupon_id
left outer join
(--用券投资额 用券投资人数 券使用张数 折扣金额 加息金额
select 'TOTAL' as activity_coupon_id
      ,sum(fund_trans_amount) as coupon_trans_amt
      ,count(distinct member_no) as coupon_trans_user_cnt
      ,count(*) as coupon_trans_cnt
      ,sum(deduction_amt) as coupon_deduction_amt
      ,sum(add_rate_amt) as coupon_add_rate_amt
from #tmp_hive_table2#
where is_coupon_trans=1 and trans_coupon_id=issue_coupon_id and fund_trans_type=1
)t3 on t1.activity_coupon_id=t3.activity_coupon_id
left outer join
(--用券人全部投资额 
select 'TOTAL' as activity_coupon_id
      ,count(*) as coupon_trans_cnt_all
      ,count(distinct a.member_no) as coupon_trans_user_cnt_all
      ,sum(b.fund_trans_amount) as coupon_trans_amt_all
from(
select member_no
      ,to_date(trans_time) as trans_date 
from #tmp_hive_table2#
where is_coupon_trans=1 and trans_coupon_id=issue_coupon_id and fund_trans_type=1
group by member_no
        ,to_date(trans_time)
)a 
join
(select member_no
       ,trans_time
       ,fund_trans_amount 
from #tmp_hive_table2# where fund_trans_type=1
group by member_no, trans_time, fund_trans_amount
)b on a.member_no=b.member_no and a.trans_date=to_date(b.trans_time)

)t4 on t1.activity_coupon_id=t4.activity_coupon_id

)t 

left outer join
(
select to_date(trans_time) as trans_date
      ,sum(fund_trans_amount) as trans_amt
from eif_ftc.t_ftc_fund_trans_order b
where #bdate#
  and status in (6,9,11)
  and fund_trans_type=1
group by to_date(trans_time)

union all 

select 'TOTAL' as trans_date
      ,sum(fund_trans_amount) as trans_amt
from eif_ftc.t_ftc_fund_trans_order b
where #bdate#
  and status in (6,9,11)
  and fund_trans_type=1
)t1 on t.trans_date=t1.trans_date

left outer join
(select a.id
       ,case when a.name like '%现金券%' then concat('满',cast(b.satisfied_amount as int),'减',cast(b.discount_amount as int))
             when a.name like '%加息券%' then concat('加息',b.raise_interest_rates/100,'%')
        end as coupon_name
from eif_market.t_market_activity_coupon a
join eif_market.t_market_rule b on a.rule_id=b.id
)t2 on t.activity_coupon_id=t2.id
order by trans_date, activity_coupon_id
;


drop table #tmp_hive_table1#;
drop table #tmp_hive_table2#;