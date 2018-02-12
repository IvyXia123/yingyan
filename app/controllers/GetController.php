<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eye\Controllers;

use Phalcon\Mvc\Controller;
use eye\Utils\MySqlDB,
    Phalcon\Db,
    eye\Utils\ResultMessage;

class GetController extends Controller {
    
    public $baseUrl = "http://ds.idc.xiwanglife.com/dataservice/getconfig.do?";

    public function menuAction() {
        $conn = MySqlDB::getConnection();
        $sql = "select * from list";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC);
        echo json_encode($result);
    }

    public function promsHistoryAction() {
        $submitter = $this->cookies->get("user")->getValue();
        $submitter = str_replace("\0", "", $submitter);
        $conn = MySqlDB::getConnection();
        //$sql = "select * from proms_history where recycle=0 and submitter=? order by id desc";
        $sql = "select ph.id,ph.name,ph.create_time,ph.task_status,ph.last_run_time,ph.list_count,ph.submitter from proms_history as ph join users as u on u.`name`=ph.submitter join users_rank as ur on ur.id=u.rid where ur.rank>=(select ur.rank from users as u join users_rank as ur on u.rid=ur.id where u.`name`=?) and recycle=0 order by ph.id desc limit 200";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$submitter]);
        echo json_encode($result);
    }

    public function promsHistoryLimitAction() {
        $submitter = $this->cookies->get("user")->getValue();
        $submitter = str_replace("\0", "", $submitter);
        $conn = MySqlDB::getConnection();
        $sql = "select ph.id,ph.name,ph.submitter from proms_history as ph join users as u on u.`name`=ph.submitter join users_rank as ur on ur.id=u.rid where ur.rank>=(select ur.rank from users as u join users_rank as ur on u.rid=ur.id where u.`name`=?) and recycle=0 order by ph.id desc limit 300";
        //$sql = "select * from proms_history where recycle=0 and submitter=? order by id desc limit 20";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$submitter]);
        echo json_encode($result);
    }
    
    public function onePromsHistoryAction() {
        $submitter = $this->cookies->get("user")->getValue();
        $submitter = str_replace("\0", "", $submitter);
        $conn = MySqlDB::getConnection();
        $id = $this->request->get("id");
        $sql = "select * from proms_history where id=? ";
        //$sql = "select ph.* from proms_history as ph join users as u on u.`name`=ph.submitter join users_rank as ur on ur.id=u.rid where ur.rank>=(select ur.rank from users as u join users_rank as ur on u.rid=ur.id where u.`name`=?) and recycle=0 order by ph.id desc limit 150";
        //$sql = "select * from proms_history where recycle=0 and submitter=? order by id desc limit 20";
        $result = $conn->fetchOne($sql, Db::FETCH_ASSOC, [$id]);
        echo json_encode($result);
    }
    
    public function promsHistoryLimit2Action() {
        $submitter = $this->cookies->get("user")->getValue();
        $submitter = str_replace("\0", "", $submitter);
        $limit = $this->request->get("limit");
        $conn = MySqlDB::getConnection();
        $sql = "select ph.id,ph.name,ph.create_time,ph.task_status,ph.last_run_time,ph.count,ph.submitter from proms_history as ph join users as u on u.`name`=ph.submitter join users_rank as ur on ur.id=u.rid where ur.rank>=(select ur.rank from users as u join users_rank as ur on u.rid=ur.id where u.`name`=?) and recycle=0 order by ph.id desc limit $limit,20";
        //$sql = "select * from proms_history where recycle=0 and submitter=? order by id desc limit 20";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$submitter]);
        echo json_encode($result);
    }

    public function menu2Action() {
        $conn = MySqlDB::getConnection();
        $sql = "select * from list";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC);
        //print_r ($result);
        $list = array();

        for ($i = 0; $i < count($result); $i++) {

            if (!array_key_exists($result[$i]["t1"], $list)) {
                $arr = array();
                $arr["id"] = $i;
                $arr["text"] = $result[$i]["t1"];
                $arr["children"] = array();
                $list[$result[$i]["t1"]] = $arr;
            } else {
                
            }


            if (!array_key_exists($result[$i]["t2"], $list[$result[$i]["t1"]]["children"])) {
                $arr2 = array();
                $arr2["text"] = $result[$i]["t2"];
                //echo $result[$i]["t2"]."<br/>";
                $list[$result[$i]["t1"]]["children"][$result[$i]["t2"]] = array($arr2);
            } else {
                //$arr2["text"] = str_replace("是否", "", $result[$i]["t2"]);
            }
            $arr2 = $list[$result[$i]["t1"]]["children"][$result[$i]["t2"]];
            //array_push($list[$result[$i]["t1"]]["children"], $arr2);
            //$arr2 = $list[$result[$i]["t1"]]["children"];
            //echo $arr2["text"];
            switch ($result[$i]["type"]) {
                case "1":
                    $arr3 = array();
                    $arr3["text"] = $result[$i]["t3"];
                    $arr3["type"] = "a";
                    if ($arr2["children"]) {
                        array_push($arr2["children"], $arr3);
                    } else {
                        $arr2["children"] = array($arr3);
                    }
                    break;
                case "2":
                    $arr3 = array();

                    $arr3["text"] = $arr2["text"];
                    $arr3["type"] = "input";
                    $arr3["placeholder"] = $arr2["text"];
                    if ($arr2["children"]) {
                        array_push($arr2["children"], array($arr3));
                    } else {
                        $arr2["children"] = array($arr3);
                    }
                    break;
                case "3":
                    $arr3 = array();
                    $arr3["text"] = $arr2["text"];
                    $arr3["type"] = "input";
                    $arr3["placeholder"] = $arr2["text"];
                    if ($arr2["children"]) {
                        array_push($arr2["children"], array($arr3));
                    } else {
                        $arr2["children"] = array($arr3);
                    }
                    break;
                case "4":
                    $arr3 = array();
                    $arr3["text"] = $arr2["text"];
                    $arr3["type"] = "input";
                    $arr3["placeholder"] = $arr2["text"];
                    if ($arr2["children"]) {
                        array_push($arr2["children"], array($arr3));
                    } else {
                        $arr2["children"] = array($arr3);
                    }
                    break;
            }
        }

        $list2 = array();

        foreach ($list as $value) {
            array_push($list2, $value);
        }

        echo json_encode($list2);
        //print_r($list);
    }

    public function bAction() {
        echo date("Ymd");
    }

    public function mysqlDataAction() {

        $json = $this->request->get('j');
        //$json = "{\"a\":\"11\",\"b\":\"22\"}";
        $params = "";
        if ($json) {
            $json = json_decode($json);
            //echo $json;
            $params = "?";
            foreach ($json as $key => $value) {
                $params .= $key . "=" . $value . "&";
            }
        }

        $ch = curl_init();
        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do" . $params;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_exec($ch);
        $r = curl_multi_getcontent($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        } else {
            curl_close($ch);
        }
        echo $r;
    }

    public function recycleAction() {
        $submitter = $this->cookies->get("user")->getValue();
        $submitter = str_replace("\0", "", $submitter);
        $conn = MySqlDB::getConnection();
        $sql = "select * from proms_history where recycle=1 and submitter=? order by id desc";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$submitter]);
        echo json_encode($result);
    }

    public function recycleInAction() {
        //$submitter = $this->cookies->get("user")->getValue();
        $id = $this->request->get("id");
        $conn = MySqlDB::getConnection();
        $conn->update("proms_history", array("recycle"), array(1), "id = " . $id);
        //echo json_encode($result);
    }

    public function recycleOutAction() {
        //$submitter = $this->cookies->get("user")->getValue();
        $id = $this->request->get("id");
        $conn = MySqlDB::getConnection();
        $conn->update("proms_history", array("recycle"), array(0), "id = " . $id);
        //echo json_encode($result);
    }

    public function downStatusAction() {
        $user = $this->cookies->get("user")->getValue();
        $user = str_replace("\0", "", $user);
        $conn = MySqlDB::getConnection();
        $sql = "select id,down_name,status,file_path,down_count from down_history where user=? and down_count=0 and status='已完成' order by id desc limit 50";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$user]);
        echo json_encode($result);
    }

    public function submitPromEffectAction() {
        sleep(10);
        $r = new ResultMessage();
        $user = $this->cookies->get("user")->getValue();
        $user = str_replace("\0", "", $user);

//        $params = json_decode($this->request->get("params"));
//        echo $params;

        $start = $this->request->get("start");
        $end = $this->request->get("end");
        $type = $this->request->get("type");
        $id = $this->request->get("id");
        $hive_table = $this->request->get("hive_table");
        
        
        $conn = MySqlDB::getConnection();
        
        $sql2 = "select id from proms_effect where prom_id=? and status='正在查询'";
        $result2 = $conn->fetchAll($sql2, Db::FETCH_ASSOC, [$id]);
        if(count($result2) > 0){
            @header('Content-type: text/html;charset=UTF-8');
            $r -> setCode(1);
            $r -> setMessage("有其他用户正在查询该活动的活动效果,请稍后再试.");
            echo $r->toJson();
            return;
        }

        $tmp_hive_table1 = "hawkeye.tmp_" . time();
        $tmp_hive_table2 = $tmp_hive_table1 . "_2";

        $params = $this->request->get("param");

        $time = date("Y-m-d H:i:s");

//        $start = $params->start;
//        $end = $params->end;
//        $type = $params->type;
//        $id = $params->id;

        $base = "/var/www/eye/public/effects/";
        $csvFile = $base . $id . "_" . $type . "_" . time();
        $errFile = $csvFile . "_err";

        $sql = "";
        switch ($type) {
            case "券使用情况";
                $coupos = $this->request->get("coupos");
                //$coupos = $params->coupos;
                if ($coupos) {
                    $dir = __DIR__;
                    $sqlFile = str_replace("controllers", "ext/copou_effect.sql", $dir);
                    $sql = file_get_contents($sqlFile);
                    $start2 = $this->request->get("start2");
                    $end2 = $this->request->get("end2");
                    $sql = str_replace("#bdate#", "to_date(b.trans_time)>='" . $start2 . "' and to_date(b.trans_time)<='" . $end2 . "'", $sql);
                    $sql = str_replace("#adate#", "to_date(a.create_time)>='" . $start . "' and to_date(a.create_time)<='" . $end . "'", $sql);
                    $sql = str_replace("#coupos#", $coupos, $sql);
                    $sql = str_replace("#tmp_hive_table1#", $tmp_hive_table1, $sql);
                    $sql = str_replace("#tmp_hive_table2#", $tmp_hive_table2, $sql);
                    $sql = str_replace("#hive_table#", $hive_table, $sql);

                    $sqlFilePath = "/tmp/$id" . "_". time() . ".hql";
                    $sqlFileTemp = fopen($sqlFilePath, "w");
                    fwrite($sqlFileTemp, $sql);
                    fclose($sqlFileTemp);

                    $command = "/usr/bin/spark-sql -S -f $sqlFilePath 1>$csvFile 2>$errFile";
                    //$command = "/usr/bin/hive -e \" $sql \" 1>$csvFile 2>$errFile";
                }
                break;
            case "交易情况";
                $procs = $this->request->get("procs");
                //$procs = $params->procs;
                if ($procs) {//，
                    $procs = str_replace("，", ",", $procs);
                    $procs = explode(",", $procs);
                    for ($i = 0; $i < count($procs); $i++) {
                        $procs[$i] = "c.product_name like '%" . $procs[$i] . "%'";
                    }
                    $procs_c = " and (" . join(" or ", $procs) . ")";
                    $procs_d = str_replace("c.product_name", "d.product_name", $procs1);
                } else {
                    $procs = "";
                }
                //$sql = "select t1.product_name,t1.display_rate,t1.invest_days,t1.min_trans_time,t1.max_trans_time,t1.trans_cnt,t1.trans_usr_cnt,t1.trans_amt,t1.discount_amt,t2.invest_income,t2.invest_addrate from (select c.id,c.product_name,c.display_rate,datediff(c.due_date,c.inception_date) as invest_days,min(b.trans_time) as min_trans_time,max(b.trans_time) as max_trans_time,count(*) as trans_cnt,count(distinct b.member_no) as trans_usr_cnt,sum(b.fund_trans_amount) as trans_amt,sum(b.discount_amount) as discount_amt from bi.wy_regnottrans0707 a join eif_ftc.t_ftc_fund_trans_order b on a.member_no=b.member_no join eif_fis.t_fis_prod_info c on b.product_id=c.id where b.status in (6,9,11) and to_date(b.trans_time)>='$start' and to_date(b.trans_time)<='$end' $procs group by c.id,c.product_name,c.display_rate,datediff(c.due_date,c.inception_date))t1 join(SELECT c.id,c.product_name,sum(b.expect_bonus_amount) as invest_income,sum(b.expect_profit_amount) as invest_addrate from(select c.fund_detail_uuid,b.member_no from bi.wy_regnottrans0707 a join eif_ftc.t_ftc_fund_trans_order b on a.member_no=b.member_no join eif_ftc.t_amc_fund_detail_alteration c on b.fund_trans_order_no=c.ftc_order_no join eif_fis.t_fis_prod_info d on b.product_id=d.id where b.status in (6,9,11) and to_date(b.trans_time)>='$start' and to_date(b.trans_time)<='$end' $procs group by c.fund_detail_uuid,b.member_no)a join eif_ftc.t_amc_fund_detail b on a.fund_detail_uuid=b.fund_detail_uuid join eif_fis.t_fis_prod_info c on b.product_id=c.id group by c.id,c.product_name)t2 on t1.id=t2.id;";

                $dir = __DIR__;
                $sqlFile = str_replace("controllers", "ext/deal_effect.sql", $dir);
                $sql = file_get_contents($sqlFile);
                $sql = str_replace("#bdate#", "to_date(b.trans_time)>='" . $start . "' and to_date(b.trans_time)<='" . $end . "'", $sql);
                $sql = str_replace("#procs_c#",$procs_c,$sql);
                $sql = str_replace("#procs_d#",$procs_d,$sql);

                $sqlFilePath = "/tmp/$id" . "_". time() . ".hql";
                $sqlFileTemp = fopen($sqlFilePath, "w");
                fwrite($sqlFileTemp, $sql);
                fclose($sqlFileTemp);

                $command = "/usr/bin/spark-sql -S -f $sqlFilePath 1>$csvFile 2>$errFile";
                break;
        }
        
        $values = array($id, $start, $end, $params, "正在查询", $user, $type, $sql);
        $colums = array("prom_id", "start", "end", "query_params", "status", "submitter", "type", "sql");
        $conn->insert("proms_effect", $values, $colums);
        $qid = $conn->lastInsertId();
        
        passthru($command, $res);
        
        passthru("sed -i '1,4d' $csvFile", $res11);
        $count = shell_exec("cat $csvFile | wc -l");
        $head200 = shell_exec("head -n 200 $csvFile");
        
        $conn->update("proms_effect", array("status", "count", "head200"), array("已完成", $count, $head200), "id = " . $qid);
        
        echo $res;
    }

    public function promEffectAction() {
        $prom_id = $this->request->get("prom_id");
        $user = $this->cookies->get("user")->getValue();
        $user = str_replace("\0", "", $user);
        $conn = MySqlDB::getConnection();
        
        //$sql = "select * from (select * from proms_effect where query_time=(select max(query_time) from proms_effect where prom_id=? and type='券使用情况' and submitter=?) and prom_id=? and submitter=? limit 1) as a1 union all select * from (select * from proms_effect where query_time=(select max(query_time) from proms_effect where prom_id=? and type='交易情况' and submitter=?) and prom_id=? and submitter=? limit 1) as a2";
        $sql = "select * from (select * from proms_effect where query_time=(select max(query_time) from proms_effect where prom_id=? and type='券使用情况') and prom_id=? limit 1) as a1 union all select * from (select * from proms_effect where query_time=(select max(query_time) from proms_effect where prom_id=? and type='交易情况') and prom_id=? limit 1) as a2";
        //$result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$prom_id,$user,$prom_id,$user,$prom_id,$user,$prom_id,$user]);
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$prom_id, $prom_id, $prom_id, $prom_id]);
        echo json_encode($result);
    }
    
    public function cancelPromEffectAction() {
        $prom_id = $this->request->get("prom_id");
        $conn = MySqlDB::getConnection();
        $conn->update("proms_effect", array("status"), array("已完成"), "prom_id = " . $prom_id);
    }
    
    public function modelTreeAction() {
        $conn = MySqlDB::getConnection();
        $sql = "select * from trans_member_info";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC);
        echo json_encode($result);
    }
    
    public function cancelPromAction(){
        $prom_id = $this->request->get("prom_id");
        $conn = MySqlDB::getConnection();
        $r = $conn->update("proms_history", array("task_status"), array("已取消"), "id = " . $prom_id);
        echo $r;
    }
    
    public function testAction() {
        $dir = __DIR__;
        $sqlFile = str_replace("controllers", "ext/copou_effect.sql", $dir);
        $sql = file_get_contents($sqlFile);
        echo $sql;
    }
    
    public function test2Action() {

        $ch = curl_init();
        $url = "https://10.127.133.89:8080?" . "action=login&username=azkaban&password=azkaban";
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_exec($ch);
        $r = curl_multi_getcontent($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        } else {
            curl_close($ch);
        }
        echo $r;
    }
    
    public function selfReportGetConfigByIdAction() {

        $baseUrl = "http://ds.idc.xiwanglife.com/view/getConfigById.do?";
        $params = '';
        foreach ($_GET as $key => $value) {
            if($key === "_url")
                continue;
            $params .= $key . "=" .$value . "&";
        }

        foreach ($_POST as $key => $value) {
            if($key === "_url")
                continue;
            $params .= $key . "=" .$value . "&";
        }
        
        $ch = curl_init();
        $url = $baseUrl . $params;
        echo $url;

//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        curl_exec($ch);
//        $r = curl_multi_getcontent($ch);
//        if (curl_errno($ch)) {
//            echo curl_error($ch);
//        } else {
//            curl_close($ch);
//        }
//        echo $r;
    }
    
    public function selfReportGetTreeAction() {

        $baseUrl = "http://ds.idc.xiwanglife.com/view/getTree.do?";
        $params = '';
        foreach ($_GET as $key => $value) {
            if($key === "_url")
                continue;
            $params .= $key . "=" .$value . "&";
        }

        foreach ($_POST as $key => $value) {
            if($key === "_url")
                continue;
            $params .= $key . "=" .$value . "&";
        }
        
        $ch = curl_init();
        $url = $baseUrl . $params;
        //echo $url;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_exec($ch);
        $r = curl_multi_getcontent($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        } else {
            curl_close($ch);
        }
        echo $r;
    }
    
    public function dsServiceAction() {
        
        //$baseUrl = "http://ds.idc.xiwanglife.com/view/getTree.do?";
        $baseUrl = "http://ds.idc.xiwanglife.com/view/";
        
        $params = '';
        foreach ($_GET as $key => $value) {
            if($key === "_url"){
                continue;
            }else if($key === "do"){
                $baseUrl .= $value . ".do?";
                continue;
            }
            $params .= $key . "=" .$value . "&";
        }

        foreach ($_POST as $key => $value) {
            if($key === "_url"){
                continue;
            }else if($key === "do"){
                $baseUrl .= $value . ".do?";
                continue;
            }
            $params .= $key . "=" .$value . "&";
        }
        
        $ch = curl_init();
        $url = $baseUrl . $params;
        //echo $url;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_exec($ch);
        $r = curl_multi_getcontent($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        } else {
            curl_close($ch);
        }
        echo $r;
    }
    
    public function downHistoryAction() {
        $user = $this->cookies->get("user")->getValue();
        $user = str_replace("\0", "", $user);
        $conn = MySqlDB::getConnection();
        $sql = "select down_name,file_path,time from down_history where user=? and status='已完成' order by id desc limit 100";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$user]);
        echo json_encode($result);
    }

}
