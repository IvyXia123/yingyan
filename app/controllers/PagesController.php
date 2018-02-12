<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eye\Controllers;

use Phalcon\Mvc\Controller;

//use Utils\PHPExcel;

class PagesController extends Controller {

    public function getAction() {
        $page = $this->dispatcher->getParam("page");
        if ($page) {
            $this->view->pick("pages/" . $page);
        }
    }

    public function testAction() {
        require_once '/Users/zhaopeng/projects/eye/app/utils/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();
        echo dirname(__FILE__);
//        $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', '序号')
//                ->setCellValue('B1', '支持工程师')
//                ->setCellValue('C1', '工单创建日期')
//                ->setCellValue('D1', '工单创建时间')
//                ->setCellValue('E1', '服务项目')
//                ->setCellValue('F1', '客户姓名')
//                ->setCellValue('G1', '客户电话')
//                ->setCellValue('H1', '工单简概')
//                ->setCellValue('I1', '工单状态');
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0, 1, "序号");

//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment;filename="OTRS.xls"');
//        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('/Users/zhaopeng/projects/output.xls');
    }

    public function test2Action() {
        $fp = fopen('/Users/zhaopeng/projects/aa/output.csv', 'w') or die("open file failure!");
        for ($i = 0; $i < 300000; $i++) {
            fwrite($fp, "aaa$i\tbbb\tccc\t18763266565 \n");
        }
        fclose($fp);
        echo __DIR__;
        //echo __FILE__;
        //Users/zhaopeng/projects/eye/app/controllers
    }

    public function test3Action() {
        require_once '/Users/zhaopeng/projects/eye/app/utils/PHPExcel.php';

        $fp = fopen('/Users/zhaopeng/projects/output.txt', 'r') or die("open file failure!");
        $lines = 1;
        $files = 1;
        $objPHPExcel = new \PHPExcel();
        while ($line = stream_get_line($fp, 1024, "\n")) {
            $arr = explode("\t", $line);
            for ($i = 0; $i < count($arr); $i++) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow($i, $lines, $arr[$i]);
            }
            if ($lines % 10000 == 0) {
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save("/Users/zhaopeng/projects/output-$files.xls");
                $lines = 1;
                $files++;
                $objWriter = null;
                $objPHPExcel = null;
                $objPHPExcel = new \PHPExcel();
            } else
                $lines++;
        }

        if ($lines > 1) {
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save("/Users/zhaopeng/projects/output-$files.xls");
        }

        echo $lines;
        fclose($fp);
    }

}
