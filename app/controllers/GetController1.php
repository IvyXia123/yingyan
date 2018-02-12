<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HCharts\Controllers;

use Phalcon\Mvc\Controller;
use ClusterMan\utils\MySqlDB;

class GetController extends Controller {

    public function dealAction() {

        //20160724
        $id = 11;
        $start = $this->request->get('today');
        $yestoday = date("Ymd",strtotime("$start -1 day"));
        $beforeYestoday = date("Ymd",strtotime("$start -2 day"));
        $ch = curl_init();
        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
                "&today=" . $start . "&yesterday=" . $yestoday . "&yesterday2=" . $beforeYestoday;
        $data = array(
            'id' => $id,
            'today' => $start
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        $r = curl_multi_getcontent($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        } else {
            curl_close($ch);
        }
        //$rr = json_decode($r);
        //print_r($rr);
        //echo json_encode($rr);
        //echo '{"id":10,"configName":"数据报表","configType":"1","details":{"yesterday_total":{"id":3,"values":[]},"today_hour":{"id":1,"values":[{"time_key":2016071500,"time_name":"2016-07-15 00","register_count":565,"deal_count":3,"deal_amount":5.21},{"time_key":2016071501,"time_name":"2016-07-15 01","register_count":33,"deal_count":1,"deal_amount":1.00},{"time_key":2016071502,"time_name":"2016-07-15 02","register_count":85,"deal_count":6,"deal_amount":5.39},{"time_key":2016071503,"time_name":"2016-07-15 03","register_count":53,"deal_count":4,"deal_amount":1.82},{"time_key":2016071504,"time_name":"2016-07-15 04","register_count":41,"deal_count":7,"deal_amount":10.55},{"time_key":2016071505,"time_name":"2016-07-15 05","register_count":68,"deal_count":6,"deal_amount":12.31},{"time_key":2016071506,"time_name":"2016-07-15 06","register_count":227,"deal_count":66,"deal_amount":165.93},{"time_key":2016071507,"time_name":"2016-07-15 07","register_count":520,"deal_count":149,"deal_amount":303.72},{"time_key":2016071508,"time_name":"2016-07-15 08","register_count":828,"deal_count":156,"deal_amount":253.83},{"time_key":2016071509,"time_name":"2016-07-15 09","register_count":1172,"deal_count":257,"deal_amount":551.52},{"time_key":2016071510,"time_name":"2016-07-15 10","register_count":1470,"deal_count":320,"deal_amount":609.50},{"time_key":2016071511,"time_name":"2016-07-15 11","register_count":1156,"deal_count":222,"deal_amount":536.07},{"time_key":2016071512,"time_name":"2016-07-15 12","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071513,"time_name":"2016-07-15 13","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071514,"time_name":"2016-07-15 14","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071515,"time_name":"2016-07-15 15","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071516,"time_name":"2016-07-15 16","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071517,"time_name":"2016-07-15 17","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071518,"time_name":"2016-07-15 18","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071519,"time_name":"2016-07-15 19","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071520,"time_name":"2016-07-15 20","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071521,"time_name":"2016-07-15 21","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071522,"time_name":"2016-07-15 22","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016071523,"time_name":"2016-07-15 23","register_count":0,"deal_count":0,"deal_amount":0.00}]},"history":{"id":5,"values":[]},"today_total":{"id":2,"values":[{"time_key":20160715,"time_name":"2016-07-15","register_count":6215,"deal_count":1172,"deal_amount":2456.85}]},"yesterday2_total":{"id":4,"values":[]}}}';
        echo $r;
    }
    
    public function pageIndexAction() {//首页

//        $id = 10;
        $start = $this->request->get('today');
        $yestoday = date("Ymd",strtotime("$start -1 day"));
        
//        $beforeYestoday = date("Ymd",strtotime("$start -2 day"));
//        $ch = curl_init();
//        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
//                "&today=" . $start . "&yesterday=" . $yestoday . "&yesterday2=" . $beforeYestoday;
//        $data = array(
//            'id' => $id,
//            'today' => $start
//        );
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_exec($ch);
//        $r = curl_multi_getcontent($ch);
//        if (curl_errno($ch)) {
//            echo curl_error($ch);
//        } else {
//            curl_close($ch);
//        }
//        echo $r;
          echo '{"id":10,"configName":"数据报表","configType":"1","details":{"yesterday_total":{"id":3,"values":[{"time_key":20160726,"time_name":"2016-07-26","register_count":113928,"deal_count":13217,"deal_amount":29070.75}]},"today_hour":{"id":1,"values":[{"time_key":2016072700,"time_name":"2016-07-27 00","register_count":3637,"deal_count":48,"deal_amount":40.93},{"time_key":2016072701,"time_name":"2016-07-27 01","register_count":1014,"deal_count":22,"deal_amount":19.06},{"time_key":2016072702,"time_name":"2016-07-27 02","register_count":539,"deal_count":12,"deal_amount":10.89},{"time_key":2016072703,"time_name":"2016-07-27 03","register_count":3794,"deal_count":6,"deal_amount":9.82},{"time_key":2016072704,"time_name":"2016-07-27 04","register_count":318,"deal_count":40,"deal_amount":9.00},{"time_key":2016072705,"time_name":"2016-07-27 05","register_count":486,"deal_count":11,"deal_amount":8.65},{"time_key":2016072706,"time_name":"2016-07-27 06","register_count":1463,"deal_count":37,"deal_amount":33.55},{"time_key":2016072707,"time_name":"2016-07-27 07","register_count":3009,"deal_count":129,"deal_amount":116.22},{"time_key":2016072708,"time_name":"2016-07-27 08","register_count":5575,"deal_count":141,"deal_amount":178.29},{"time_key":2016072709,"time_name":"2016-07-27 09","register_count":7627,"deal_count":210,"deal_amount":314.16},{"time_key":2016072710,"time_name":"2016-07-27 10","register_count":9581,"deal_count":719,"deal_amount":1318.65},{"time_key":2016072711,"time_name":"2016-07-27 11","register_count":11118,"deal_count":562,"deal_amount":959.19},{"time_key":2016072712,"time_name":"2016-07-27 12","register_count":11967,"deal_count":325,"deal_amount":486.57},{"time_key":2016072713,"time_name":"2016-07-27 13","register_count":10780,"deal_count":195,"deal_amount":336.16},{"time_key":2016072714,"time_name":"2016-07-27 14","register_count":9686,"deal_count":1091,"deal_amount":1824.94},{"time_key":2016072715,"time_name":"2016-07-27 15","register_count":9951,"deal_count":529,"deal_amount":771.76},{"time_key":2016072716,"time_name":"2016-07-27 16","register_count":7701,"deal_count":165,"deal_amount":278.58},{"time_key":2016072717,"time_name":"2016-07-27 17","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016072718,"time_name":"2016-07-27 18","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016072719,"time_name":"2016-07-27 19","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016072720,"time_name":"2016-07-27 20","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016072721,"time_name":"2016-07-27 21","register_count":27,"deal_count":23,"deal_amount":3.00},{"time_key":2016072722,"time_name":"2016-07-27 22","register_count":0,"deal_count":0,"deal_amount":0.00},{"time_key":2016072723,"time_name":"2016-07-27 23","register_count":0,"deal_count":0,"deal_amount":0.00}]},"history":{"id":5,"values":[{"time_key":20160726,"time_name":"2016-07-26","register_count":11479408,"deal_count":402499,"deal_amount":1112225.68}]},"today_total":{"id":2,"values":[{"time_key":20160727,"time_name":"2016-07-27","register_count":93968,"deal_count":3923,"deal_amount":6705.00}]},"yesterday2_total":{"id":4,"values":[{"time_key":20160725,"time_name":"2016-07-25","register_count":53494,"deal_count":10711,"deal_amount":23058.43}]},"yesterday_hour":{"id":6,"values":[{"time_key":2016072600,"time_name":"2016-07-26 00","register_count":2591,"deal_count":184,"deal_amount":404.96},{"time_key":2016072601,"time_name":"2016-07-26 01","register_count":734,"deal_count":61,"deal_amount":84.69},{"time_key":2016072602,"time_name":"2016-07-26 02","register_count":400,"deal_count":19,"deal_amount":36.29},{"time_key":2016072603,"time_name":"2016-07-26 03","register_count":282,"deal_count":7,"deal_amount":27.50},{"time_key":2016072604,"time_name":"2016-07-26 04","register_count":219,"deal_count":13,"deal_amount":22.27},{"time_key":2016072605,"time_name":"2016-07-26 05","register_count":350,"deal_count":41,"deal_amount":103.49},{"time_key":2016072606,"time_name":"2016-07-26 06","register_count":1062,"deal_count":145,"deal_amount":306.84},{"time_key":2016072607,"time_name":"2016-07-26 07","register_count":2282,"deal_count":328,"deal_amount":564.61},{"time_key":2016072608,"time_name":"2016-07-26 08","register_count":4162,"deal_count":488,"deal_amount":924.60},{"time_key":2016072609,"time_name":"2016-07-26 09","register_count":5689,"deal_count":682,"deal_amount":1675.12},{"time_key":2016072610,"time_name":"2016-07-26 10","register_count":7193,"deal_count":2062,"deal_amount":4529.08},{"time_key":2016072611,"time_name":"2016-07-26 11","register_count":8061,"deal_count":758,"deal_amount":1604.90},{"time_key":2016072612,"time_name":"2016-07-26 12","register_count":8724,"deal_count":706,"deal_amount":1453.86},{"time_key":2016072613,"time_name":"2016-07-26 13","register_count":7997,"deal_count":860,"deal_amount":1949.24},{"time_key":2016072614,"time_name":"2016-07-26 14","register_count":7296,"deal_count":1637,"deal_amount":3567.72},{"time_key":2016072615,"time_name":"2016-07-26 15","register_count":7386,"deal_count":671,"deal_amount":1423.44},{"time_key":2016072616,"time_name":"2016-07-26 16","register_count":7380,"deal_count":608,"deal_amount":1176.91},{"time_key":2016072617,"time_name":"2016-07-26 17","register_count":6631,"deal_count":522,"deal_amount":987.22},{"time_key":2016072618,"time_name":"2016-07-26 18","register_count":6289,"deal_count":613,"deal_amount":1038.52},{"time_key":2016072619,"time_name":"2016-07-26 19","register_count":6590,"deal_count":594,"deal_amount":877.40},{"time_key":2016072620,"time_name":"2016-07-26 20","register_count":7702,"deal_count":1591,"deal_amount":2948.09},{"time_key":2016072621,"time_name":"2016-07-26 21","register_count":6305,"deal_count":931,"deal_amount":1439.68},{"time_key":2016072622,"time_name":"2016-07-26 22","register_count":4553,"deal_count":739,"deal_amount":1279.60},{"time_key":2016072623,"time_name":"2016-07-26 23","register_count":4050,"deal_count":391,"deal_amount":644.72}]}}}';
          //echo '{"id":10,"configName":"数据报表","configType":"1","details":{"yesterday_total":{"id":3,"values":[{"time_key":20160807,"time_name":"2016-08-07","register_count":85764,"deal_count":1731,"deal_amount":2970.32}]},"today_hour":{"id":1,"values":[]},"history":{"id":5,"values":[{"time_key":20160807,"time_name":"2016-08-07","register_count":13728048,"deal_count":414906,"deal_amount":1196320.55}]},"today_total":{"id":2,"values":[]},"yesterday2_total":{"id":4,"values":[{"time_key":20160806,"time_name":"2016-08-06","register_count":87921,"deal_count":1774,"deal_amount":3091.38}]},"yesterday_hour":{"id":6,"values":[{"time_key":2016080700,"time_name":"2016-08-07 00","register_count":1580,"deal_count":30,"deal_amount":45.51},{"time_key":2016080701,"time_name":"2016-08-07 01","register_count":504,"deal_count":14,"deal_amount":21.85},{"time_key":2016080702,"time_name":"2016-08-07 02","register_count":310,"deal_count":9,"deal_amount":9.04},{"time_key":2016080703,"time_name":"2016-08-07 03","register_count":212,"deal_count":1,"deal_amount":0.05},{"time_key":2016080704,"time_name":"2016-08-07 04","register_count":155,"deal_count":1,"deal_amount":1.00},{"time_key":2016080705,"time_name":"2016-08-07 05","register_count":219,"deal_count":7,"deal_amount":16.70},{"time_key":2016080706,"time_name":"2016-08-07 06","register_count":746,"deal_count":22,"deal_amount":46.69},{"time_key":2016080707,"time_name":"2016-08-07 07","register_count":1663,"deal_count":73,"deal_amount":124.05},{"time_key":2016080708,"time_name":"2016-08-07 08","register_count":3071,"deal_count":101,"deal_amount":144.77},{"time_key":2016080709,"time_name":"2016-08-07 09","register_count":4378,"deal_count":125,"deal_amount":183.38},{"time_key":2016080710,"time_name":"2016-08-07 10","register_count":5550,"deal_count":141,"deal_amount":205.60},{"time_key":2016080711,"time_name":"2016-08-07 11","register_count":6552,"deal_count":141,"deal_amount":301.96},{"time_key":2016080712,"time_name":"2016-08-07 12","register_count":6991,"deal_count":119,"deal_amount":202.53},{"time_key":2016080713,"time_name":"2016-08-07 13","register_count":6302,"deal_count":111,"deal_amount":221.84},{"time_key":2016080714,"time_name":"2016-08-07 14","register_count":5749,"deal_count":97,"deal_amount":166.50},{"time_key":2016080715,"time_name":"2016-08-07 15","register_count":5855,"deal_count":95,"deal_amount":112.90},{"time_key":2016080716,"time_name":"2016-08-07 16","register_count":5806,"deal_count":99,"deal_amount":143.54},{"time_key":2016080717,"time_name":"2016-08-07 17","register_count":5573,"deal_count":89,"deal_amount":132.41},{"time_key":2016080718,"time_name":"2016-08-07 18","register_count":5083,"deal_count":83,"deal_amount":119.68},{"time_key":2016080719,"time_name":"2016-08-07 19","register_count":4829,"deal_count":98,"deal_amount":164.11},{"time_key":2016080720,"time_name":"2016-08-07 20","register_count":4659,"deal_count":101,"deal_amount":188.34},{"time_key":2016080721,"time_name":"2016-08-07 21","register_count":4394,"deal_count":122,"deal_amount":246.73},{"time_key":2016080722,"time_name":"2016-08-07 22","register_count":3250,"deal_count":75,"deal_amount":92.62},{"time_key":2016080723,"time_name":"2016-08-07 23","register_count":2516,"deal_count":48,"deal_amount":78.52}]}}}';    
    }
    //http://10.127.133.91:8080/data/dataservice/getconfig.do?id=13&start_day=20160801&end_day=20160831
    public function monthCoreTargetAction() {//首页

//        $id = 13;
//        $today = $this->request->get('day');
//        $start = date("Ym01",strtotime("$today"));
//        $end = date('Ymd', strtotime("$start +1 month -1 day"));
//        $month_today = date('Ymd', strtotime("$today -1 day"));
//        
//        $ch = curl_init();
//        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
//                "&start_day=" . $start . "&end_day=" . $end . "&month_today=" . $month_today;
//        
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
        echo '{"id":13,"configName":"2.1_月度核心指标_rt_month_kpi","configType":"1","details":{"month":{"id":17,"values":[{"time_key":20160806,"time_name":"2016-08-06","register_nums":689914,"realname_nums":11978,"bind_nums":9504,"new_trans_nums":6702,"new_trans_values":10176.450000,"trans_nums":11417,"trans_values":21232.150000}]},"days":{"id":12,"values":[{"time_key":20160801,"time_name":"2016-08-01","register_nums":130920,"realname_nums":1762,"bind_nums":1287,"new_trans_nums":894,"new_trans_values":1279.110000,"trans_nums":1548,"trans_values":2378.710000},{"time_key":20160802,"time_name":"2016-08-02","register_nums":127298,"realname_nums":2831,"bind_nums":2310,"new_trans_nums":1620,"new_trans_values":1800.580000,"trans_nums":2786,"trans_values":4139.270000},{"time_key":20160803,"time_name":"2016-08-03","register_nums":125428,"realname_nums":1881,"bind_nums":1428,"new_trans_nums":979,"new_trans_values":1348.810000,"trans_nums":1975,"trans_values":3763.320000},{"time_key":20160804,"time_name":"2016-08-04","register_nums":124209,"realname_nums":1824,"bind_nums":1654,"new_trans_nums":1218,"new_trans_values":1369.910000,"trans_nums":2173,"trans_values":3715.360000},{"time_key":20160805,"time_name":"2016-08-05","register_nums":95840,"realname_nums":1898,"bind_nums":1537,"new_trans_nums":1102,"new_trans_values":1547.870000,"trans_nums":2190,"trans_values":4144.110000},{"time_key":20160806,"time_name":"2016-08-06","register_nums":87921,"realname_nums":1782,"bind_nums":1297,"new_trans_nums":889,"new_trans_values":1181.780000,"trans_nums":1774,"trans_values":3091.380000},{"time_key":20160807,"time_name":"2016-08-07","register_nums":2992,"realname_nums":95,"bind_nums":60,"new_trans_nums":31,"new_trans_values":29.850000,"trans_nums":70,"trans_values":115.390000},{"time_key":20160808,"time_name":"2016-08-08","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160809,"time_name":"2016-08-09","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160810,"time_name":"2016-08-10","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160811,"time_name":"2016-08-11","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160812,"time_name":"2016-08-12","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160813,"time_name":"2016-08-13","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160814,"time_name":"2016-08-14","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160815,"time_name":"2016-08-15","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160816,"time_name":"2016-08-16","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160817,"time_name":"2016-08-17","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160818,"time_name":"2016-08-18","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160819,"time_name":"2016-08-19","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160820,"time_name":"2016-08-20","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160821,"time_name":"2016-08-21","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160822,"time_name":"2016-08-22","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160823,"time_name":"2016-08-23","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160824,"time_name":"2016-08-24","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160825,"time_name":"2016-08-25","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160826,"time_name":"2016-08-26","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160827,"time_name":"2016-08-27","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160828,"time_name":"2016-08-28","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160829,"time_name":"2016-08-29","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160830,"time_name":"2016-08-30","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000},{"time_key":20160831,"time_name":"2016-08-31","register_nums":0,"realname_nums":0,"bind_nums":0,"new_trans_nums":0,"new_trans_values":0.000000,"trans_nums":0,"trans_values":0.000000}]}}}';
    }
    
    //http://10.127.133.91:8080/data/dataservice/getconfig.do?id=14&today=20160802
    public function couponUsedAction() {//首页

//        $id = 14;
//        $today = $this->request->get('day');
//        //$today = "20160802";
//        $ch = curl_init();
//        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
//                "&today=" . $today . "&history_day=" . $today;
//        
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
        echo '{"id":14,"configName":"2.2_券使用情况_rt_coupon","configType":"1","details":{"today":{"id":13,"values":[{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"加息券合计","coupon_users":0,"coupon_orders":0,"coupon_order_values":0.000000,"coupon_profit_values":0.00},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"现金券合计","coupon_users":80,"coupon_orders":93,"coupon_order_values":176.390000,"coupon_profit_values":1.04},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减100","coupon_users":6,"coupon_orders":6,"coupon_order_values":6.000000,"coupon_profit_values":0.06},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减200","coupon_users":9,"coupon_orders":9,"coupon_order_values":25.000000,"coupon_profit_values":0.18},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减50","coupon_users":22,"coupon_orders":22,"coupon_order_values":26.000000,"coupon_profit_values":0.11},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1000减50","coupon_users":33,"coupon_orders":33,"coupon_order_values":17.310000,"coupon_profit_values":0.17},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减20","coupon_users":3,"coupon_orders":3,"coupon_order_values":2.400000,"coupon_profit_values":0.01},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减100","coupon_users":1,"coupon_orders":1,"coupon_order_values":3.000000,"coupon_profit_values":0.01},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减150","coupon_users":8,"coupon_orders":8,"coupon_order_values":36.000000,"coupon_profit_values":0.12},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满40000减200","coupon_users":1,"coupon_orders":1,"coupon_order_values":4.000000,"coupon_profit_values":0.02},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减200","coupon_users":1,"coupon_orders":1,"coupon_order_values":5.000000,"coupon_profit_values":0.02},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减300","coupon_users":1,"coupon_orders":1,"coupon_order_values":5.030000,"coupon_profit_values":0.03},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减500","coupon_users":6,"coupon_orders":6,"coupon_order_values":45.050000,"coupon_profit_values":0.30},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满8000减80","coupon_users":2,"coupon_orders":2,"coupon_order_values":1.600000,"coupon_profit_values":0.02}]},"history":{"id":23,"values":[{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"加息券合计","coupon_users":29462,"coupon_orders":35645,"coupon_order_values":70011.460000,"coupon_profit_values":690.33},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--加息券0.2%","coupon_users":211,"coupon_orders":212,"coupon_order_values":857.390000,"coupon_profit_values":1.65},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--加息券0.5%","coupon_users":2933,"coupon_orders":2941,"coupon_order_values":5869.510000,"coupon_profit_values":22.16},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--加息券1.0%","coupon_users":8130,"coupon_orders":8834,"coupon_order_values":21950.470000,"coupon_profit_values":178.05},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--加息券1.5%","coupon_users":16944,"coupon_orders":18971,"coupon_order_values":29946.880000,"coupon_profit_values":341.30},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--加息券2.0%","coupon_users":4614,"coupon_orders":4636,"coupon_order_values":11342.480000,"coupon_profit_values":147.15},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--加息券3.0%","coupon_users":50,"coupon_orders":51,"coupon_order_values":44.730000,"coupon_profit_values":0.02},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"现金券合计","coupon_users":314793,"coupon_orders":600375,"coupon_order_values":755090.990000,"coupon_profit_values":6821.41},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减400","coupon_users":2097,"coupon_orders":2812,"coupon_order_values":14566.940000,"coupon_profit_values":112.48},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减300","coupon_users":2197,"coupon_orders":2271,"coupon_order_values":13955.260000,"coupon_profit_values":68.13},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减250","coupon_users":82,"coupon_orders":155,"coupon_order_values":775.000000,"coupon_profit_values":3.88},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减200","coupon_users":2243,"coupon_orders":2243,"coupon_order_values":12889.080000,"coupon_profit_values":44.86},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减150","coupon_users":1332,"coupon_orders":2024,"coupon_order_values":11029.260000,"coupon_profit_values":30.36},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减100","coupon_users":4622,"coupon_orders":4625,"coupon_order_values":28222.300000,"coupon_profit_values":46.25},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满400减360","coupon_users":2,"coupon_orders":2,"coupon_order_values":0.080000,"coupon_profit_values":0.07},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满400减340","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.040000,"coupon_profit_values":0.03},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满400减320","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.040000,"coupon_profit_values":0.03},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满400减300","coupon_users":107,"coupon_orders":107,"coupon_order_values":45.820000,"coupon_profit_values":3.21},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满400减200","coupon_users":48,"coupon_orders":48,"coupon_order_values":46.480000,"coupon_profit_values":0.96},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满40000减50","coupon_users":2180,"coupon_orders":2181,"coupon_order_values":11819.200000,"coupon_profit_values":10.91},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满40000减200","coupon_users":11304,"coupon_orders":11304,"coupon_order_values":53760.820000,"coupon_profit_values":226.08},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满40000减100","coupon_users":8336,"coupon_orders":8342,"coupon_order_values":44817.010000,"coupon_profit_values":83.42},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满35000减100","coupon_users":84,"coupon_orders":120,"coupon_order_values":647.030000,"coupon_profit_values":1.20},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减5","coupon_users":43,"coupon_orders":43,"coupon_order_values":4.890000,"coupon_profit_values":0.02},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减280","coupon_users":2,"coupon_orders":2,"coupon_order_values":0.160000,"coupon_profit_values":0.06},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减260","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.030000,"coupon_profit_values":0.03},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减250","coupon_users":4,"coupon_orders":4,"coupon_order_values":0.330000,"coupon_profit_values":0.10},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100000减500","coupon_users":1740,"coupon_orders":1740,"coupon_order_values":18820.630000,"coupon_profit_values":87.00},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减100","coupon_users":48349,"coupon_orders":52967,"coupon_order_values":68228.630000,"coupon_profit_values":529.67},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满9900减9800","coupon_users":1,"coupon_orders":1,"coupon_order_values":1.000000,"coupon_profit_values":0.98},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满9600减9550","coupon_users":1,"coupon_orders":1,"coupon_order_values":1.000000,"coupon_profit_values":0.96},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满900减880","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.090000,"coupon_profit_values":0.09},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满800减700","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.080000,"coupon_profit_values":0.07},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满8000减80","coupon_users":2302,"coupon_orders":2302,"coupon_order_values":2393.880000,"coupon_profit_values":18.42},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满7500减7480","coupon_users":1,"coupon_orders":1,"coupon_order_values":1.000000,"coupon_profit_values":0.75},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满700减650","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.070000,"coupon_profit_values":0.07},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满600减500","coupon_users":129,"coupon_orders":129,"coupon_order_values":51.970000,"coupon_profit_values":6.45},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满600减300","coupon_users":59,"coupon_orders":59,"coupon_order_values":23.030000,"coupon_profit_values":1.77},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满500减460","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.050000,"coupon_profit_values":0.05},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满500减400","coupon_users":133,"coupon_orders":133,"coupon_order_values":55.840000,"coupon_profit_values":5.32},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满5000减80","coupon_users":3308,"coupon_orders":3308,"coupon_order_values":2105.610000,"coupon_profit_values":26.46},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满5000减50","coupon_users":30280,"coupon_orders":30604,"coupon_order_values":27384.590000,"coupon_profit_values":153.02},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满5000减150","coupon_users":2103,"coupon_orders":2103,"coupon_order_values":1509.820000,"coupon_profit_values":31.55},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满5000减100","coupon_users":1699,"coupon_orders":1724,"coupon_order_values":1675.190000,"coupon_profit_values":17.24},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减800","coupon_users":8340,"coupon_orders":10895,"coupon_order_values":55451.180000,"coupon_profit_values":871.60},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满50000减500","coupon_users":8467,"coupon_orders":16120,"coupon_order_values":83456.710000,"coupon_profit_values":806.00},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满15000减150","coupon_users":2620,"coupon_orders":2812,"coupon_order_values":5804.450000,"coupon_profit_values":42.18},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1100减1050","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.110000,"coupon_profit_values":0.11},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减80","coupon_users":107,"coupon_orders":107,"coupon_order_values":14.030000,"coupon_profit_values":0.86},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减70","coupon_users":71,"coupon_orders":71,"coupon_order_values":2.960000,"coupon_profit_values":0.50},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减60","coupon_users":69,"coupon_orders":69,"coupon_order_values":4.340000,"coupon_profit_values":0.41},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减50","coupon_users":363,"coupon_orders":363,"coupon_order_values":20.510000,"coupon_profit_values":1.82},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减40","coupon_users":98,"coupon_orders":98,"coupon_order_values":8.760000,"coupon_profit_values":0.39},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满100减20","coupon_users":261856,"coupon_orders":262802,"coupon_order_values":67056.270000,"coupon_profit_values":525.60},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1000减950","coupon_users":2,"coupon_orders":2,"coupon_order_values":0.200000,"coupon_profit_values":0.19},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1000减900","coupon_users":43,"coupon_orders":43,"coupon_order_values":30.800000,"coupon_profit_values":3.87},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1000减50","coupon_users":3855,"coupon_orders":3859,"coupon_order_values":3926.250000,"coupon_profit_values":19.30},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1000减20","coupon_users":1113,"coupon_orders":1316,"coupon_order_values":561.250000,"coupon_profit_values":2.63},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减80","coupon_users":190,"coupon_orders":190,"coupon_order_values":246.660000,"coupon_profit_values":1.52},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减50","coupon_users":782,"coupon_orders":782,"coupon_order_values":967.470000,"coupon_profit_values":3.91},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减300","coupon_users":39178,"coupon_orders":61862,"coupon_order_values":63701.390000,"coupon_profit_values":1855.86},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减30","coupon_users":15257,"coupon_orders":15278,"coupon_order_values":19018.120000,"coupon_profit_values":45.83},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减200","coupon_users":8785,"coupon_orders":10988,"coupon_order_values":14346.870000,"coupon_profit_values":219.76},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减20","coupon_users":1418,"coupon_orders":1465,"coupon_order_values":3180.490000,"coupon_profit_values":2.93},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满10000减150","coupon_users":16324,"coupon_orders":19321,"coupon_order_values":21440.840000,"coupon_profit_values":289.82},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减240","coupon_users":2,"coupon_orders":2,"coupon_order_values":0.060000,"coupon_profit_values":0.05},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减220","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.030000,"coupon_profit_values":0.02},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满300减200","coupon_users":4,"coupon_orders":4,"coupon_order_values":0.120000,"coupon_profit_values":0.08},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满3000减50","coupon_users":2706,"coupon_orders":2706,"coupon_order_values":984.080000,"coupon_profit_values":13.53},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满3000减30","coupon_users":21920,"coupon_orders":21920,"coupon_order_values":11692.060000,"coupon_profit_values":65.76},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满2800减2700","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.280000,"coupon_profit_values":0.27},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满25000减50","coupon_users":47,"coupon_orders":59,"coupon_order_values":254.000000,"coupon_profit_values":0.30},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满200减180","coupon_users":3,"coupon_orders":3,"coupon_order_values":0.060000,"coupon_profit_values":0.05},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满200减160","coupon_users":8,"coupon_orders":8,"coupon_order_values":0.160000,"coupon_profit_values":0.13},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满200减150","coupon_users":7,"coupon_orders":7,"coupon_order_values":0.300000,"coupon_profit_values":0.11},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满200减140","coupon_users":5,"coupon_orders":5,"coupon_order_values":1.080000,"coupon_profit_values":0.07},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满200减120","coupon_users":17,"coupon_orders":17,"coupon_order_values":1.400000,"coupon_profit_values":0.20},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满200减100","coupon_users":60,"coupon_orders":60,"coupon_order_values":29.670000,"coupon_profit_values":0.60},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减50","coupon_users":9311,"coupon_orders":9318,"coupon_order_values":23733.940000,"coupon_profit_values":46.59},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减300","coupon_users":9069,"coupon_orders":9608,"coupon_order_values":22791.860000,"coupon_profit_values":288.24},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减200","coupon_users":2803,"coupon_orders":2870,"coupon_order_values":6369.150000,"coupon_profit_values":57.40},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减150","coupon_users":180,"coupon_orders":180,"coupon_order_values":749.560000,"coupon_profit_values":2.70},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满20000减100","coupon_users":13798,"coupon_orders":13798,"coupon_order_values":34410.920000,"coupon_profit_values":137.98},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1800减1750","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.180000,"coupon_profit_values":0.18},{"time_key":20160807,"time_name":"2016-08-07","coupon_name":"--满1700减1600","coupon_users":1,"coupon_orders":1,"coupon_order_values":0.170000,"coupon_profit_values":0.16}]}}}';
    }
    //http://10.127.133.91:8080/data/dataservice/getconfig.do?id=15&start_day=20160801&end_day=20160803&month_day=20160802
    public function dayCashSumAction() {//首页

//        $id = 15;
//        $today = $this->request->get('day');
//        $recieveMonth = date("n",strtotime("$today"));
//        $nowMonth = date("n");
//        
//        if($recieveMonth == $nowMonth){
//            $start = date("Ym01",strtotime("$today"));
//            $end = date('Ymd', strtotime("$today"));
//            $month_day = $end;
//        }else if($recieveMonth < $nowMonth){
//            $start = date("Ym01",strtotime("$today"));
//            $end = date('Ymd', strtotime("$start +1 month -1 day"));
//            $month_day = $end;
//        }  else {
//            echo "";
//            return;
//        }
//        
//        $ch = curl_init();
//        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
//                "&start_day=" . $start . "&end_day=" . $end . "&month_day=" . $month_day;
//        //echo $url;
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
    
        echo '{"id":15,"configName":"2.3_每日提现金额_rt_withdraw_cash","configType":"1","details":{"total":{"id":16,"values":[{"time_key":20160731,"time_name":"2016-07-31","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00}]},"month":{"id":15,"values":[{"time_key":20160731,"time_name":"2016-07-31","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00}]},"today":{"id":14,"values":[{"time_key":20160701,"time_name":"2016-07-01","day_withdraw_times":3,"day_withdraw_nums":3,"day_withdraw_values":0.700000,"day_withdraw_profit":0.02},{"time_key":20160702,"time_name":"2016-07-02","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160703,"time_name":"2016-07-03","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160704,"time_name":"2016-07-04","day_withdraw_times":2,"day_withdraw_nums":2,"day_withdraw_values":2.000000,"day_withdraw_profit":0.04},{"time_key":20160705,"time_name":"2016-07-05","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160706,"time_name":"2016-07-06","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160707,"time_name":"2016-07-07","day_withdraw_times":2,"day_withdraw_nums":2,"day_withdraw_values":2.200000,"day_withdraw_profit":0.04},{"time_key":20160708,"time_name":"2016-07-08","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160709,"time_name":"2016-07-09","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160710,"time_name":"2016-07-10","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160711,"time_name":"2016-07-11","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160712,"time_name":"2016-07-12","day_withdraw_times":2,"day_withdraw_nums":2,"day_withdraw_values":1.010000,"day_withdraw_profit":0.02},{"time_key":20160713,"time_name":"2016-07-13","day_withdraw_times":3,"day_withdraw_nums":3,"day_withdraw_values":0.710000,"day_withdraw_profit":0.02},{"time_key":20160714,"time_name":"2016-07-14","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160715,"time_name":"2016-07-15","day_withdraw_times":24255,"day_withdraw_nums":24238,"day_withdraw_values":8831.400000,"day_withdraw_profit":219.04},{"time_key":20160716,"time_name":"2016-07-16","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160717,"time_name":"2016-07-17","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160718,"time_name":"2016-07-18","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160719,"time_name":"2016-07-19","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160720,"time_name":"2016-07-20","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160721,"time_name":"2016-07-21","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160722,"time_name":"2016-07-22","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160723,"time_name":"2016-07-23","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160724,"time_name":"2016-07-24","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160725,"time_name":"2016-07-25","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160726,"time_name":"2016-07-26","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160727,"time_name":"2016-07-27","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160728,"time_name":"2016-07-28","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160729,"time_name":"2016-07-29","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160730,"time_name":"2016-07-30","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00},{"time_key":20160731,"time_name":"2016-07-31","day_withdraw_times":0,"day_withdraw_nums":0,"day_withdraw_values":0.000000,"day_withdraw_profit":0.00}]}}}';
    }
    
    public function channelRegisDealAction() {//首页

        $id = 16;
        $today = $this->request->get('day');
        //$today = "20160802";
        $ch = curl_init();
        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
                "&today=" . $today . "&history_day=" . $today;
        
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
    
    public function subChannelRegisDealAction() {//首页

        $id = 17;
        $today = $this->request->get('day');
        //$today = "20160802";
        $ch = curl_init();
        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
                "&today=" . $today . "&history_day=" . $today;
        
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
    
    public function dealRangesAction() {

        $id = 18;
        $today = $this->request->get('day');
        //$today = "20160802";
        $ch = curl_init();
        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
                "&today=" . $today . "&history_day=" . $today;
        
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
    
    public function proDealSumAction() {

        $id = 19;
        $today = $this->request->get('day');
        //$today = "20160802";
        $ch = curl_init();
        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
                "&today=" . $today . "&history_day=" . $today;
        
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
    
    public function proTimeAnalyAction() {//首页

//        $id = 20;
//        $today = $this->request->get('day');
//        //$today = "20160802";
//        $ch = curl_init();
//        $url = "http://10.127.133.91:8080/data/dataservice/getconfig.do?id=".$id.
//                "&today=" . $today . "&history_day=" . $today;
//        
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
        echo '{"id":20,"configName":"4.3_产品期限分析_rt_product_term_sales","configType":"1","details":{"today":{"id":22,"values":[{"time_key":20160807,"time_name":"2016-08-07","hour_type":"22-23时","new_user_values":27.05,"m_3_values":0.000000,"m_6_values":29.030000,"m_9_values":0.000000,"m_12_values":36.540000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"21-22时","new_user_values":41.71,"m_3_values":0.000000,"m_6_values":60.810000,"m_9_values":0.000000,"m_12_values":144.210000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"20-21时","new_user_values":37.74,"m_3_values":0.000000,"m_6_values":66.150000,"m_9_values":0.000000,"m_12_values":84.450000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"19-20时","new_user_values":36.36,"m_3_values":0.000000,"m_6_values":33.140000,"m_9_values":0.000000,"m_12_values":94.610000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"18-19时","new_user_values":28.45,"m_3_values":0.000000,"m_6_values":34.380000,"m_9_values":0.000000,"m_12_values":56.850000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"17-18时","new_user_values":30.59,"m_3_values":0.000000,"m_6_values":28.590000,"m_9_values":0.000000,"m_12_values":73.230000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"16-17时","new_user_values":17.99,"m_3_values":0.000000,"m_6_values":58.990000,"m_9_values":0.000000,"m_12_values":66.560000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"15-16时","new_user_values":25.81,"m_3_values":0.000000,"m_6_values":20.390000,"m_9_values":0.000000,"m_12_values":66.700000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"14-15时","new_user_values":30.95,"m_3_values":0.000000,"m_6_values":44.570000,"m_9_values":0.000000,"m_12_values":90.980000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"13-14时","new_user_values":32.17,"m_3_values":0.000000,"m_6_values":49.970000,"m_9_values":0.000000,"m_12_values":139.700000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"12-13时","new_user_values":36.03,"m_3_values":0.000000,"m_6_values":57.570000,"m_9_values":0.000000,"m_12_values":108.930000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"11-12时","new_user_values":51.17,"m_3_values":0.000000,"m_6_values":124.570000,"m_9_values":0.000000,"m_12_values":126.220000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"10-11时","new_user_values":49.65,"m_3_values":0.000000,"m_6_values":43.710000,"m_9_values":0.000000,"m_12_values":112.240000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"9-10时","new_user_values":41.52,"m_3_values":0.000000,"m_6_values":36.560000,"m_9_values":0.000000,"m_12_values":105.300000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"8-9时","new_user_values":18.49,"m_3_values":0.000000,"m_6_values":79.110000,"m_9_values":0.000000,"m_12_values":47.170000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"7-8时","new_user_values":14.72,"m_3_values":0.000000,"m_6_values":27.000000,"m_9_values":0.000000,"m_12_values":82.330000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"6-7时","new_user_values":3.01,"m_3_values":0.000000,"m_6_values":22.040000,"m_9_values":0.000000,"m_12_values":21.640000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"5-6时","new_user_values":0.60,"m_3_values":0.000000,"m_6_values":1.000000,"m_9_values":0.000000,"m_12_values":15.100000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"4-5时","new_user_values":1.00,"m_3_values":0.000000,"m_6_values":0.000000,"m_9_values":0.000000,"m_12_values":0.000000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"3-4时","new_user_values":0.05,"m_3_values":0.000000,"m_6_values":0.000000,"m_9_values":0.000000,"m_12_values":0.000000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"2-3时","new_user_values":0.42,"m_3_values":0.000000,"m_6_values":3.000000,"m_9_values":0.000000,"m_12_values":5.620000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"1-2时","new_user_values":5.35,"m_3_values":0.000000,"m_6_values":3.100000,"m_9_values":0.000000,"m_12_values":13.400000,"m_18_values":0.000000,"m_24_values":0.000000},{"time_key":20160807,"time_name":"2016-08-07","hour_type":"0-1时","new_user_values":6.81,"m_3_values":0.000000,"m_6_values":17.290000,"m_9_values":0.000000,"m_12_values":21.410000,"m_18_values":0.000000,"m_24_values":0.000000}]},"history":{"id":28,"values":[{"time_key":20160807,"time_name":"2016-08-07","hour_type":null,"new_user_values":0.00,"m_3_values":0.000000,"m_6_values":0.000000,"m_9_values":0.000000,"m_12_values":0.000000,"m_18_values":0.000000,"m_24_values":0.000000}]}}}';
    }
    
    public function aAction(){
        $d = "20160731";
        //echo date($d,strtotime("-1 day"));
        //echo date('Ymd',strtotime('+3 d',strtotime('20160726')));//日期天数相加函数
        //echo   date("Ymd",strtotime("$d -1 day"));
        
        $recieveMonth = date("n",strtotime($d));
        $nowMonth = date("n");
        
        $today = "20160803";
        $recieveMonth = date("n",strtotime("$today"));
        $nowMonth = date("n");
        
        if($recieveMonth == $nowMonth){
            $start = date("Ym01",strtotime("$today"));
            $end = date('Ymd', strtotime("$today"));
            $month_today = $end;
        }else if($recieveMonth < $nowMonth){
            $start = date("Ym01",strtotime("$today"));
            $end = date('Ymd', strtotime("$start +1 month -1 day"));
            $month_today = $end;
        }  else {
            echo "";
            return;
        }
        echo $start."#".$end."#".$month_today;
    }

}
