var formatSql2;
var switch_msg;
var useObj;
var tradingObj;
$(function(){
	// HQL 复制
   $('#pre-sql2').click(function(){
   		formatSql2 = $.format(getActivityList.final_sql,{method: 'sql'});
    	$.dialog({
            title: '',
            content: "<pre class='brush:sql;'>"+formatSql2+"</pre>",
            columnClass: 'col-md-1',
            animation: 'top',
            closeAnimation: 'bottom'
        });
        SyntaxHighlighter.highlight();
    })
    getTablelist();
})
/*-----------------------------------------  Initialize function  ------------------------------------------*/
// Initialize the current activity data
	function getTablelist() {
        var msg = getActivityList;// 主页传递参数
        $('#_id').html(msg.id);
        $('#_name').html(msg.name);
        $('#_create_time').html(msg.create_time);
        $('#_task_status').html(msg.task_status);
        $('#_list_count').html(msg.count);
       	msg.task_status == '已完成' ? $('#exportJump').css({"background-color": "#00cccc", "cursor": "pointer"}) :$('#exportJump').css({"background-color": "#ccc", "cursor": "not-allowed"});
        // 其他模块
      	rosterStatus($('#exclude'),msg.excepts);
        rosterStatus($('#contain'),msg.includes);
		var groups2 = htagGroups2("lableGroup");
        groups2.loadData(eval(msg.tags));
        //不要删除下面注释  重要
        console.debug(msg.final_sql);
        console.debug(msg.hive_table);
        
        // 初始化 名单导出与查询按钮 style
        if(msg.task_status === '等待执行' || msg.task_status=== '已取消' )
        	$('.but').css({'background-color':'#ccc','cursor': 'no-drop'});
        	
        getDetails(msg.id);
    }
	
    // 名单状态
	function rosterStatus(_node,data){
		if(data && data.length>0){
			data=data.split(',');
			for(var i = 0 ; i < data.length ; i ++){
				if( i == data.length-1 ){
					var html = '<span>'+ data[i] +'</span>';
				}else{
					var html = '<span>'+ data[i] +',&nbsp;&nbsp;</span>';
				}
				$(_node).append(html);
			}
		}
		$(_node).find('span').length < 1 ? $(_node).parent('.entries_group').hide():$(_node).parent('.entries_group').show();
	}
	
    // get the details
    function getDetails(id){
    	// 通过 ID 获取data
		$.ajax({
				url:"/get/promEffect",
				type:"get",			
				data:{
					prom_id:id
				},
				beforeSend:function (){
		        	$('.loading').show();
		        },
				success: function (data) {
					var data=JSON.parse(data);
					// 使用券dom,交易dom
					var $ticket=$('#use_situation'),$deal=$('#trading_situation');
					switch_msg=data;	
					// data typeof 													
					if(!data || data.length==0){
						// tab 不存在 或者值为空，
						function tabStyle(e){
							e.find('.table-head').css({'overflow':'hidden'});
							e.find('.table-body').css({'border':'none'});
							e.find('tr').eq(0).find('a:not([class="inquire"])').css('cursor','no-drop');
						}
						tabStyle($ticket);
						tabStyle($deal);
						$ticket.find('tr').eq(0).css({'display':'block','width':'1200px'}).next().hide();
						// 动画停止
						setTimeout(function(){$('.loading').hide()});
						return ;
					}else{		
						// each data					
						for(var i=0;i<data.length;i++){
							 ifData(data[i],data.length);
						}
					}
					tabResize($('#activity-Particulars'));
					setTimeout(function(){$('.loading').hide()});
				}
			}
		)
 	}
	    
	// 判断数据类型，载入tab 
	function ifData(data,list){
		var $dom;
		var info = JSON.parse(data.query_params);
		if(data.type == '券使用情况'){
			$dom='use_situation';
            $('#'+$dom).find('#issue_stamps').children('.startTime').val(info.start);
            $('#'+$dom).find('#issue_stamps').children(".endTime").val(info.end);
            $('#'+$dom).find('#Use_coupons').children('.startTime').val(info.start2);
            $('#'+$dom).find('#Use_coupons').children(".endTime").val(info.end2);
			$('#'+$dom).find('#coupos').val(info.coupos);	
			if ( list == 1){
				$('#'+$dom).next().css({'overflow':'hidden'});
				$('#'+$dom).next().find('.table-body').css({'border':'none'});
				$('#'+$dom).next().find('.searchTR').next().hide();
			}
			appTab(data,$dom);
		}else{
			$dom='trading_situation';
			$('#'+$dom).find('.startTime').val(data.start);
			$('#'+$dom).find('.endTime').val(data.end);
			$('#'+$dom).find('#procs').val(info.procs);
			if ( list == 1 ){
				$('#'+$dom).prev().css({'overflow':'hidden'});
				$('#'+$dom).prev().find('.table-body').css({'border':'none'});
				$('#'+$dom).prev().find('.searchTR').next().hide();
			}
			appTab(data,$dom);
		}
	}
	// 加载 tab 数据
	function appTab(data,$dom){
		if(data.status=='已完成'){
			var tabData=data.head200;
			// 复制用数据
			useObj=data.head200;
            if(!tabData || tabData.length < 1){
            	$('#'+$dom).css('overflow','hidden');
            	$('#'+$dom).find('.table-body').hide();
            	$('#'+$dom).find('.searchTR').next().hide();
               return ;
            }
            // 克隆表格标题
            var th = $('.'+$dom).parent().prev().find('tr:last').clone();
            $('.'+$dom).append(th);
            
            
            $('#'+$dom).css('overflow','auto');
        	$('#'+$dom).find('.table-body').show();
        	$('#'+$dom).find('.searchTR').next().show();
			var obj=tabData.split('\n');
			for(var i=0;i<obj.length-1;i++){
				var arr=obj[i].split('\t');	
				var html=$('<tr></tr>');
				for(var n=0;n<arr.length;n++){
					if($dom == 'use_situation'){
						if( n == 5 || n == 7 || n == 14 || n == 16) arr[n] = Math.round(new Number(arr[n]));
					}else{
						if( n == 6 || n == 7 || n == 9) arr[n] = Math.round(new Number(arr[n]));
					}
					var td='<td>'+arr[n]+'</td>';
					html.append(td);
				}
				$('.'+$dom).append(html);
			}
			$('#'+$dom).find('.pre-copy').addClass('cursor');
		}else{
			// ‘查询中’ style 
			queryData($('#'+$dom));
			$('#'+$dom).find('.searchTR').next().hide();
			elicitData();
		}
		itemCount($('#'+$dom));
	} 
	
	/* --------------------------------     状态区      ------------------------------------*/
	
	// ‘查询中’ style
	function queryData(e){	
		e.css({'overflow-x':'hidden'});
		e.find('.table-body').css({'border':'none'});
		e.find('input').attr('disabled','true').css({'background-color':'rgba(248, 248, 248,0.7)','color':'#999'});
		e.find('#inquire').html('正在查询').css({'background-color':'#ccc','cursor': 'no-drop'});
		e.find('#cancel_query').attr('title','取消查询').css({'background-color':'#00CCCC','cursor':'pointer'});
		e.find('.pre-copy').removeClass('cursor');
	}
	// 当前table 条目数
	function itemCount(e){
		if(e.hasClass('showTab')){
			var count=e.find('.table-body').find('tr').length-1;
			if(count >= 0 ){
				$('#numberEntries').html('共：<i style="colr:#666;padding-right:3px;">'+count+'</i>条');
			}else{
				$('#numberEntries').html('');
				e.css('overflow-x','hidden').find('.table-body').hide().prev().find('tr').eq(1).hide();
			}	
		}else{
			return false;
		}
	}
	
	/*---------------------------------     功能区         -----------------------------------*/
	
	// table 切换
	function tabSwitchover(e){
		$('.table_nav').find('li').removeClass('markup');
		$(e).addClass('markup');
		// 对象索引对应的 table ,选项卡切换类型;
		var $this=$('.content_table .table_content').eq($(e).index());
		$this.siblings().removeClass('showTab');
		$this.addClass('showTab');
		// 数据是否存在	
		if(!switch_msg || switch_msg.length<1){	
			$this.find('.table-head').css('overflow','hidden').find('tr').eq(1).hide();
			$this.find('.table-body').css({'border':'none'});
			return;
		}else{
			itemCount($this);
		}
		tabResize($('#activity-Particulars'));
	}
	
	// 查询
	// The date format to judge
	function dateJudge(e) {
		if($('#_task_status').html() !== '等待执行' && $('#_task_status').html() !== '已取消'){
			e=$(e).parents('table');
			var isTime = true
		    // 获取 date start and end的值；
		    var inquireName=$(e).find('.names').val(); 
		    var ID=$('#_id').html();
		    if($(e).parents('.table_content').attr('id')==='use_situation'){
		    	var startTime=$(e).find('#issue_stamps').children('.startTime').val();
		    	var endTime = $(e).find('#issue_stamps').children(".endTime").val();
		    	var startTime2=$(e).find('#Use_coupons').children('.startTime').val();
		    	var endTime2 = $(e).find('#Use_coupons').children(".endTime").val();
		    	if (startTime != "" && endTime != "" && startTime2 != "" && endTime2 != "") {
			    	if(endTime < startTime && endTime2 < startTime2){
			    		isTime = false;
			    	}else{
			    		isTime = true;
			    	}
				    if (isTime != true) {
				    	$('#globalTip p').html("错误信息：");
				    	$('#globalTip strong').html("");
				        $('#globalTip i').html("结束时间必须晚于开始时间！");
				        Myclick();
				    }else{
				    	if(inquireName!='' && inquireName.length>0){
			    			var inquire=$(e).find('#inquire'); 
			    			if(inquire.html()==='查询'){
					    		// sbumite inquire
						    	var typeName=$('.markup').children().html();
						    	//console.log(typeName+'/'+startTime+"/"+endTime+'/'+inquireName+'/'+ID);
		                        var params = {};
		                        params.start = startTime;
		                        params.end = endTime;
		                        params.start2 = startTime2;
		                        params.end2 = endTime2;
		                        params.type = typeName;
		                        params.id = ID;
		                        params.coupos = inquireName;
		                        params.hive_table = getActivityList.hive_table;
		                        params.param = JSON.stringify(params);
		                        submitPromEffect(params);
		                        queryData(e.parents('.table_content'));
		                        elicitData()
		                        return
				    		}else{ 
				    			elicitData()
				    			return
				    		}
				    	}else{
				    		$('#globalTip p').html("错误信息：");
					    	$('#globalTip strong').html("");
					        $('#globalTip i').html("券名称不能为空！");
					        Myclick();
					        return false;
				    	}		    		    	
				    }
			    } else {
			    	$('#globalTip p').html("错误信息：");
			    	$('#globalTip strong').html("");
			        $('#globalTip i').html("请填写完整日期！");
			        Myclick();
			        return false;
			    }
		    }else{
		    	var startTime=$(e).find('.startTime').val();
		    	var endTime = $(e).find(".endTime").val();
		    	if (startTime != "" && endTime != "") {
			    	endTime < startTime ? isTime = false:isTime = true;
				    if (isTime != true) {
				    	$('#globalTip p').html("错误信息：");
				    	$('#globalTip strong').html("");
				        $('#globalTip i').html("结束时间必须晚于开始时间！");
				        Myclick();
				    }else{
				    	var inquire=$(e).find('#inquire'); 
		    			if(inquire.html()==='查询'){
				    		// sbumite inquire
					    	var typeName=$('.markup').children().html();
					    	var inquireName=$(e).find('.names').val();
					    	//console.log(typeName+'/'+startTime+"/"+endTime+'/'+inquireName+'/'+ID);
		                    var params = {};
		                    params.start = startTime;
		                    params.end = endTime;
		                    params.type = typeName;
		                    params.id = ID;
		                    params.procs = inquireName;
		                    params.hive_table = getActivityList.hive_table;
		                    params.param = JSON.stringify(params);
		                    submitPromEffect(params);
		                    queryData(e.parents('.table_content'));
		                    elicitData()
		                    return
			    		}else{ 
			    			elicitData()
			    			return
			    		}  	
				    }
			    } else {
			    	$('#globalTip p').html("错误信息：");
			    	$('#globalTip strong').html("");
			        $('#globalTip i').html("请填写完整日期！");
			        Myclick();
			        return false;
			    }
		    }
		}else{
			false;
		}
	}
	// 正在查询  每3秒获取一次数据
	function elicitData(){
		$('.inquire').each(function(){
			if($(this).html() == '正在查询'){
				$(this).parents('.table-head').next().find('tbody').find('tr').remove();
				$(this).parents('.searchTR').next().hide();
			 	elicit=setInterval(function(){
					window.location.reload();
				},10000)
			}
		})
	}

	//  取消查询
	function cancelQuery(e){
		if($(e).attr('title')==='取消查询'){
			clearInterval(elicit)
			// 获取 date start and end的值；
		    var startTime=$(e).parent('th').siblings().children('.startTime').val();
		    var endTime = $(e).parent('th').siblings().children(".endTime").val();
		    var inquireName=$(e).parent('th').siblings().children('.names').val(); 
		    var ID=$('#_id').html();
			$(e).attr('title','');		   						
			$(e).css({'background-color':'#ccc'});
	        $(e).html('取消查询');
	        $(e).parent('th').siblings().children('#inquire').html('查询');
	       	$(e).parent('th').siblings().children('#inquire').css({'background-color':'#00CCCC', "cursor": "auto"});
	       	$(e).parents('tr').find('input').css({'background-color':'white','color':'#333'});	    
	        $(e).parents('tr').find('input').removeAttr('disabled');
		}else{
			return;
		}
	}
	//  动态获取表格 宽高 值；								
	function tabResize(e){
		var $this=$(e).find('.showTab'); // 当前显示的 table
		var thList=$($this).find('.table-head tr').eq(1).find('th');// tr(th) length
		var trW=0;												//tr width
		//超长名字专用	
		var iWidth=($(e).find('.sizeWidth').parent('td').width()-10)/100;		
		// 循环获取th总宽度
		for(var i=0;i<thList.length;i++){
			console.log($(thList).eq(i).width())
			trW=trW+($(thList).eq(i).outerWidth() - .2);
		}
		$($this).find('.table_scroll').css('width',trW);
		$($this).find('tr').css({width:trW});
		$($this).find('.sizeWidth').css("width",iWidth+'rem');	
		$($this).parent().css('height',$($this).outerHeight());
		$($this).find('.table-body').css({'height':$($this).height()-$($this).find('.table-head').height()-17,'overflow-y':'auto'});	
	}	
	
	function submitPromEffect(params){
	   console.debug(params);
	   $.ajax({
	       url: "/get/submitPromEffect",
	       async: true,
	       type: 'POST',
	       data:params,
	       success:function(text){
	           try{
	            //console.debug(text);
	            var o = JSON.parse(text);
	            if(o.code === 1){
	                $('#globalTip p').html("提交失败");
	                $('#globalTip strong').html("");
	                $('#globalTip i').html(o.message);
	                Myclick();
	            }
	           }catch(e){}
	       }
	   }); 
	}
	
	function cancelPromEffect(prom_id){
	    $.ajax({
	       url: "/get/cancelPromEffect",
	       async: false,
	       type: 'POST',
	       data:{prom_id:prom_id},
	       success:function(text){
	           try{
	            //console.debug(text);
	            var o = JSON.parse(text);
	            if(o.code === 1){
	                $('#globalTip p').html("取消失败");
	                $('#globalTip strong').html("");
	                $('#globalTip i').html(o.message);
	                Myclick();
	            }
	           }catch(e){}
	       }
	   }); 
	}
	
/*===================================       插件区              =================================*/		

	//   Date 插件   start
	$('#ca').calendar({
	    width: 320,
	    height: 320,
	    data: [
	        {
	            date: '2015/12/24',
	            value: 'Christmas Eve'
	        },
	        {
	            date: '2015/12/25',
	            value: 'Merry Christmas'
	        },
	        {
	            date: '2016/01/01',
	            value: 'Happy New Year'
	        }
	    ],
	    onSelected: function (view, date, data) {
	    }
	});
	function createDate(event) {  
	    $("<div id='da'></div>").calendar({
	        trigger: $(event),
	        zIndex: 999,
	        format: 'yyyy-mm-dd'
	    }).appendTo($("body"));
	}
	
