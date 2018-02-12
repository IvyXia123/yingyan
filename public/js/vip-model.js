'use strict';
$(document).ready(function () {
    $.ajax({
        url: "/get/modelTree",
        async: true,
        type: 'POST',
        beforeSend:function (){
    		$('.loading').show();
	 	},
        success: function (text) {
            try {
                var o = JSON.parse(text);
                formatModelTree(o);
                var nodeTree1=datascource.children;
				for(var i=0;i<nodeTree1.length;i++){
					var nodeTree2=nodeTree1[i].children;
					for(var a=0;a<nodeTree2.length;a++){
						var nodeTree3=nodeTree2[a].children;
						for(var b=0;b<nodeTree3.length;b++){
							var value=nodeTree3[b].title;
							datascource.children[i].children[a].children[b].title=value.replace(/_/g,"<br/>")
						}
					}
				}
			    $('#chart-container').orgchart({
			      'data' : datascource,
			      'nodeContent': 'title',
			      'direction': 'l2r'
				});	
            } catch (e) {
            }
            removeNode();
			setTimeout(function(){$('.loading').hide()});
			unitTip();
        }
    });
});
var datascource = {};
function formatModelTree(list) {
    
    for (var i = 0; i < list.length; i++) {
        
        if (!datascource.name) {
            datascource.name = list[i].node1;
            datascource.children = [];
        }
        
        var node2 = getChild(datascource.children, list[i].node2);
        if (!node2) {
            var node2 = {};
            node2.name = list[i].node2;
            node2.children = [];
            datascource.children.push(node2);
        }
        
        var node3 = getChild(node2.children, list[i].node3);
        if (!node3) {
            //console.debug(list[i].node2);
            var node3 = {};
            node3.name = list[i].node3;
            node3.children = [];
            node2.children.push(node3);
        }
        
        var node4 = getChild(node3.children, list[i].node4);
        if (!node4) {
            //console.debug(list[i].node2);
            var node4 = {};
            node4.name = list[i].node4;
            //node4.children = [];
            node3.children.push(node4);
        }
        node4.title = list[i].value;
    }
}

function getChild(arr, child) {
    for (var i=0;i<arr.length;i++) {
        //console.debug(arr[i].name + "--" + child);
        if (arr[i].name === child)
            return arr[i];
    }
    return undefined;
}

//-----------------------------------------------------------------------------
// Retrieves node the width of the
function nodeWidth(){
	$('.node').each(function(){
		var size=$(this).children('.title').outerHeight()+$(this).children('.content').outerHeight()+20;
		$(this).css('width',size);
	})
}
// remove no duplicate node
function removeNode(){
	$('.content').each(function(){
		var lengths=$(this).html();
		lengths=lengths.replace(/\s+/g, "");
		if(lengths.length<1){
			$(this).parent('.node').children('.title').css({'position':'relative',top:'-6px',left:'13px',height:'30px','line-height':'26px','border-radius':'4px'});
			$(this).remove();
		}else{
			$(this).parent('.node').children('.title').css({'position':'relative',top:'80px',left:'-70px','width':$(this).outerWidth()});
			$(this).css({'position':'relative',top:'80px',left:'-70px'});
			$(this).parent().css({'height':$(this).outerWidth()+20});			
		}
		if(lengths.length>12){
			$(this).css('text-align','left')
		}
	})
	nodeWidth();
}
// unit tip
function unitTip(){
	$('.title').each(function(){
		$(this).hover(function(){
			if($(this).text().length>12){
				var Text=$(this).text();
				var offsetTop=$(this).offset().top-$(this).parents('#chart-container').offset().top;
				var offsetLeft=$(this).offset().left-$(this).parents('#chart-container').offset().left;
				var top=offsetTop-$(this).outerHeight()-20;
				$('#u-tip').html(Text).css({top:top,left:offsetLeft,'opacity':1});
			}
		},function(){
			$('#u-tip').css({top:-10,'opacity':0});
		})					
	})	
}
