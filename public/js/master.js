/* 修改于：
 * 2016-12-16
 */
/*===========================================       全局变量区         =================================================*/
var detailsObj;
var user = $.cookie('userName');//user；
var activityList = [];      // 当前活动所有参数；
var getActivityList = [];  //指定活动所有参数；
var copyId;    //复制活动参数；
var elicit;
var dataUser = $.cookie('US_SESSION_ID');
/* 首页分段加载全局变量
 *
 * */
// 以获取数据条目数
var requestCount=0;
// 当前TAB 高度
var contentH;
// 当前数据请求状态
var condition =false;
var off=true;
/*===========================================        执行区         	================================================*/
(function ($) {
    // 页面主体内容高度
    $('#g-container').css('height',$('body').height()-143);
    // 用户名
    $('#user_name').html($.cookie('userName'));
    setTimeout(function () {
        $('html,body').css("opacity", 1);
    }, 200)
})(jQuery)
$(function () {
    getActivity();// 初始化数据
	exportUnit(); // 导出操作
    downloads();  // 下载页
    navSwitch();  // 导航栏页面切换操作
    hoverSearch();// 搜索框样式
    //未开放功能提示牌
    $('.warning_btn').click(function () {
        $(this).parents('.warning_box').hide();
    });
});
$(window).resize(function(){
	setTimeout(function(){
		var headerH = $('.container_header').outerHeight();
		$('#g-container').css('height',$('body').height() - headerH);
		sizeTab();
	})
});

/*===========================================    	  方法区         	================================================*/

function getActivity() {
	var url=String(document.location);
	if(condition == true){
		return
	}else{
		condition = true;
	}
 	// 刷新跳转详情页  & 刷新主页面
	if($.trim(String(document.location).split('#')[1]).length >0 ){
    	$(window).ready(function(){
    		var id=url.split('#')[1];
    		$('body').hide();
    		refreshSkip(id);
    	})
    }else{
    	$.ajax({
	        url: "/get/promsHistoryLimit2?",
	        async: true,
	        type: 'get',   
	        data:{limit:requestCount},
	        success: function (text, textStatus) {
	            var msg = jQuery.parseJSON(text);
	            for (var i = 0; i < msg.length; i++) {
	                if (!msg[i].count)
	                	msg[i].count = 0;
	            		activityList.push(msg[i]);
	            		addTab(msg[i])
	            }
	           $('#index_content').find('.table-head').show();
	            sizeTab();
	            requestCount=$('#overviewActivity').find('tr').length;
	        	contentH=$('#overviewActivity').height();
	        	condition = false;
	        }
	    });
    
    }
}

//  刷新跳转到 URL 指定页面
 function refreshSkip(id){
 	var u = {};
 		u.id = id;
 		u.page = String(document.location).split('#')[0]+'#'+u.id;
 	if(u.id.length > 10){
 		u.URL  =  "/pages/get/"+u.id;
 		u.dom  = u.id;
 	}else{
 		u.URL  =  "/pages/get/activity-Particulars";
 		u.dom  = 'activity-Particulars';
 	}
 	$.ajax({
        url: "/get/onePromsHistory",
        async: true,
        type: 'POST',
        data:{
        	id:u.id
        },
        success: function (text, status) {
        	// 获取当前活动头部信息
			getActivityList = JSON.parse(text);
			// 加载当前页面
			$.ajax({
		        url: u.URL,
		        async: true,
		        type: 'POST',
		        success: function (text, status) {
		        	document.location = u.page;
		            var dom=u.dom;
		            $("#" + dom).html(text);
		            var pageName = $("#" + dom).attr('date-page');	//加载页面 title 名字
				    $("#" + dom).addClass('container_show').siblings('section').removeClass('container_show');
				    $('#pageTitle').attr('date-page','index_content').html(pageName);
		        	condition = false;
		        	$('body').show();
		        }
		    });
        }
    });
 }


