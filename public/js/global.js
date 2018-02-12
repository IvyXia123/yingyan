var g = {};
g.dayPass = false;
g.hourPass = false;

g.base = "/";
g.maxWidth = 1100;


function formatDate(d) {
    d.date = new Date();
    d.fdate = d.date.format("yyyy-MM-dd");
    d.yestoday = d.date.add('d', -1).format("yyyy-MM-dd");
    d.seven = d.date.add('d', -7).format("yyyy-MM-dd");
    d.hour = d.date.getHours();
    d.day = new Date(d.fdate).getDate();
    d.dayPass = false;
    d.hourPass = false;
}


formatDate(g);

function dateInterval() {
    var tmp = {};
    formatDate(tmp);
    if (tmp.fdate !== g.fdate) {
        g.dayPass = true;
        g = tmp;
    }
    if (tmp.hour !== g.hour) {
        //alert(tmp.hour + "-" + g.hour);
        g.hour = tmp.hour;
        g.hourPass = true;
    }
}

(function ($) {
    $.getUrlParam = function (name)
    {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null)
            return unescape(r[2]);
        return null;
    }
})(jQuery);

var p = {};

g.timeout = 10000;

g.queryData = function (date, url, callback) {
    date = date.replace(/-/g, '');
    $.ajax({
        url: url,
        async: true,
        type: 'POST',
        timeout: g.timeout,
        data: {day: date},
        xhrFields: {
            withCredentials: false
        },
        success: function (text, textStatus) {
            try {
                var o = JSON.parse(text);
                callback(o);
            } catch (e) {
                //console.error(e);
                $.hideIndicator();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $.hideIndicator();
            $.toast("获取数据超时");
        }
    });
};

g.parseFloat = function(record, value, x){
    return parseFloat(value).toFixed(2);
};



