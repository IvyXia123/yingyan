<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eye\Controllers;

use Phalcon\Mvc\Controller,
    Phalcon\Db;
use eye\Utils\MySqlDB,
    eye\Utils\Pinyin;

class LoginController extends Controller {

    public function indexAction() {
        
    }

    public function bAction() {
        $html = "appsTableData=[ [\"<a href='/cluster/app/application_1471303506455_104222'>application_1471303506455_104222</a>\",\"www\",\"insert into hawkeye.zpp_472  select t.*...)t(Stage-3)\",\"MAPREDUCE\",\"root.default\",\"1479809064838\",\"0\",\"RUNNING\",\"UNDEFINED\",\"130\",\"130\",\"1008640\",\"49.7\",\"49.7\",\"<br title='27.5'> <div class='ui-progressbar ui-widget ui-widget-content ui-corner-all' title='27.5%'> <div class='ui-progressbar-value ui-widget-header ui-corner-left' style='width:27.5%'> </div> </div>\",\"<a href='http://bigdata-namenode01.dev.wgq.hdb.com:8088/proxy/application_1471303506455_104222/'>ApplicationMaster</a>\",\"0\"],[\"<a href='/cluster/app/application_1471303506455_104221'>application_1471303506455_104221</a>\",\"www\",\"insert into hawkeye.zpp_471  select t.*...)t(Stage-3)\",\"MAPREDUCE\",\"root.default\",\"1479809019402\",\"0\",\"RUNNING\",\"UNDEFINED\",\"105\",\"105\",\"983040\",\"48.5\",\"48.5\",\"<br title='37.8'> <div class='ui-progressbar ui-widget ui-widget-content ui-corner-all' title='37.8%'> <div class='ui-progressbar-value ui-widget-header ui-corner-left' style='width:37.8%'> </div> </div>\",\"<a href='http://bigdata-namenode01.dev.wgq.hdb.com:8088/proxy/application_1471303506455_104221/'>ApplicationMaster</a>\",\"0\"]   ]";
        $html = str_replace(" ", "", $html);
        $isMatch = preg_match("/appsTableData=(\[\[.*?\]\])/", $html, $match);
        $match = str_replace("appsTableData=", "", $match[0]);
        preg_match("/(\[.*?\])/", $match, $match2);
        //preg_match("/(^\[{1}.*?\]$)/", $match, $match2);
        //print_r ($match2);
        for ($i = 0; $i < count($match2); $i++) {
            $r = str_replace("[", "", $match2[$i]);
            $r = str_replace("]", "", $r);
            $r = str_replace("\"", "", $r);
            $arr = split(",", $r);
            echo $arr[0];
            echo $r . "<br/><br/>";
        }
//        echo $match;
//        $match = eval($match);
//        print_r ($match[0]);
    }

    public function doLoginAction() {

        try {
            $res = array();
            $name = $this->request->get('name');
            $pass = $this->request->get('pass');

            $url = "http://us.xiwanglife.com/userservice/login.do?userName=$name&password=$pass";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            $usRes = curl_multi_getcontent($ch);
            if (curl_errno($ch)) {
                echo curl_error($ch);
                return;
            } else {
                curl_close($ch);
            }

            $usRes = json_decode($usRes);
            
            if (isset($usRes->status) && $usRes->status != 200) {
                $res['c'] = -1;
                $res['t'] = '密码错误';
            } else {
//                $conn = MySqlDB::getConnection();
//                $sql = "select u1.*, u2.name as rank_name,u2.rank from users as u1 join users_rank as u2 where u1.name=? and u1.rid=u2.id";
//
//                $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$name, $pass]);
//
//                if (count($result) != 1) {
//                    $res['c'] = -1;
//                    $res['t'] = '密码错误';
//                } else {
//                    $r = $result[0];
//                    $expire = time() + 3600 * 24 * 365;
//                    
//                    $this->cookies->set('puser', $r['rank_name'], $expire);
//                    $this->cookies->set('user', $r['name'], $expire);
//                    $res['c'] = 0;
//                    $res['t'] = '/';
//                }
                $conn = MySqlDB::getConnection();
                $sql = "select u1.*, u2.name as rank_name,u2.rank from users as u1 join users_rank as u2 where u1.name=? and u1.rid=u2.id";

                $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$name, $pass]);

                if (count($result) == 0) {
                    $rid = $conn->fetchOne("select id,name from users_rank where name='staff'", Db::FETCH_ASSOC);
                    $rank_name = $rid['name'];
                    $rid = $rid["id"];
                    $sql = "insert into users(name, pass, rid) values($name, $pass, $rid)";
                    //$conn->fetchAll($sql, Db::FETCH_ASSOC, [$name, $pass]);
                    $colums = array("name", "pass", "rid");
                    $values = array($name, $pass, $rid);
                    $conn->insert("users", $values, $colums);
                } else {
                    $rank_name = $result[0]['rank_name'];
                }

                $expire = time() + 3600 * 24 * 365;

                $this->cookies->set('puser', $rank_name, $expire);
                $this->cookies->set('user', $name, $expire);
                $res['c'] = 0;
                $res['t'] = '/';
            }
            $conn = null;
            echo json_encode($res);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function downStatusAction() {
        $user = $this->cookies->get("user")->getValue();
        $user = str_replace("\0", "", $user);
        if (!$user || strstr($user)) {
            $user = $this->request->get("user");
        }
        if (!$user || strstr($user)) {
            echo "[]";
            return;
        }
        $conn = MySqlDB::getConnection();
        $sql = "select id,down_name,status,file_path,down_count from down_history where user=? and down_count=0 and status='已完成' order by id desc limit 50";
        $result = $conn->fetchAll($sql, Db::FETCH_ASSOC, [$user]);
        echo json_encode($result);
    }

    public function downloadCountAction() {
        try {
            $id = $this->request->get("id");
            $conn = MySqlDB::getConnection();
            $conn->update("down_history", array("down_count"), array(1), "id = " . $id);
        } catch (Exception $ex) {
            
        }
    }

    public function test3Action() {
        $pinyin = new Pinyin();
        $a = $pinyin->convert('新手大礼包');
//        echo implode($a);
//        echo date("Y-m-d-H:s:m");

        $a = 1988;
        echo $a * 0.0001 + 2;
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

}