// tab 加载
 function addTab(data){
 	var tr=$('<tr></tr>');
 	var html='<td>' + data.id + '</td>' +
             '<td><i class="activityName sizeWidth item-name pointer" date-page="activity-Particulars"'+
             ' onclick="addDelete(this)">'+ data.name + '</i></td>' +
             '<td><span>' + data.create_time + '</span></td>' +
             '<td>' + data.task_status + '</td>' +
             '<td><span>' + data.last_run_time + '</span></td>' +
             '<td><span>' + data.count + '</span></td>' +
             '<td><span>' + data.submitter + '</span></td>';
    tr.append(html);
 	if(data.task_status !== '等待执行'){
 		var last_td='<td><i class="pointer" onclick="addDelete(this)" date-page="activity-creates">'+
                    '复制</i> / <i class="pointer" onclick="addDelete(this)">删除</i> / <i class="pointer" onclick="addDelete(this)">下载</i></td>';
 	}else{
 		var last_td='<td><i class="pointer" onclick="addDelete(this)" date-page="activity-creates">'+
                    '复制</i> / <i class="pointer" onclick="addDelete(this)">取消</i> / <i class="pointer" onclick="addDelete(this)">下载</i></td>';
 	}
 	tr.append(last_td);
 	$('#overviewActivity').append(tr);
 }
 
/*===========================================    	  功能区         	================================================*/
	
