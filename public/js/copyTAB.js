var idTmr;
function  getExplorer() {
    // 判断浏览器类型及版本
    var explorer = window.navigator.userAgent;
    //ie 
    if (explorer.indexOf("MSIE") >= 0) {
        return 'ie';
    }
    //firefox 
    else if (explorer.indexOf("Firefox") >= 0) {
        return 'Firefox';
    }
    //Chrome
    else if (explorer.indexOf("Chrome") >= 0) {
        return 'Chrome';
    }
    //Opera
    else if (explorer.indexOf("Opera") >= 0) {
        return 'Opera';
    }
    //Safari
    else if (explorer.indexOf("Safari") >= 0) {
        return 'Safari';
    }
}
function method1(tableid) {//整个表格拷贝到EXCEL中
    if (getExplorer() == 'ie')
    {
        var curTbl = document.getElementById(tableid);
        var oXL = new ActiveXObject("Excel.Application");

        //创建AX对象excel 
        var oWB = oXL.Workbooks.Add();
        //获取workbook对象 
        var xlsheet = oWB.Worksheets(1);
        //激活当前sheet 
        var sel = document.body.createTextRange();
        sel.moveToElementText(curTbl);
        //把表格中的内容移到TextRange中 
        sel.select;
        //全选TextRange中内容 
        sel.execCommand("Copy");
        //复制TextRange中内容  
        xlsheet.Paste();
        //粘贴到活动的EXCEL中       
        oXL.Visible = true;
        //设置excel可见属性

        try {
            var fname = oXL.Application.GetSaveAsFilename("Excel.xls", "Excel Spreadsheets (*.xls), *.xls");
        } catch (e) {
            print("Nested catch caught " + e);
        } finally {
            oWB.SaveAs(fname);

            oWB.Close(savechanges = false);
            //xls.visible = false;
            oXL.Quit();
            oXL = null;
            //结束excel进程，退出完成
            //window.setInterval("Cleanup();",1);
            idTmr = window.setInterval("Cleanup();", 1);

        }

    } else
    {
        tableToExcel(event.target.className);
    }
}
function Cleanup() {
    window.clearInterval(idTmr);
    CollectGarbage();
}
var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,',
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64 = function (s) {
                var www = window.btoa(unescape(encodeURIComponent(s)));
                return  www;
            },
            format = function (s, c) {
                return s.replace(/{(\w+)}/g,
                        function (m, p) {
                            return c[p];
                        })
            };
    return function (table, name) {
    	var idName = $('#'+table).parents('#activity-Particulars').find('#_id').html();
        if (!table.nodeType)
            var table = $('#' + table).clone();
        table.find('tr').css('width', 'auto');
        var str = table.innerHTML;
        var ctx = {worksheet: name || 'Worksheet', table: table.html()};
        //window.open(uri + base64(format(template, ctx)))
        var a = $("<a></a>");
        var s = $("<span></span>");
        s.appendTo(a);
        a.appendTo($("body"));
        a.hide();
        a.attr("href", uri + base64(format(template, ctx)));
        a.attr("download", idName+".xls");
        s.trigger("click");
    }
})()
