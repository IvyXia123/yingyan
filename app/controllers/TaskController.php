<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eye\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use eye\Utils\MySqlDB,
    Phalcon\Db,
    eye\Utils\Pinyin;
use eye\Utils\ResultMessage;
use eye\Plugins\MySendMail;

class TaskController extends Controller {

    public function downloadAction() {
        $rm = $this->di->get("rm");
        $id = 0;
        $errFile = "";
        $conn = MySqlDB::getConnection();
        try {

            $user = $this->cookies->get("user")->getValue();
            $user = str_replace("\0", "", $user);

            $fields = $this->request->get("fields");
            $id = $this->request->get("id");
            $downType = $this->request->get("downType");

            $sql = "select * from proms_history where id=?";
            $r = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$id]);

            $r = $r[0];
            //mobile,named,device_type,device_cid,member_no
            $sql = "select " . $fields . " from " . $r["hive_table"];

            $fieldsAlias = array();
            if (strstr($fields, "mobile")) {
                array_push($fieldsAlias, "手机号码");
            }

            if (strstr($fields, "named")) {
                array_push($fieldsAlias, "称呼");
            }

            if (strstr($fields, "device_type")) {
                array_push($fieldsAlias, "设备类型");
            }

            if (strstr($fields, "device_cid")) {
                array_push($fieldsAlias, "CID");
            }

            if (strstr($fields, "member_no")) {
                array_push($fieldsAlias, "会员号");
            }

            $fieldsAlias = join(",", $fieldsAlias);

            $time = date("Y-m-d H:i:s");

            $downName = str_replace(" ", "_", $r["name"]) . "_" . date("Y-m-d") . ".zip";
            $serverFileName = $id . "_" . time();

            $base = "/var/www/eye/public/files/";
            $csvPath = $base . $serverFileName;
            $errPath = "$csvPath.err";

            //$conn->begin();
            $values = array($user, $time, $sql, "正在导出数据", "/files/$serverFileName.zip", $downName, 0);
            $colums = array("user", "time", "sql", "status", "file_path", "down_name", "down_count");

            $conn->insert("down_history", $values, $colums);
            //$conn->commit();
            $id = $conn->lastInsertId();

            if ($downType == "excel") {
                $command = "/usr/bin/hive -e \" $sql \" 1>$csvPath 2>$errPath";
                passthru($command, $res);
                //$res = 0;
                $errFile = str_replace("rn", "<br/>", file_get_contents($errPath));


                $csvBase = $csvPath . "_csv";
                $splitCommand = "mkdir -p $csvBase;split -d -l 300000 $csvPath $csvBase/$serverFileName" . "_csv_";
                passthru($splitCommand, $res2);
                $fieldsAlias = str_replace(",", "\t", $fieldsAlias);
                passthru("cd $csvBase;ls|xargs -n1 -i{} sed -i '1i $fieldsAlias' {}", $res0);
                passthru("cd /var/www/eye/app/ext;/usr/jdk/bin/java -jar HTool.jar EyeCreateExcel $csvBase", $res3);
                passthru("/usr/bin/zip -qrj $csvBase/$serverFileName.zip $csvBase/*xlsx", $res4);

                //$conn->update("down_history", array("status", "log"), array("已完成", $errFile), "id = " . $id);

                if ($res == 0 && $res2 == 0 && $res3 == 0 && $res4 == 0) {
                    passthru("mv $csvBase/$serverFileName.zip $base;rm -rf $csvBase $csvPath", $res5);
                } else {
                    throw new Exception("执行命令遇到错误,$command");
                }
            } else {
                $csvSepa = " | tr '\t' ',' ";
                $command = "/usr/bin/hive -e \" $sql \" $csvSepa 1>$csvPath 2>$errPath";
                passthru($command, $res);
                $errFile = str_replace("rn", "<br/>", file_get_contents($errPath));

                $csvBase = $csvPath . "_csv";
                $splitCommand = "mkdir -p $csvBase;split -d -l 1000000 $csvPath $csvBase/$serverFileName";
                passthru($splitCommand, $res2);
                passthru("cd $csvBase;ls|xargs -n1 -i{} sed -i '1i $fieldsAlias' {}", $res00);
                passthru("cd $csvBase;ls|xargs -n1 -i{} mv {} {}.csv", $res3);
                passthru("/usr/bin/zip -qrj $csvBase/$serverFileName.zip $csvBase/*csv", $res4);

                //$conn->update("down_history", array("status", "log"), array("已完成", $errFile), "id = " . $id);

                if ($res == 0 && $res2 == 0 && $res3 == 0 && $res4 == 0) {
                    passthru("mv $csvBase/$serverFileName.zip $base;rm -rf $csvBase $csvPath", $res5);
                } else {
                    throw new Exception("执行命令遇到错误,$command");
                }
            }