// 分页加载
	// 页面不存在滚动条的情况下，以鼠标滚轮模拟
	$(window).bind('mousewheel',function(){
		if($('#index_content').is(':visible') && $.trim(String(document.location).split('#')[1]).length < 1)
		var scrollFuncSize=scrollFunc();
		if(scrollFuncSize > 0 && $('#overviewActivity').parent().height() > $('#overviewActivity').height())
			getActivity();
			
	})
	// tab 存在滚动条的情况下
	$('#overviewActivity').parent().scroll(function(){
		if($('#index_content').is(':visible') && $.trim(String(document.location).split('#')[1]).length < 1)
		var $this=$(this),
			viewH=$(this).height(),
			scrollTop=$(this).scrollTop();
			var size = contentH-viewH-scrollTop;
                        if(size  < 1)getActivity();
                          
	})

	// 鼠标滚动
	function scrollFunc(e){ 
	    e=e || window.event; 
	    if(e.wheelDelta){//IE/Opera/Chrome 
	    	// IE 向下滚动为负数
	    	var e=(e.wheelDelta)-(e.wheelDelta)*2
			return e 
	    }else if(e.detail){//Firefox
	        return e.detail;
	    }
	}

	// 点击活动name执行操作
	// copy || remove || creation || deletes
	function addDelete(e) {
	    var optName = $(e).parents('tr').children(':first').html();
	    var thisText=$(e).html();
	    for (i = 0; i < activityList.length; i++) {
	        if (activityList[i].id == optName) {
	        	switch (thisText) {
	        		case "删除":
		        		popTip(thisText);
						$(".md-close").click(function(){ 
							removeTable(e);
					        $(".md-close").unbind("click");
					   	});	
					    Myclick();
					    break;
				    case "取消":
				    	popTip(thisText);
		            	$(".md-close").click(function(){  
							deletes(e,optName);
					        $(".md-close").unbind("click");
					   	});	
					    Myclick();
	                	break;
                	case "复制":
                		copyId = activityList[i].id;
	                	switchoverPage(e);
					    break;
				    case "下载":
	                	listExport(e);
					    break;
				    default:
					    if(!jumpDetailsPage(e)){
		            		return false;
		            	}else{
		            		jumpDetailsPage(e)
		            	}
	        	}
	        }
	    }
	}
	
	// Delete data prompt
	function deletes(e,ststus){
		$.ajax({
	        url: "/get/cancelProm",
	        async: true,
	        type: 'POST',
	        data: {prom_id: ststus},
	        success: function (text, textStatus) {
	            try {
	                if(text === "1"){
	                    $(e).parents("tr").children(":nth-child(4)").html("已取消");
	                    $(e).html("删除");
	                }
	            } catch (e) {
	
	            }
	        }
	    });
	}
	
	// jump details page (only details)
	 function jumpDetailsPage(e){
	 	var $this=$(e);
	 	var id=$this.parents('tr').children(':first').html();
	 	refreshSkip(id)
 		changeURL(id)
 		return false;
	}	
	 
	// click nav node switchover page
	function switchoverPage(e) {
		var name = $(e).attr('date-page');       		// 要加载页面
		var title = $("#" + name).attr('date-page');	//要加载页面的 标题
		var id=$('#g-container').children('section:not(:hidden)').attr('id');
		$('#'+id).empty();
		$('#pageTitle').html(title);
		$("#" + name).addClass('container_show').siblings('section').removeClass('container_show');
	    if ($('#' + name).find('table').length < 1) {
	    	// 判断页面存在与否
	        $('#' + name).load("/pages/get/" + name, sizeTableHeight());          
	    } 
	    // 根据子元素长度判断页面级别
	   	if($('#pageTitle').children().length < 1){
	   		$('#pageTitle').attr('date-page',name);
	   	}
	    clearInterval(elicit);
	}
	
	 // 重新拼接新的 URL，并删除创建新的导航栏；
	function changeURL(url){
	    var l = String(document.location);
	    l = l.split("#")[0];
	    if(url < 0 || !url){
	    	// 当前条件是为了返回首页
	    	document.location = l;
	    }else{
	    	document.location = l + "#" + url;
	    } 
	}
	
	/// click page title Before returning the page
	function returnPage(e){
		clearInterval(elicit);
		// 页面切换前操作；
		var Node=$('#g-container').children('section:not(:hidden)');// 获取当前正在显示模块的 页面名 及 ID
		var Node_id=Node.attr('id');
		if(Node.hasClass('san')){
			var name = $('#pageTitle').attr('date-page')
			// 页面切换成功操作
			$("#" + name).addClass('container_show').siblings('section').removeClass('container_show');
		}else{
			var name = $(e).parent().attr('date-page');					// 获取当前对象对象存储的要切换页面的名字;
			// 页面切换成功操作
			$("#" + name).addClass('container_show').siblings('section').removeClass('container_show');
			$('#pageTitle').html($("#" + name).attr('date-page')).attr('date-page',Node_id);		// 获取要切换页面的 标题 ，页面切换成功，更换标题栏;
		}
		$('#'+Node_id).empty();									// 防止重复加载数据，清除当前跳转前页面的顶层盒子内的所有数据;
		if($.trim(String(document.location).split('#')[1]).length >= 0 ){
			document.location =String(document.location).split('#')[0]; // 改变URL，防止刷新后页面返回上次操作;
		}
		if(name == 'index_content'){
			getActivity()
		}
	}
	
	//  删除活动，并隐藏当前节点；
	function removeTable(event) {
	    var indexId = $(event).parents('tr').children('td').eq(0).html();
	    $.ajax({
	        type: "get",
	        url: "/get/recycleIn",
	        data: {
	            id: indexId
	        }
	    });
	    $(event).parents('tr').hide();
	}
	
	// container size
	function sizeTableHeight() {
	    var containerH = $('body').height() - $('.container_header').height();
	    $('.container').css('height', containerH / 100 + 'rem');
	    var new_height = ($('.container').height() - $('.table-head table').outerHeight() - 18) / 100 + "rem";
	    $('.table-body').css("height", new_height);
	}

	// Tip
	function Myclick() {
	    $('.md-modal').addClass('md-show');
	    $('#globalTip').css("visibility", "inherit");
	    $(".md-modal").unbind("click");
	}
	function hide() {
	    if ($('#prom_name').val() === "") {
	        $('#prom_name').select();
	        $('#prom_name').css("border", "1px solid red");
	        setTimeout(function () {
	            $('#prom_name').css({border: "none", "border-bottom": "1px solid #66cccc"});
	        }, 2000);
	        $('.md-modal').removeClass('md-show');
	        $('#globalTip').css("visibility", "hidden");
	    } else {
	        $('.md-modal').removeClass('md-show');
	        $('#globalTip').css("visibility", "hidden");
	    }
	}

/*===========================================    	  样式区         	================================================*/

	// hover search box input width size
	function hoverSearch() {
	    $('.header_content_right').hover(function () {
	        $(this).children('.search_input').animate({
	            width: "2.5rem",
	            padding: "0 0.05rem"
	        }, 300)
	    }, function () {
	        $(this).children('.search_input').css('width', "0rem").css("padding", "0");
	        $('.search_input').blur();
	    })
	}
	// click nav style switch
	function navSwitch() {
	    $('.header a').click(function () {
	        $('.nav_one').children('a').removeClass('nav_one_Selected');
	        $(this).parents('.nav_one').children('a').addClass('nav_one_Selected');
	    })
	    $('.header .nav_two').children().click(function () {
	        switchoverPage($(this));
	    });
	}	

