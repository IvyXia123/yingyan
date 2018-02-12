var r = {};
var logInformation = {
	config_id:undefined,// 任务ID
	taskName:undefined, // 任务名称
	skipRowNumber:0	    // 当前加载行数
}
r.dom  = $('#u-items');
var myDate = new Date(),
	Month  = (myDate.getMonth()+1) < 10 ? '0'+(myDate.getMonth()+1) : (myDate.getMonth()+1),
	myDate = myDate.getFullYear() +'-'+ Month +'-'+ myDate.getDate(),
	timer  = undefined;
// 初始化 顶层节点
//一级菜单
if(typeof r.initialNode != 'function'){
	r.initialNode = function(){
		r.aa = '1123';
		$.ajax({
			url:"http://ds.idc.xiwanglife.com/view/getTree.do?id=-1&US_SESSION_ID="+dataUser,
			type:"get",
			dataType:"json",
			async:true,
			success:function(data){
				var ul=$('<ul class="items"></ul>');
				for(var i=0;i<data.length;i++){
					var li='<li id="'+data[i].id+'"><span>'+data[i].text+'</span></li>';
					ul.append(li);
				}
				r.dom.append(ul);
			},
			error:function(){
				$('#globalTip p').html('');
				$('#globalTip i').html("登录信息过期，请重新登陆!");
        		Myclick();
			}
		});
	}   
}
// nav netx node
//二级菜单
r.nextNode = function(event){
	var UL=$('<ul class="item"></ul>');
	var $this = $(event.target).parent();
	r.staff($this,UL);
	r.task($this,UL);
}
// 人员数据
r.staff = function($this,UL){
	$.ajax({
          type: "get",
          url:"http://ds.idc.xiwanglife.com/view/getTree.do",
          data:{
          	id:r.findId,
          	US_SESSION_ID:dataUser
          },
          dataType:'json', //接受数据格式
          success: function(data){
          	if( data == "" || !data)return;
          	if($this.children('ul').length >0)$this.children('ul').remove();
			for(var i=0;i<data.length;i++){
				var li='<li id="'+data[i].id+'"><span date-type="staff">'+data[i].text+'</span></li>';
				UL.append(li);
			}
			$this.append(UL);//li
   		}	
	});
}
 
// 任务数据
 r.task = function($this,UL){
	$.ajax({
          type: "get",
          url:"http://ds.idc.xiwanglife.com/view/getConfigsByGroupId.do",
          data:{
          	id:r.findId,
          	US_SESSION_ID:dataUser
          },
          dataType:'json', //接受数据格式
          success: function(data){
          	if( data == "" || !data)return;
          	if($this.children('ul').length >0)$this.children('ul').remove();
			for(var i=0;i<data.length;i++){
				var li='<li id="'+data[i].id+'"><span date-type="task">'+data[i].configName+'</span></li>';
				UL.append(li);
			}
			$this.append(UL);//li
   		}	
	});
}

// 任务详情
r.GetConfig = function(event){
	$('.r-m-content').empty();
	r.findId = $(event.target).parent().attr('id');
	$.ajax({
		type:"get",
		url:"http://ds.idc.xiwanglife.com/view/getConfigById.do?",
		data:{
			id:r.findId,
			US_SESSION_ID:dataUser
		},
		dataType:'json',
		async:true,
		 success: function(data){
		 	if(data == "" || !data )return;
			var html = '<div class="r-m-div">'
						+'<div class="tabs-box">'
							+'<ul>'
								+'<li class="in_left tabs-ma tabs-underline tabs-active">参数</li>'
								+'<li class="in_left tabs-ma">日志</li>'
							+'</ul>'
						+'</div>'
						+'<div id="tabs">'
							+'<div class="tabs-one">'
								+'<form action=""class="r-form">'
									+'<p>'+data.configName+'</p>'
									+'<ul class="r-ul-one">'
										+'<span >名称：</span>'
										+'<li><input type="text" placeholder="'+data.configName+'"/></li>'
									+'</ul>'
									+'<ul class="r-ul-one">'
										+'<span>备注：</span>'
										+'<li><textarea name="" rows="5" cols="50" placeholder="'+data.remark+'"/></textarea></li>'
									+'</ul>'
									+'<ul class="r-ul-two">'
										+'<li>以下是所有参数的信息</li>'
									+'</ul>'
								+'</form>'
								+'<div class="execute">'
									+'<button id="execute_btn" data_id="'+r.findId+'">执行</button>'
								+'</div>'
						+'</div>'
						+'<div class="tabs-two clearfix" style="display:none;">'
							+'<table id="this_window">'
								+'<tr><td style="width: 134px;">任务名称</td><td id="taskName">loading...</td></tr>'
								+'<tr><td>参数信息</td><td id="params">loading...</td></tr>'
								+'<tr><td>备注说明</td><td id="remark">loading...</td></tr>'
								+'<tr><td>任务状态</td><td id="status">loading...</td></tr>'
								+'<tr><td>日志路径</td><td id="logPath">loading...</td></tr>'
								+'<tr><td>日志路径</td><td id="errorLogPath">loading...</td></tr>'
								+'<tr><td>日志信息</td><td id="content">loading...</td></tr>'
							+'</table>'
						+'</div>'
					+'</div>'
				+'</div>';
			$('.r-m-content').append(html);
			$.ajax({
		          type: "get",
		          url:"http://ds.idc.xiwanglife.com/dataservice/getparams.do",
		          data:{
		          	id:r.findId,
		          	US_SESSION_ID:dataUser
		          },
		          dataType:'json', //接受数据格式
		          success: function(data){
		          	var data = data.params;
		          	if( data == "" || !data)return;
					for(var i = 0;i < data.length;i++){
						var p ='<ul class="r-ul-one"><span date-type="task">'+data[i]+'</span><li><input type="text" data-name="'+data[i].substring(2,data[i].length-1)+'"/></li></ul>';
						$('.r-ul-two').append(p);
					}
		   		}	
			});
		}
	});
}
r.initialNode();

