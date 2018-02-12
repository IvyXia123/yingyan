$(document).on('click',function(event){
	var tagName  = event.target.nodeName.toLowerCase(),		// tag name
	 	strClass = event.target.className,       	 	   // class name
	 	currentPageId = showId(),  					      // The current display view page id
	 	day  = $(event.target).attr('data-calendar-day'),//
	 	list = 0;
	
	// download animation
  	if (strClass.indexOf("aside_btn") < 0) {
 	 	$('.aside_content').addClass('_slideUp')
      	$('.aside_content').stop().hide(700);
  	}
  	
  	// 首页
  	if(currentPageId == 'index_content'){
  		
  	}
  	
  	// 首页 》 活动详情
  	if(currentPageId == 'activity-Particulars'){
  		
  	}
  	
  	// 活动创建
  	if(currentPageId == 'activity-creates'){
  		
  	}
  	
  	// 回收站
  	if(currentPageId == 'recycle'){
  		
  	}
  	
  	// 渠道分类模型
  	if(currentPageId == 'classification_model'){
  		
  	}
  	
  	// 渠道分类模型 》 渠道分类明细特征
  	if(currentPageId == 'trait_detail'){
  		
  	}
  	
  	// 渠道分类模型 》 类特征详情
  	if(currentPageId == 'features'){
  		
  	}
  	
  	// 渠道预警模型
  	if(currentPageId == 'warning_model'){
  		
  	}
  	
  	// 渠道预警模型 》 交易金额
  	if(currentPageId == 'moneyWarning'){
  		
  	}
  	
  	// 渠道预警模型 》 交易人数
  	if(currentPageId == 'peopleWarning'){
  		
  	}
  	
  	// 营销策略分析模型
  	if(currentPageId == 'strategy_model'){
  		
  	}
  	
  	// 会员分类营销模型
  	if(currentPageId == 'vip-model'){
  		
  	}
  	
  	// 自助报表
  	if(currentPageId == 'reportForms'){
  		// 当前点击对象为左侧标题栏span,
		if(tagName == 'span' && $(event.target).parents('section').attr('id')  == 'u-items'){
			// span 类型
			r.isType(event);
		}
		//以下是动态添加tab切换
		if($(event.target).hasClass('tabs-ma')){
	      	var index=$(event.target).index();
			$(event.target).addClass('tabs-underline').siblings().removeClass('tabs-underline');
			$('#tabs').children().eq(index).show().siblings().hide();
		}
		if($(event.target).attr('id') == 'execute_btn'){
			// 执行活动
			$(event.target).parents('#tabs').prev().children().children(':first').removeClass('tabs-underline').next().addClass('tabs-underline');
			$(event.target).parents('#tabs').children(':first').hide().next().show();
			logInformation.config_id = $(event.target).attr('data_id');
			r.log();
		}
  	}
  	
  	// 自助报表 》 我的列表
  	if(currentPageId == 'myTask'){
  		if(tagName == 'a'){
  			if($(event.target).html().indexOf('日志') >= 0){
				timer != undefined ? clearTimeout(timer):false;
				// 历史日志查看
				logInformation.taskName = $(event.target).attr('data-type');
				logInformation.skipRowNumber = 0;
				var page = $(event.target).attr('data-page');
				// 页面加载
				$.ajax({
					type:"post",
					url:"/pages/get/"+page,
					async:true,
					success:function(data){
						if($('.'+page).children('.logContent').length > 0){
							return true;
						}else{
							$('.'+page).animate({'height':'90%',opacity:1},100,function(){$(this).show()}).append(data);
							t.viewLog($('.'+page).find('table'));
						}
					}
				});
			}
  			// 报表
			if($(event.target).html().indexOf('报表') >= 0){
				$('.logContent').remove();
				if(timer != undefined) clearTimeout(timer);
				// 历史日志查看
				logInformation.taskName = $(event.target).attr('data-type');
				logInformation.skipRowNumber = 0;
				var page = $(event.target).attr('data-page');
				// 页面加载
				if($('.'+page).children('.logContent').length < 1)t.getForms(page);
			}
  		}
		if($(event.target).hasClass('shut')){
			// 日志详情关闭
			$(event.target).next('.logContent').remove();
			$(event.target).parent().animate({'height':0,opacity:0},500);
		}
		// 查询任务
		if( tagName == 'li' && day != undefined){
			var myTime = $('#task').val();
			var startTime = myTime+" 00:00:00" ,
				endTime   = myTime+" 23:59:59" ;
			$('#taskList').empty();
			t.tasks(startTime,endTime);
		}
  	}
})
/*===============================================================================*/
function showId(){
	return $('body').find('#g-container').children(':not(:hidden)').attr('id');
}
