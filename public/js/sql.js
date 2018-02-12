
function confirm_submit() {
    if ($('#prom_name').val() === "") {
        $('#globalTip i').html("活动名不能为空！");
        Myclick();
        return false;
    } else {
//        $('#globalTip p').html("提交请求");
//        $('#globalTip strong').html("");
//        $('#globalTip i').html("确定要提交活动吗？");
//        Myclick();
//        $('#globalTip .md-close').click(function () {
//            
//        });
        $("#submit-prom").attr("disabled", "true").text("正在提交,请稍后.").css("background-color", "#ccc");
        var r = makeSql();
        send(r[0], r[1]);
    }
}




var makeSql = function (e) {

    var joinBaseSql = {};
    joinBaseSql["5"] = " left outer join(select user_id as member_no from eif_market.t_market_coupon_user where #where# group by user_id)t1 on t.member_no=t1.member_no ";
    joinBaseSql["7"] = " left outer join(select user_id as member_no from eif_market.t_market_coupon_user where #where# group by user_id)t2 on t.member_no=t2.member_no ";
    joinBaseSql["9"] = " left outer join(select a.member_no from(select user_id as member_no, count(*) as cnt from eif_market.t_market_coupon_user #activity_coupon_id# group by user_id)a join (select a.member_no,count(*) as cnt from eif_ftc.t_ftc_fund_trans_order a join eif_market.t_market_use_rec b on a.business_order_item_no=b.order_no where a.status in (6,9,11) and fund_trans_type=1 and #where# group by a.member_no)b on a.member_no=b.member_no where a.cnt=b.cnt)t3 on t.member_no=t3.member_no ";
    joinBaseSql["11"] = " left outer join(select a.member_no from(select user_id as member_no, count(*) as cnt from eif_market.t_market_coupon_user #activity_coupon_id# group by user_id)a join (select a.member_no,count(*) as cnt from eif_ftc.t_ftc_fund_trans_order a join eif_market.t_market_use_rec b on a.business_order_item_no=b.order_no where a.status in (6,9,11) and fund_trans_type=1 and #where# group by a.member_no)b on a.member_no=b.member_no where a.cnt=b.cnt)t4 on t.member_no=t4.member_no ";

    joinBaseSql["13"] = " left outer join(select a.member_no from eif_ftc.t_ftc_fund_trans_order a join eif_fis.t_fis_prod_info b on a.product_id=b.id where a.status in (6,9,11) and fund_trans_type=1 and #where# group by a.member_no)t5 on t.member_no=t5.member_no";
    joinBaseSql["15"] = " left outer join(select a.member_no from eif_ftc.t_ftc_fund_trans_order a join eif_fis.t_fis_prod_info b on a.product_id=b.id where a.status in (6,9,11) and fund_trans_type=1 and #where# group by a.member_no)t6 on t.member_no=t6.member_no";
    joinBaseSql["17"] = " left outer join(select t.member_no from (select member_no, white_list_id from(select member_no, split(white_list_group,',') as white_list_group from eif_member.t_member where white_list_group<>'' and white_list_group is not null)m lateral view explode(m.white_list_group) adtable as white_list_id)t where #where# group by t.member_no)t7 on t.member_no=t7.member_no ";
    joinBaseSql["18"] = " left outer join(select t.member_no from (select member_no, white_list_id from(select member_no, split(white_list_group,',') as white_list_group from eif_member.t_member where white_list_group<>'' and white_list_group is not null)m lateral view explode(m.white_list_group) adtable as white_list_id)t where #where# group by t.member_no)t8 on t.member_no=t8.member_no ";

    joinBaseSql["19"] = " left outer join(select member_no, score_tag_result from eye.bigtable)t11 on t.member_no=t11.member_no";

    joinBaseSql["20"] = " left outer join(SELECT a.member_no FROM eif_ftc.t_ftc_fund_trans_order a JOIN eif_fis.t_fis_prod_info b ON a.product_id=b.id WHERE a.status IN (6,9,11) and fund_trans_type=1 AND #where# GROUP BY a.member_no)t20 ON t.member_no=t20.member_no ";
    joinBaseSql["21"] = " left outer join(SELECT a.member_no FROM eif_ftc.t_ftc_fund_trans_order a JOIN eif_fis.t_fis_current_prod_info b ON a.product_id=b.id WHERE a.status IN (6,8,9,11) and fund_trans_type=2 and product_id>=10000024 and #where# GROUP BY  a.member_no)t21 ON t.member_no=t21.member_no ";

    var data = [];
    var sql = {};
    sql.wheres = {};
    for (var groupid in groups.dataDomMap) {
        var group = {};
        group.groupId = groupid;
        group.nodes = [];

        if (!sql.wheres[groupid]) {
            sql.wheres[groupid] = {};
            sql.wheres[groupid]["join"] = {};
            sql.wheres[groupid]["or"] = [];
            sql.wheres[groupid]["orderBy"] = [];
            sql.wheres[groupid]["limit"] = "";
        }
        for (var key in joinBaseSql) {
            sql.wheres[groupid]["join"][key] = {};
            sql.wheres[groupid]["join"][key]["sql"] = joinBaseSql[key];
            sql.wheres[groupid]["join"][key]["where"] = [];
        }

        data.push(group);

        var pros = Object.getOwnPropertyNames(groups.dataDomMap[groupid]);
        for (var i = 0; i < pros.length; i++) {
            if (pros[i] === "0" || pros[i] === "length" || pros[i] === "id")
                continue;

            var tag = groups.dataDomMap[groupid][pros[i]].find("span:first");
            var table = tag.attr("data-table");
            var field = tag.attr("data-field");
            var type = tag.attr("data-type");
            var values = [];

            if (!sql[table]) {
                sql[table] = "from " + table + " where ";
                //sql[table] = "select member_no,mobile,named,device_cid,device_type from " + table + " where ";
            }

            if (!sql.wheres[groupid][table])
                sql.wheres[groupid][table] = [];
            if (!sql.wheres[groupid]["join"]) {

            }
            if (!sql.wheres[groupid]["joinWhere"])
                sql.wheres[groupid].joinWhere = [];

            var node = {};
            node.title = pros[i];
            node.field = field;
            node.table = table;
            node.type = type;
            node.tags = [];
            group.nodes.push(node);

            var pros1 = Object.getOwnPropertyNames(groups.dataDomMap[groupid][pros[i]]);

            for (var j = 0; j < pros1.length; j++) {
                if (pros1[j] === "0" || pros1[j] === "length" || pros1[j] === "prevObject" || pros1[j] === "context")
                    continue;
                var v = pros1[j];

                v = v.replace(/，/g, ',');

                switch (v) {
                    case "是":
                        v = "1";
                        break;
                    case "否":
                        v = "0";
                        break;
                }
                values.push("'" + v + "'");

                node.tags.push(pros1[j]);
            }

            var sqlWhere = field;
            if (sqlWhere === undefined)
                sqlWhere = "";

            switch (type) {
                case "0":
                    values = String(values).replace(/\'/g, '');
                    //sqlWhere += " is null ";
                    //sql.wheres[groupid][table].push(sqlWhere);
                    sql.wheres[groupid]["or"].push(field + " is null ");
                    break;
                case "1":
                    var orNull = "";
                    if (field === "sex") {
                        var index = values.indexOf("\'未知\'");
                        if (index !== -1) {
                            orNull = field + " is null ";
                            values.splice(index, 1);
                            if (values.length === 0) {
                                sql.wheres[groupid]["or"].push(orNull);
                                break;
                            }
                        }
                    }
                    if (values.length === 1)
                        sqlWhere += " =" + values;
                    else
                        sqlWhere += " in(" + values + ")";
                    if (orNull.length > 0)
                        sqlWhere += " or " + orNull;
                    sql.wheres[groupid][table].push(sqlWhere);
                    break;
                case "2":
                    var ss = values.toString().replace(/\'/g, '');
                    var vv = ss.split(",");
                    if (vv.length === 1)
                        sqlWhere += " =" + values;
                    else {
                        var aa = [];

                        for (var ii in vv) {
                            aa.push("'" + vv[ii] + "'");
                        }

                        sqlWhere += " in(" + aa.join(",") + ")";
                    }
                    sql.wheres[groupid][table].push(sqlWhere);
                    break;
                case "3":
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        sqlWhere += "=" + values;
                        sql.wheres[groupid][table].push(sqlWhere);
                        break;
                    }

//                        var a = m[0].replace("\[", "").replace("\]", "").split(",");
//                        sqlWhere = "(" + sqlWhere +  ">=" + a[0] + " and " + field + " <=" + a[1] + ")";
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push("(" + field + " >=" + a[0] + " and " + field + " <=" + a[1] + ")");
                    }
                    sqlWhere = " " + arr.join(" or ");
                    sql.wheres[groupid][table].push(sqlWhere);
                    break;
                case "4" :
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        sqlWhere += "=" + values;
                        sql.wheres[groupid][table].push(sqlWhere);
                        break;
                    }

//                        var a = m[0].replace("\[", "").replace("\]", "").split(",");
//                        sqlWhere += ">='" + a[0] + "' and " + field + " <='" + a[1] + "'";
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push("(to_date(" + field + ") >='" + a[0] + "' and to_date(" + field + ") <='" + a[1] + "')");
                    }
                    sqlWhere = " " + arr.join(" or ");
                    sql.wheres[groupid][table].push(sqlWhere);
                    break;
                case "5" :
                    values = String(values).replace(/\'/g, '');
                    sqlWhere = " activity_coupon_id in (" + values + ") ";
                    sql.wheres[groupid]["join"]["5"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t1.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    //console.debug(sqlWhere);
                    //[2016-01-01,2016-02-09],[2016-05-01,2016-09-09]
                    break;
                case "6" :
                    //values = values.toString().replace(/'/g, '');
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("发放券时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        });
                        return;
                    }

                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(create_time) >='" + a[0] + "' and to_date(create_time) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ")";
                    sql.wheres[groupid]["join"]["5"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t1.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "7" :
                    values = String(values).replace(/'/g, '');
                    sqlWhere = " activity_coupon_id in (" + values + ") ";
                    sql.wheres[groupid]["join"]["7"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t2.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "8" :
                    //values = String(values).replace(/'/g, '');
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("包含发放券ID时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        });
                        return;
                    }

                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(create_time) >='" + a[0] + "' and to_date(create_time) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ")";
                    sql.wheres[groupid]["join"]["7"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t2.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "9" :
                    values = String(values).replace(/'/g, '');
                    sqlWhere = "b.activity_coupon_id in (" + values + ")";
                    var w = "where activity_coupon_id in(" + values + ")";
                    var s = sql.wheres[groupid]["join"]["9"]["sql"].replace("#activity_coupon_id#", w);
                    sql.wheres[groupid]["join"]["9"]["sql"] = s;
                    sql.wheres[groupid]["join"]["9"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t3.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "10" :
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("使用券时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        })
                        return false;
                    }
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        console.debug(m[k]);
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(a.trans_time) >='" + a[0] + "' and to_date(a.trans_time) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ")";
                    sql.wheres[groupid]["join"]["9"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t3.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "11" :
//                        values = String(values).replace(/'/g, '');
//                        sqlWhere = " b.activity_coupon_id in (" + values + ") ";
//                        sql.wheres[groupid]["join"]["11"]["where"].push(sqlWhere);
//                        sql.wheres[groupid]["joinWhere"]["t4.member_no is not null"] = "";
//                        delete sql[table];
//                        delete sql.wheres[groupid][table];
//                        break;
                    values = String(values).replace(/'/g, '');
                    sqlWhere = "b.activity_coupon_id in (" + values + ")";
                    var w = "where activity_coupon_id in(" + values + ")";
                    var s = sql.wheres[groupid]["join"]["11"]["sql"].replace("#activity_coupon_id#", w);
                    sql.wheres[groupid]["join"]["11"]["sql"] = s;
                    sql.wheres[groupid]["join"]["11"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t4.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "12" :
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("使用券时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        })
                        return;
                    }
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(a.trans_time) >='" + a[0] + "' and to_date(a.trans_time) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ")";
                    sql.wheres[groupid]["join"]["11"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t4.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "13" :
                    values = String(values).replace(/\'/g, '');
                    values = values.split(",");
                    //[2015-01-01,2016-01-01]
                    var arr = [];
                    for (var s = 0; s < values.length; s++) {
                        arr.push("b.product_name like '%" + values[s] + "%'");
                    }
                    sqlWhere = " (" + arr.join(' or ') + ") ";
                    sql.wheres[groupid]["join"]["13"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t5.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];

                    break;
                case "14":
                    //values = String(values).replace(/\'/g, '');
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("购买产品时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        });
                        return;
                    }
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(a.trans_time) >='" + a[0] + "' and to_date(a.trans_time) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ") ";
                    sql.wheres[groupid]["join"]["13"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t5.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "15" :
                    values = String(values).replace(/\'/g, '');
                    values = values.split(",");

                    //[2015-01-01,2016-01-01]
                    var arr = [];
                    for (var s = 0; s < values.length; s++) {
                        arr.push(" b.product_name like '%" + values[s] + "%' ");
                    }
                    sqlWhere = " (" + arr.join(' or ') + ") ";
                    sql.wheres[groupid]["join"]["15"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t6.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "16":
                    //values = String(values).replace(/\'/g, '');
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("购买产品时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        });
                        return;
                    }
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(a.trans_time) >='" + a[0] + "' and to_date(a.trans_time) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ") ";
                    sql.wheres[groupid]["join"]["15"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t6.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "17" :
                    values = String(values).replace(/'/g, '');
                    sqlWhere = " white_list_id in (" + values + ") ";
                    sql.wheres[groupid]["join"]["17"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t7.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "18" :
                    values = String(values).replace(/'/g, '');
                    sqlWhere = " white_list_id in (" + values + ") ";
                    sql.wheres[groupid]["join"]["18"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t8.member_no is null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "19" :
                    values = String(values).replace(/'/g, '');
                    //sql.wheres[groupid]["join"]["19"]["where"].push("1=1");
                    sql.wheres[groupid]["orderBy"].push("score_tag_result desc, rand(1)");
                    sql.wheres[groupid]["limit"] = values;
//                        delete sql[table];
//                        delete sql.wheres[groupid][table];
                    break;
                case "20" :
                    //values = String(values).replace(/\'/g, '');
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("包含某日定期产品到期用户--时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        });
                        return;
                    }
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(b.due_date) >='" + a[0] + "' and to_date(b.due_date) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ") ";
                    sql.wheres[groupid]["join"]["20"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t20.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                case "21" :
                    //values = String(values).replace(/\'/g, '');
                    var r = /\[(.+?)\]/g;
                    var m = values[0].match(r);
                    if (!m) {
                        $('.globalTip p').html("包含某日活包定产品到期用户--时间格式不正确！");
                        $('.globalTip').show();
                        $('.globalTip span').click(function () {
                            tipHide();
                        });
                        return;
                    }
                    var arr = [];
                    for (var k = 0; k < m.length; k++) {
                        var a = m[k].replace("\[", "").replace("\]", "").split(",");
                        arr.push(" (to_date(b.due_date) >='" + a[0] + "' and to_date(b.due_date) <='" + a[1] + "') ");
                    }
                    sqlWhere += " (" + arr.join(" or ") + ") ";
                    sql.wheres[groupid]["join"]["21"]["where"].push(sqlWhere);
                    sql.wheres[groupid]["joinWhere"]["t21.member_no is not null"] = "";
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    break;
                default:
                    delete sql[table];
                    delete sql.wheres[groupid][table];
                    continue;
            }

        }
    }

    var ws = sql.wheres;
    var finalsql = "";
    var unionAll = [];
    var base = " select t.* from ( select t.* from ( select member_no,mobile,named,device_cid,device_type,score_tag_result ";
    for (var group in ws) {
        var fsql = base;
        for (var table in ws[group]) {
            if (table === "join" || table === "joinWhere" || table === "or" || table === "limit" || table === "orderBy") {
                continue;
            }
            var s = sql[table];

            var where = "(" + ws[group][table][0] + ")";
            for (var i = 1; i < ws[group][table].length; i++) {
                where += " and (" + ws[group][table][i] + ")";
            }

            if (ws[group]["or"].length > 0)
                where += " or " + ws[group]["or"].join(" or ");

            fsql += s + where + ")t";
            //console.debug(ws[group]["join"]);
//                for (var i = 0; i < ws[group]["join"].length; i++) {
//                    finalsql += ws[group]["join"][i];
//                }
            for (var key in ws[group]["join"]) {
                if (ws[group]["join"][key]["where"].length === 0)
                    continue;
                fsql += ws[group]["join"][key]["sql"].replace("#where#", ws[group]["join"][key]["where"].join(" and "));
            }
        }
        if (fsql === base) {

            fsql += " from eye.bigtable)t ";

            for (var key in ws[group]["join"]) {
                if (ws[group]["join"][key]["where"].length === 0)
                    continue;
                fsql += ws[group]["join"][key]["sql"].replace("#where#", ws[group]["join"][key]["where"].join(" and "));
            }
        }

        var arr = [];
        for (var w in ws[group]["joinWhere"]) {
            arr.push(w);
        }
        if (arr.length > 0) {
            fsql += " where " + arr.join(" and ");
        }
        //fsql += " " + fsql;

        if (ws[group]["orderBy"].length > 0) {
            fsql += " order by " + ws[group]["orderBy"].join(",");
        }

        if (ws[group]["limit"].length > 0) {
            fsql += " limit " + ws[group]["limit"];
        }

        fsql += " )t";

        unionAll.push(fsql);
    }
    var user = $.cookie("userName");
    var exceptsArr = $("#except_proms").val();
    var includesArr = $("#include_proms").val();
    var finalWhere = [];
    var finalGroupBy = "";

    if (unionAll.length > 1) {
        finalsql = "select * from (" + unionAll.join(" union all ") + ")t";
        finalGroupBy = " group by member_no,mobile,named,device_cid,device_type,score_tag_result ";
    } else {
        finalsql = unionAll[0];
    }

    if (exceptsArr.length > 0) {
        var exceptsJson = JSON.parse($("#hide_except_proms").val());
        exceptsArr = exceptsArr.split(",");
        var arr = [];
        for (var e = 0; e < exceptsArr.length; e++) {
            arr.push("select member_no from hawkeye." + exceptsJson[exceptsArr[e]] + "_" + exceptsArr[e] + " ");
        }
        finalsql += " left outer join(select t.member_no from(" + arr.join(" union all ") + ")t group by member_no)t9 on t.member_no=t9.member_no ";
        finalWhere.push("t9.member_no is null");
    }

    if (includesArr.length > 0) {
        var includesJson = JSON.parse($("#hide_include_proms").val());
        includesArr = includesArr.split(",");
        var arr = [];
        for (var e = 0; e < includesArr.length; e++) {
            arr.push("select member_no from hawkeye." + includesJson[includesArr[e]] + "_" + includesArr[e] + " ");
        }
        finalsql += " left outer join(select t.member_no from(" + arr.join(" union all ") + ")t group by member_no)t10 on t.member_no=t10.member_no ";
        finalWhere.push("t10.member_no is not null");
    }

    if (finalWhere.length > 0) {
        finalsql += " where " + finalWhere.join(" and ");
    }

    if (finalGroupBy.length > 0) {
        finalsql += finalGroupBy;
    }
    console.debug(finalsql);
    return [finalsql, JSON.stringify(data)];

};

function send(finalsql, tags) {
    var d = {
        name: $("#prom_name").val(),
        sql: finalsql,
        tags: tags,
        excepts: $("#except_proms").val(),
        includes: $("#include_proms").val(),
        run_time: $("#startDate").val()
    };

    $.ajax({
        url: "/task/submit",
        async: true,
        type: 'POST',
        data: d,
        success: function (text, status) {
            alert(text);
            try {
                if (text === "1") {
                    alert("提交成功");
                    location.href = "/";
                } else {
                    //alert("提交任务失败." + text);
                    $("#submit-prom").removeAttr("disabled").text("提交").css("background-color", "#5bc9c9");
                }
            } catch (e) {
                console.error(e);
            }
        },
        complete: function (request, status) {
            if (status !== 'success') {
                console.debug(request);
                alert("提交任务失败..");
                $("#submit-prom").removeAttr("disabled").text("提交").css("background-color", "#5bc9c9");
            }
        },
        error: function (text, status) {
            console.debug(text);
        }
    });
    //
}



function downCallBack(e) {

    //
}

