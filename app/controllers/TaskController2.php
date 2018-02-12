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
    eye\Utils\Pinyin;
use eye\Utils\ResultMessage;

class TaskController extends Controller {

    public function submitAction() {

        $r = $this->di->get("rm");
        $name = $this->request->get("name");
        $sql = $this->request->get("sql");
        $tags = $this->request->get("tags");


//        $name = "name";
//        $sql = "sql";
//        $tags = "tags";

        $submitter = $this->cookies->get("user")->getValue();

        $pinyin = new Pinyin();
        $a = $pinyin->convert($name);
        $pinyinName = implode($a);

        $createTime = date("Y-m-d H:s:m");

        $pinyinName = $pinyinName . date("Y-m-d");

//        $hive_table_name = $submitter . $create_time . $pinyinName;
//        $a1 = array(" ", "　", "\t", "\n", "\r");
//        $a2 = array("", "", "", "", "");
//        $hive_table_name = str_replace(" ", "", $hive_table_name);
//        $hive_table_name = str_replace("-", "", $hive_table_name);
//        $hive_table_name = str_replace(":", "", $hive_table_name);

        try {

            $conn = MySqlDB::getConnection();
            $conn->begin();
            $values = array($name, $sql, $tags, "submitted", $createTime, "测试", $createTime, "0", $submitter, $pinyinName, "nofile");
            //hive table name = pinyin(name) + submitter + create_time
            $colums = array("name", "sql", "tags", "task_status", "create_time", "list_desc", "last_run_time", "list_count", "submitter", "pinyin_name", "file_status");

            $conn->insert("proms_history", $values, $colums);
            $id = $conn->lastInsertId();

            if ($id < 1) {
                throw new Exception("insert new task faild");
            }

            $conn->commit();
        } catch (Exception $ex) {
            $conn->rollback();
            $r->setMessage("提交任务失败");
            $r->setCode(1);
        } finally {
            $conn = null;
            echo $r->toJson();
        }
    }

    public function downloadAction() {
        $rm = $this->di->get("rm");
        try {
            $id = $this->request->get("id");
            $conn = MySqlDB::getConnection();
            $sql = "select * from proms_history where id=?";
            $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$id]);

            $conn = null;
            $r = $result[0];
            $sql = $r['sql'];
            $sqlArr = explode("union all", $sql);
            $finallSql = "";
            for ($i = 0; $i < count($sqlArr); $i++) {
                $finallSql .= " select t.* from (select member_no,mobile,named,device_cid,device_type " . $sqlArr[$i];
                if ($i != count($sqlArr) - 1) {
                    $finallSql .= " union all ";
                }
            }
            //echo $finallSql."<br/>";
            $finallSql = $finallSql.";";
            //echo $path;
            $csvPath = "/var/www/eye/public/files/" . $r["pinyin_name"];
            $command = "sh /var/www/hive-job.sh " . $path . " " . $csvPath;
            //passthru($command, $res);
            $rm->setMessage("success");
            $rm->param["fileName"] = $r["name"];
            $rm->param["filePath"] = "/files/" . $r["pinyin_name"];
            $rm->param["command"] = $command;
        } catch (Exception $ex) {
            $rm->setCode(1);
            $rm->setMessage("准备文件失败");
        } finally {
            echo $rm->toJson();
        }
    }

    public function testAction() {
        $rm = $this->di->get("rm");
        //passthru("sh /Users/zhaopeng/shell-files/b.sh 14 > /dev/null 2>&1 &");
        $rm->setCode(1);
        $rm->setMessage("失败");
        echo $rm->toJson();
    }

}
