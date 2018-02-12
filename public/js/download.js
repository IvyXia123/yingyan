
function download() {
    $.ajax({
        type: "post",
        async: true,
        url: "/get/downStatus",
        success: function (msg) {
            msg = JSON.parse(msg);
            //console.debug(msg);
            for (var i = 0; i < msg.length; i++) {
                if (msg[i].status === "正在导出数据") {
                    //$('<li class="entry"><span class="entry_content">' + msg[i].down_name + '</span></li>').appendTo("#download");
                } else {
                    //msg[i].file_path
                    var a = $("<a></a>").appendTo($("body"));
                    a.attr("href", msg[i].file_path);
                    a.attr("download", msg[i].down_name);
                    a.appendTo($("body"));
                    a.css("display", "none");
                    $("<span>AAAAAAA</span>").appendTo(a).trigger("click");
                    console.debug(a);
                    $.ajax({
                      type: "post",
                      async: true,
                      timeout : 10000,
                      url: "/task/downloadCount",
                      data:{id:msg[i].id}
                    });
                    
                    window.setTimeout(function () {
                        a.remove();
                    }, 3000);
                }
            }
        }
    });
}

function downloadAction() {
	$('.u-export ul').animate({"width": "0px"}, 200).children().hide();
    setTimeout(function(){
    	$('.u-export').hide();
    	},210)
    var a = [];
    $('input:checkbox').each(function () {
        if ($(this).is(':checked')) {
            a.push($(this).val());
        }
    });
    var downType = $("input:radio[name=downType]:checked").val();
    $.ajax({
        url: "/task/download",
        async: true,
        type: 'POST',
        data: {id: $('.u-export ul').attr('itemName'), fields: a.join(","), downType:downType},
        success: function (text, textStatus) {
//            try {
//                console.debug(text);
//                var o = JSON.parse(text);
//                console.debug(o);
//                if (o.code === 0) {
//
//                } else {
//                    console.debug(o.message);
//                }
//            } catch (e) {
//                console.error(e);
//            }
        }
    });
}

window.setInterval(download, 5000);

function download3() {
    $.ajax({
        type: "get",
        async: true,
        url: "/get/downStatus",
        success: function (msg) {
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if (msg[i].status === "正在导出数据") {
                    //$('<li class="entry"><span class="entry_content">' + msg[i].down_name + '</span></li>').appendTo("#download");
                } else {
                    //msg[i].file_path
                    var down = $('<li class="entry"><span class="entry_content">' + msg[i].down_name + '</span></li>').appendTo("#download");
                    down.click({filePath: msg[i].file_path, fileName: msg[i].down_name}, function (e) {
                        var a = $("<a></a>").appendTo($("body"));
                        a.attr("href", e.data.filePath);
                        a.attr("download", e.data.fileName);
                        a.appendTo($("body"));
                        a.css("display", "none");
                        $("<span>AAAAAAA</span>").appendTo(a).trigger("click");
                        window.setTimeout(function () {
                            a.remove();
                        }, 3000);
                    });
                }
            }
        }
    });
}