            $rm->setCode($res);
            $rm->setMessage("success");
            $rm->param["fileName"] = $r["name"] . ".zip";
            $rm->param["filePath"] = "/files/" . "$serverFileName.zip";
            $rm->param["command"] = $command;
        } catch (Exception $ex) {
            $rm->setCode(1);
            $rm->setMessage($ex);
            $errFile = $errFile . $ex->getMessage();
        } finally {
            $conn = null;
            $conn = MySqlDB::getConnection();
            $conn->update("down_history", array("status", "log"), array("已完成", $errFile), "id = " . $id);
            $conn = null;
        }
    }

    public function downloadCountAction() {
        try {
            $id = $this->request->get("id");
            $conn = MySqlDB::getConnection();
            $conn->update("down_history", array("down_count"), array(1), "id = " . $id);
        } catch (Exception $ex) {
            
        }
    }

    public function submitAction() {

//        echo "11";
//        if (function_exists('fastcgi_finish_request')) {
//            ob_flush();
//            flush();
//            fastcgi_finish_request();
//        }
//
//        sleep(5);
        $rm = $this->di->get("rm");
        $name = $this->request->get("name");
        $sql = $this->request->get("sql");
        $tags = $this->request->get("tags");
        $excepts = $this->request->get("excepts");
        $includes = $this->request->get("includes");
        $run_time = $this->request->get("run_time");

        $submitter = str_replace("\0", "", $this->cookies->get("user")->getValue());

        $pinyin = new Pinyin();
        $a = $pinyin->convert($name);
        $pinyinName = implode($a);

        $createTime = date("Y-m-d H:i:s");

        $pinyinName = $pinyinName . date("Y-m-d");
        $id = 0;

        $status = "正在执行";
        $error = NULL;

        $where = array();

        try {

            $finalSql = $sql;

            if ($run_time && strlen($run_time) > 0) {
                $status = "等待执行";
                $last_run_time = $run_time;
            } else {
                $run_time = NULL;
                $last_run_time = $createTime;
            }

            $conn = MySqlDB::getConnection();
            $values = array($name, $sql, $tags, $status, $createTime, $last_run_time, "0", $submitter, $pinyinName, "nofile", '0', $finalSql, 0, $excepts, $includes);
            $colums = array("name", "sql", "tags", "task_status", "create_time", "last_run_time", "list_count", "submitter", "pinyin_name", "file_status", "hive_table", "final_sql", "recycle", "excepts", "includes");

            $insert_res = $conn->insert("proms_history", $values, $colums);
            $id = $conn->lastInsertId();            //hive table name = pinyin(name) + submitter + create_time
            $hiveTable = "hawkeye.$submitter" . "_" . $id;
            $tableName = $submitter . "_" . $id;

            //$conn->update("proms_history", array("hive_table"), array($hiveTable), "id = " . $id);

            if ($id < 1) {
                throw new Exception("insert new task faild");
            }

            //$run_time
            if ($run_time && strlen($run_time) > 0) {
                
            } else {
                echo $insert_res;
                $size = ob_get_length();
                header("Content-Length: $size");  
                header("Connection: Close");
                ob_end_flush();
                ob_flush();
                flush();
                
                ignore_user_abort(true);
                
                $error = $this->runTask($hiveTable, $finalSql, $id, $tableName);
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            echo $error;
        } finally {
            $conn = null;
            $conn = MySqlDB::getConnection();
            $conn->update("proms_history", array("hive_table", "error"), array($hiveTable, $error), "id = " . $id);
            $conn = null;
        }
    }

    private function runTask($hiveTable, $finalSql, $id, $tableName) {

        $error = NULL;
        $count = 0;
        try {
            $create = "drop table " . $hiveTable . ";create table " . $hiveTable . " (member_no string,mobile string,named string,device_cid string,device_type string, score_tag_result int);";
            $insert = " insert into " . $hiveTable . " ";
            $finalSql = $create . $insert . $finalSql;

            $command = "/usr/bin/hive -e \"" . $finalSql . "\"";

            passthru($command, $res);

            if ($res == 0) {
                $count = shell_exec("/usr/bin/hdfs dfs -cat hdfs://arescluster/apps/hive/warehouse/hawkeye.db/$tableName/* | wc -l");
            } else {
                $error = "run task error";
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage();
        } finally {
            $conn = null;
            $conn = MySqlDB::getConnection();
            $conn->update("proms_history", array("task_status", "count"), array("已完成", $count), "id = " . $id);
            $conn = null;
            return $error;
        }
    }

    public function schedulerAction() {
        try {
            $conn = MySqlDB::getConnection();
            $sql = "select * from proms_history where task_status='等待执行'";
            $result = $conn->fetchAll($sql, Db::FETCH_ASSOC);

            $now = date("Y-m-d H:i:s");
            //echo $now . "  ";
            $now = strtotime($now);
            //echo $now . "<br/>";
            for ($i = 0; $i < count($result); $i++) {
                //echo $result[$i]["last_run_time"] . "  ";
                $time = strtotime($result[$i]["last_run_time"]);
                //date("Ymd H:i:s", strtotime($time));
                //echo $time . "<br/>";
                if ($time < $now) {
                    $id = $result[$i]["id"];
                    $hiveTable = $result[$i]["hive_table"];
                    $finalSql = $result[$i]["final_sql"];
                    $submitter = $result[$i]["submitter"];
                    $tableName = $submitter . "_" . $id;

                    $conn = MySqlDB::getConnection();
                    $conn->update("proms_history", array("task_status"), array("正在执行"), "id = " . $id);
                    $conn = null;

                    echo $hiveTable . "<br/><br/>";
                    echo $finalSql . "<br/><br/>";
                    echo $id . "<br/>";
                    echo $tableName . "<br/>";
                    $error = $this->runTask($hiveTable, $finalSql, $id, $tableName);

                    if ($error && strlen($error) > 0) {
                        $conn = MySqlDB::getConnection();
                        $conn->update("proms_history", array("error"), array($error), "id = " . $id);
                        $conn = null;
                    }

                    break;
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function testAction() {
//        $a = array("aaa");
//        $rm = $this->di->get("rm");
//        //passthru("sh /Users/zhaopeng/shell-files/b.sh 14 > /dev/null 2>&1 &");
//        $rm->setCode(1);
//        $rm->setMessage("失败$a[0]");
//        $submitter = $this->cookies->get("user")->getValue();
        //echo $submitter;
        echo date("Y-m-d H:i:s") . "<br/>";
        echo date("y-M-D H:i:s");
        //echo $rm->toJson();
    }

    public function mailAction() {


        $bigtable = shell_exec("/home/www/day_check/bigtable.sh");
        $openBaoBox = shell_exec("/home/www/day_check/open_bao_box.sh");
        $xplan = shell_exec("/home/www/day_check/xplan.sh");

//        $bigtable = "aaaaa<br/><br/><br/>";
//        $openBaoBox = "bbbbb<br/><br/><br/>";
//        $xplan = "ccccc";

        $mail = new MySendMail();
        $mail->setServer("smtp.163.com", "laoshanlu316@163.com", "pinganjinke");
        $mail->setFrom("laoshanlu316@163.com");
        $mail->setReceiver("61215@163.com");

        $mail->setMailInfo("day-check", $bigtable . "<br/><br/><br/>" . $openBaoBox . "<br/><br/><br/>" . $xplan);
        $mail->sendMail();
    }

}
