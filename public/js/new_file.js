// 页面加载后 动态获取表格 宽高 值；	--------------------------------------------------------------------------- 							        						
		function onloadStrategy(e){
			var thList=$(e).find('.table-head tr').eq(0).find('th');// tr(th) length
			var trW=0;												//tr width
			//超长名字专用	
			var iWidth=($(e).find('.sizeWidth').parent('td').width()-10)/100;		
			// 循环获取th总宽度
			for(var i=0;i<thList.length;i++){
				trW=trW+$(thList).eq(i).outerWidth();           		// TH 盒模型宽度;
			}
			$('.container').css('height',$(e).outerHeight());					
			// tbody width size
			$(e).find('.table-body').css({width:trW+30});
			$(e).find('.sizeWidth').css("width",iWidth+'rem');							
		}
		//  动态获取表格 宽高 值；								
function onloading(e){
	var bodyH=$('body').height();                   		// body height
//	var headH=$('.container_header').height(); 				// header-top  height
//  var tableH=$(e).find('.table-head table').height(); 	// table head height
//  var tableW=$(e).find('.table-head table').width();		// table head width
	var containerH=bodyH-headH;								// container height
	var thList=$(e).find('.table-head tr').eq(0).find('th');// tr(th) length
	var trW=0;												//tr width
	//超长名字专用	
	var iWidth=($(e).find('.sizeWidth').parent('td').width()-10)/100;		
	var table_bodyH=(containerH-tableH); 			//table_body height	
	// 循环获取th总宽度
	for(var i=0;i<thList.length;i++){
		trW=trW+$(thList).eq(i).outerWidth();           		// TH 盒模型宽度;
	}
	$('.container').css('height',containerH/100+'rem');					
	// tbody width size
	//$(e).children('.content_table').css('width',trW/100+'rem');
	$(e).find('.table-body').css({width:trW+30,height:table_bodyH/100+"rem"});
	$(e).find('.sizeWidth').css("width",iWidth+'rem');							
}

		//  动态获取表格 宽高 值；								
function onloadings(e){
	var bodyH=$('body').height();                   // body height
	var headH=$('.container_header').height(); 		// header-top  height
	var tableH=$(e).find('.table-head table').height();// table head height
	var containerH=bodyH-headH;						// container height
	var thList;	// tr(th) length
	var trW=0;									///tr width
	//超长名字专用	
	var iWidth=($(e).find('.sizeWidth').parent('td').width()-10)/100;		
	var table_bodyH=(containerH-tableH); 			//table_body height	
	if($(e).attr('id')==='features'){
		thList=$(e).find('.table-head tr').eq(1).find('th');
		$('.container').css('height',$(e).outerHeight());
		$(e).find('.table-body').css('height',$('#fication_tables').outerHeight());
	}else{
		thList=$(e).find('.table-head tr').eq(0).find('th');
		$('.container').css('height',containerH/100+'rem');
		$(e).find('.table-body').css('height',table_bodyH-35);
	}
	// 循环获取th总宽度
	for(var i=0;i<thList.length;i++){
		trW=trW+$(thList).eq(i).outerWidth();
	}			
						
	// tbody width size	
	$(e).children('.content_table').css('width',trW);
	$(e).find('.table-body').css({width:trW+20});
	$(e).find('.sizeWidth').css("width",iWidth+'rem');							
}	