r.log = function(Id){
	var canshu = {};
	if(!canshu || canshu.length < 1){
		return true;
	}else{
		var iptList = $('.r-ul-two').find('input');
		for(var n = 0; n < iptList.length; n++){
			
			canshu[$(iptList).eq(n).attr('data-name')] = $(iptList).eq(n).val()
		}
		canshu.config_id = logInformation.config_id;
	}
	$.ajax({
		type:"post",
		url:"http://ds.idc.xiwanglife.com/view/addTask.do?",
		data:canshu
		,
		xhrFields: {
          	withCredentials: true
      	},
		crossDomain: true,
		async:false,
		success:function(data){
			logInformation.taskName = data.message;
			var this_window = $('#this_window');
			r.viewLog(this_window);
		}
	});
}
r.tasks = function(myTime){
	protos = true;
	$.ajax({
		type:"post",
		url:"http://ds.idc.xiwanglife.com/view/findTaskByUser.do?time="+myTime+"&page=1&rows=99999",
		async:true,
		xhrFields: {
          	withCredentials: true
      	},
		crossDomain: true,
		success:function(data){
			//taskList
			var data = data.rows,
				count = 1;
			for(var i = data.length-1; i >= 0; i--){
				var tr = '<tr>'
						+'<td>'+count+'</td>'
						+'<td>'+data[i].remark+'</td>'
						+'<td>'+r.getStatus(data[i].status)+'</td>'
						+'<td>'+getMyDate(data[i].executeTime)+'</td>'
						+'<td>'+getMyDate(data[i].endTime)+'</td>'
						+'<td><a href="javascript:;" data-type="'+data[i].taskName+'" data-page="logInformation">日志&nbsp;</a>'
							+'<a href="javascript:;" data-type="">&nbsp;&nbsp;下载&nbsp;&nbsp;</a>'
							+'<a href="javascript:;" data-type="'+data[i].taskName+'" data-page="historyReport">&nbsp;报表</a>'
						+'</td></tr>';
				$('#taskList').append(tr);
				count++
			}
		}
	});
}

// 日志弹出页
r.viewLog = function(this_window){
	// 绑定新的框架
	$.ajax({
		type:"get",
		url:"http://ds.idc.xiwanglife.com/view/findTaskLogByName.do",
		data:{
			taskName:logInformation.taskName,
			skipRowNumber:logInformation.skipRowNumber
		},
		dataType:'json',
		async:false,
		xhrFields: {
          	withCredentials: true
      	},
		crossDomain: true,
		success: function(data){
		 	try {
				// task info
				this_window.find("#taskName").html(data.task.taskName);
				this_window.find("#remark").html(data.task.remark);
				this_window.find("#logPath").html(data.task.logPath);
				this_window.find("#errorLogPath").html(data.task.errorLogPath);
				var content = "";
				var params = data.task.params.split(",");
				for (var i in params) {
					if (content != "") {
						content += "<br/>";
					}
					content += params[i];
				}
				this_window.find("#params").html(content);
				var status = r.getStatus(data.task.status);
				this_window.find("#status").html(status);
				// log
				this_window.find("#content").html(this_window.find("#content").html().replace(/loading.../,""));
				this_window.find("#content").append(data.logModel.content);
				var status = r.getStatus(data.task.status);
				if (status == "正在执行" || status == "等待执行" || logInformation.skipRowNumber != data.logModel.total || logInformation.skipRowNumber == 0) {
					logInformation.skipRowNumber = data.logModel.total;
					timer = setTimeout(function(){r.viewLog(this_window)}, 2000);
				}else{
					clearTimeout(timer);
				}
			} catch (e) {
				timer = setTimeout(r.viewLog(this_window),3000);
			}
		}
	});
}

/**************************************       点击判断             *****************************************/
// The span type on the left
r.isType = function(event){
	var types = $(event.target).attr('date-type');
	r.findId = $(event.target).parent().attr('id'); //是一级菜单的每个id
	// 当前对象是否存在同级UL,不存在就请求创建
	if( $(event.target).siblings('ul').length > 0 ){
		$(event.target).siblings('ul').remove();
	}else{
		if(types == 'task'){
			timer != undefined ? clearTimeout(timer):false;
			logInformation.taskName = undefined;
			logInformation.skipRowNumber = 0;
			r.GetConfig(event);
		}else if(types == 'staff'){
			var ul=$('<ul class="item2"></ul>');
			var $this = $(event.target).parent();
			r.task($this,ul);
		}else if(types == undefined){
			r.nextNode(event);
		}
	}
}

/**************************************        功能性function       ****************************************/
//  日志
r.getStatus = function(value) {
	if (value == "WAIT")
		return "等待执行";
	if (value == "RUN")
		return "正在执行";
	if (value == "SUCCESS")
		return "执行成功";
	if (value == "FAIL")
		return "执行失败";
	if (value == "CANCEL")
		return "执行取消";
	return "未知状态";
}