/*===========================================    	  组件区          	================================================*/

	// pop window tip
	function  popTip(texting){
		$('#globalTip p').html(texting+"活动");
	 	$('#globalTip i').html("确定要"+texting+"当前活动吗？");
	}

	//  未开放功能提示框	
	function promptShow() {
	    $('.warning_box').show();
	    if ($('.warning_box').css("display") == "none") {
	        $('body').css("overflow", "auto")
	    } else {
	        $('body').css("overflow", "hidden")
	    }
	}

	//  删除 登录信息    
	function exit() {
	    var date = new Date();
	    date.setTime(date.getTime() - 10000);
	    document.cookie = 'pp' + "=" + null + "; expires=" + date.toGMTString();
	    document.cookie = 'user' + "=" + null + "; expires=" + date.toGMTString();
	    document.cookie = 'userName' + "=a; expires=" + date.toGMTString();
	    location.href='/login';
	}
	
	//下载记录
	function animateAside() {
	    var v = $('.aside_content').is(":hidden");
	    if (v == true) {
	        $('.aside_content').removeClass('_slideUp')
	        $('.aside_content').stop().show(300);
	    } else {
	        $('.aside_content').addClass('_slideUp')
	        $('.aside_content').stop().hide(700);
	    }
	}
	// 导出 渲染控制
	function exportUnit() {
	    $('.u-export').click(function () {
	        $(this).hide();
	    }).children().click(function (event) {
	        event.stopPropagation();
	    }).find('input[type="checkbox"]').click(function () {
	        if ($(this).is(':checked')) {
	            $(this).attr('checked', true);
	        } else {
	            $(this).attr('checked', false);
	        }
	    })
	}
	
	/* 下载页 */
	function downloads(){
		$('#addDownload').click(function(){
			if($('.g-download').find('table').length<1){
				$('.g-download').load("/pages/get/download" );
			}else{
				$('.g-download').show();
			}
			
		})
	}
	
	// 页面加载后 动态获取表格 宽高 值；	--------------------------------------------------------------------------- 							
	function onlond(e) {
	    var tr_last = $(e).find('.table-head table').find('tr');
	    var thList, thW1, thW2, trWidth;
	    var bodyH = $('body').height();                   // body height
	    var headH = $('.container_header').height(); 		// header-top  height
	    var tableH = $(e).find('.table-head table').height();// table head height
	    var containerH = bodyH - headH;
	    var iWidth = ($(e).find('.sizeWidth').parent('td').width() - 10) / 100; ///超长名字专用			
	    var table_bodyH = containerH - tableH - 30;			// container height
	    if (tr_last.length < 2) {
	        thList = $(e).find('th');						// tr(th) length
	        thW1 = $(e).find('th').eq(0).outerWidth();		// th.eq(0) width
	        //tr width	
	        trWidth = ((thList.length - 1) * thW1 - 163) / 100;		
	        $(e).find('tr').css({width: trWidth + 'rem'});
	        $(e).find('.table-body').css({width: trWidth + 0.2 + 'rem', height: table_bodyH / 100 + "rem"});
	    } else {
	        thList = $(e).find('.table-head table').find('tr').eq(1).find('th');
	        thW1 = $(e).find('.table-head table').find('tr').eq(1).find('th').eq(0).outerWidth();
	        //tr width	
	        trWidth = ((thList.length - 1) * thW1 + 116) / 100;
	        $('body .container').css('height', "auto");
	        $(e).find('tr').css({width: trWidth + 'rem'});
	        $(e).find('.table-body').css({width: trWidth + 0.2 + 'rem', height: (table_bodyH - 80) / 100 + "rem"});
	    }
	    $('.container').css('height', containerH / 100 + 'rem');
	    // tbody width size	
	    e.children('.content_table').css('width', trWidth + 'rem');
	
	    e.find('.sizeWidth').css("width", iWidth + 'rem');
	}


/* download
 ---------------*/

// 名单导出
function listExport(event) {
	var status = $(event).parents('tr').children(':eq(3)').html();
	var itemName = $(event).parents('tr').children(':eq(0)').text();
	if(status == "已完成")
		$('.u-export').show().children().attr('itemName',itemName).animate({"width": "330px",'opacity':'1'}, 300).children().show(200)
		.children('input[type="checkbox"]').each(function () {
			// 下载选项默认选中;
	        $(event).prop('checked',true);
	    });
}