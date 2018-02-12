
	Date.prototype.format = function (fmt) {
	    var o = {
	        "M+": this.getMonth() + 1,
	        "d+": this.getDate(),
	        "h+": this.getHours(),
	        "m+": this.getMinutes(),
	        "s+": this.getSeconds(),
	        "q+": Math.floor((this.getMonth() + 3) / 3),
	        "S": this.getMilliseconds()
	    };
	    if (/(y+)/.test(fmt))
	        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	    for (var k in o)
	        if (new RegExp("(" + k + ")").test(fmt))
	            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
	    return fmt;
	};
	
	Date.prototype.add = function (part, value) {
	    
	    value *= 1;
	    if (isNaN(value)) {
	        value = 0;
	    }
	    switch (part) {
	        case "y":
	            this.setFullYear(this.getFullYear() + value);
	            break;
	        case "m":
	            this.setMonth(this.getMonth() + value);
	            break;
	        case "d":
	            this.setDate(this.getDate() + value);
	            break;
	        case "h":
	            this.setHours(this.getHours() + value);
	            break;
	        case "n":
	            this.setMinutes(this.getMinutes() + value);
	            break;
	        case "s":
	            this.setSeconds(this.getSeconds() + value);
	            break;
	        default:
	
	    }
	    return this;
	};
function Dates(_newDate,_Dom){
	var _index=$(_Dom).find('th');
	date = new Date(_newDate);
	var indexList=_index.length-1;
	if(indexList<=9){
		var count=indexList;
		for(var i=1;i<indexList;i++){
			count=indexList-i;
			var dates=new Date(_newDate).add('d',-i+1).format("yyyy-MM-dd");
			$(_index[count]).html(dates);
		}
	}else{
		var count=indexList;
		for(var i=0;i<indexList;i++){
			count=indexList-i-1;
			$(_index).eq(count).html(new Date(_newDate).add('d',-i).format("yyyy-MM-dd"));
		}
	}
	/*fdate = d.date.format("yyyy-MM-dd");*/	
}