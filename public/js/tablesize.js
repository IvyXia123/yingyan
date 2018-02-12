//  动态获取表格 宽高 值；								
function onloading(e){
	var $this = $(e).find('.g-table');
	var count = 0; // 单行 tr 总宽度 
	var $firstTab = $this.find('table:first');
	var $lastTab = $this.find('table:last');
	var listTh = $this.find('tr:first').children('th');
	var widthName = $(e).find('.sizeWidth').parent('td').width()-10;
	for(var i=0;i<listTh.length;i++){
		count += $(listTh).eq(i).outerWidth();
	}
	setTimeout(function(){
		var size=$this.height()-$firstTab.outerHeight();
		$lastTab.parent().css({height:size});           // tHead
		$this.find('.sizeWidth').css("width",widthName);// <i>
	    $this.find('tr').css('width',count);            // tr
	    // 判断  tab box 是否需要 overflow-x 滚动条
	    if( count < ($(window).width()*0.9+2)){  
	    	$this.css({'overflow-x':'hidden'});
	    }else{
	    	$this.css({'width':count,'overflow-x':'auto'});
	    }
	    if($(e).attr('id') == 'features'){	    	
			$this.css('width',count);
			$lastTab.parent().css('height',$lastTab.outerHeight());
			$(e).parent().css('height',$(e).outerHeight());	
	    }
	},10)
}
function sizeTab(){
	var $e=$('#g-container');
	onloading($e.children('section[class="container_show"]'));
}
