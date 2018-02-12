select t1.product_name
      ,t1.display_rate
      ,t1.invest_days
      ,t1.min_trans_time
      ,t1.max_trans_time
      ,t1.trans_cnt
      ,t1.trans_usr_cnt
      ,t1.trans_amt
      ,t1.discount_amt
      ,t2.invest_income
      ,t2.invest_addrate
from
(select c.id
      ,c.product_name
      ,c.display_rate
      ,datediff(c.due_date,c.inception_date) as invest_days
      ,min(b.trans_time) as min_trans_time
      ,max(b.trans_time) as max_trans_time
      ,count(*) as trans_cnt
      ,count(distinct b.member_no) as trans_usr_cnt
      ,sum(b.fund_trans_amount) as trans_amt
      ,sum(b.discount_amount) as discount_amt
from bi.wy_regnottrans0707 a
join eif_ftc.t_ftc_fund_trans_order b on a.member_no=b.member_no
join eif_fis.t_fis_prod_info c on b.product_id=c.id
where b.status in (6,9,11)
  and #bdate#
  #procs_c#
group by c.id
        ,c.product_name
        ,c.display_rate
        ,datediff(c.due_date,c.inception_date)
)t1
join
(
SELECT c.id
      ,c.product_name
      ,sum(b.expect_bonus_amount) as invest_income
      ,sum(b.expect_profit_amount) as invest_addrate
from
(select c.fund_detail_uuid
       ,b.member_no
from bi.wy_regnottrans0707 a
join eif_ftc.t_ftc_fund_trans_order b on a.member_no=b.member_no
join eif_ftc.t_amc_fund_detail_alteration c on b.fund_trans_order_no=c.ftc_order_no
join eif_fis.t_fis_prod_info d on b.product_id=d.id
where b.status in (6,9,11)
  and #bdate#
  #procs_d#
group by c.fund_detail_uuid
        ,b.member_no
)a
join eif_ftc.t_amc_fund_detail b on a.fund_detail_uuid=b.fund_detail_uuid
join eif_fis.t_fis_prod_info c on b.product_id=c.id
group by c.id
        ,c.product_name
)t2 on t1.id=t2.id;