
var format1 = function (data) {
    var d = [];
    var d1 = [];
    //console.debug(data.length);
    for (var i = 0; i < data.length; i++) {

        var t1 = data[i]["t1"];
        var t2 = data[i]["t2"];
        var t3 = data[i]["t3"];
        var type = data[i]["type"];
        var field = data[i]["t4"];
        var table = data[i]["t5"];

        if (!d[data[i]["t1"]]) {
            var c1 = {};
            c1.id = i;
            c1.text = t1;
            c1.children = [];
            d[t1] = c1;
            d1.push();
        }

        if (!d[t1].children[t2]) {
            var c2 = {};
            c2.text = t2;
            c2.field = field;
            c2.table = table;
            c2.children = [];
            d[t1].children[t2] = c2;
        }

        var c3 = {};
        c3.otype = type;

        switch (type) {
            case 1:
                c3.type = "a";
                break;
            default:
                c3.type = "input";
                c3.placeholder = t3;
                if (!t3 || t3.length == 0)
                    c3.placeholder = t2;
                break;
        }
        //console.debug(c3.type);
        if (t3 && t3.length > 0) {
            c3.text = t3;
        } else {
            c3.text = t2;
        }
        d[t1].children[t2].children.push(c3);
    }
    return d;
};
var d;
$.ajax({
    url: "/get/menu",
    async: false,
    type: 'POST',
    success: function (text, textStatus) {
        //console.debug(text);
        d = JSON.parse(text);
        d = format1(d);
        //console.debug(d);
    }
});

var count = 1;
var d3;

var hmenu = function (_id) {
    var menu = function (id) {
        this.dataDomMap = {};
        this.id = id;
        this.clickHook;
    };
    menu.prototype = {
        constructor: menu,
        loadData: function (data) {
            this.data = data;
            this.render();
            return this;
        },
        render: function () {
            $("#" + this.id).append(this.makeUl(this.data, true));
        },
        makeUl: function (data, show, parentText, field, table) {
            var rootUl = $("<ul></ul>");
            for (var i in data) {
                var text = data[i].text;
                var li = $("<li></li>");
                var leaf;
                if (data[i].type === "input") {
                    leaf = $("<input type='text' class='li-input-text' placeholder='" + data[i].placeholder + "' />");
                    li.append(leaf);
                    leaf.attr("data-text", text);
                    leaf.blur({hook: this.clickHook, a: leaf}, function (e) {
                        e.data.hook(e.data.a);
                    });
                    var ot = data[i].otype;
                    if (ot === 4 || ot === 6 || ot === 8 || ot === 10 || ot === 12 || ot === 14 || ot === 16 || ot === 20 || ot === 21) {
                        leaf.addClass("date-item");
                        leaf.click(calendarShow);
                    }
                } else {
                    leaf = $("<a>" + text + "</a>");
                    li.append(leaf);
                    var hasChildren = data[i].children;
                    leaf.click({hook: this.clickHook, hasChildren: hasChildren, a: leaf}, function (e) {
                        var ul = $(this).parent().find("ul:first");
                        if (ul.length > 0) {
                            if (ul.css("display") === "none") {
                                $(this).parents("li").each(function () {
                                    $(this).find("ul:first").slideDown();
                                });
                            } else {
                                $(this).parent().find("ul:first").slideUp();
                            }
                        }
                        if (e.data.hook && !e.data.hasChildren)
                            e.data.hook(e.data.a);
                    });
                    if (hasChildren) {
                        li.append(this.makeUl(data[i].children, false, text, data[i].field, data[i].table));
                    } else {
                        leaf.attr("data-text", text)
                        leaf.addClass("bg");
                        leaf.addClass("li-a");
                    }
                }
                rootUl.append(li);
                this.dataDomMap[text] = leaf;
                leaf.otype = data[i].otype;
                if (parentText) {
                    leaf.parentText = parentText;
                }
                if (field) {
                    leaf.field = field;
                }
                if (table) {
                    leaf.table = table;
                }
            }
            if (!show)
                rootUl.hide();
            return rootUl;
        },
        setClickHook: function (func) {
            this.clickHook = func;
        }
    };
    return new menu(_id);
};
var aa = function (a) {
    if (!groups.activeGroup) {
        alert("先选择一个标签组");
        return;
    }

    var text = a.text();
    if (a[0].tagName === "INPUT") {
//        var oldText = a.attr("data-text");
//        text = a.val();
//        if (text === oldText)
//            return;
//            
//        a.attr("data-text", text);
//        
//        if (groups.dataDomMap[groups.activeGroup.id][a.parentText]) {
//            var tag = groups.dataDomMap[groups.activeGroup.id][a.parentText][oldText];
//            if (tag) {
//                tag.remove();
//                delete groups.dataDomMap[groups.activeGroup.id][a.parentText][oldText];
//                if(groups.dataDomMap[groups.activeGroup.id][a.parentText].children("span").length === 1){
//                    var node = groups.dataDomMap[groups.activeGroup.id][a.parentText];
//                    node.remove();
//                    delete groups.dataDomMap[groups.activeGroup.id][a.parentText];
//                    return;
//                }
//            }
//        }
        var node = groups.dataDomMap[groups.activeGroup.id][a.parentText];
        if (node) {
            node.remove();
            delete groups.dataDomMap[groups.activeGroup.id][a.parentText];
        }
        text = a.val();
        if (text.length === 0)
            return;
        var oldText = a.attr("data-text");
        //console.debug(a);
        oldText.replace(text, "");
        a.attr("data-text", text + oldText);

        //oldText.replace(text, "");

    } else {
        var e = a.css("background-image");
        if (a.css("background-image").indexOf("33.png") > 0) {
            a.css("background-image", "url(image/22.png)");
        } else {
            a.css("background-image", "url(image/33.png)");
        }
    }
    groups.addTag(groups.activeGroup.id, a.parentText, text, a.field, a.table, a.otype);
};

var htagGroups = function (_id) {
    var groups = function (id) {
        this.dataDomMap = {};
        this.root = $("#" + id);
        this.self = this;
        this.activeGroup = null;
        this.clickHook = null;
        //alert(12);
    };
    groups.prototype = {
        //constructor: groups,
        loadData: function (data) {
            this.data = data;
            this.render();
            return this;
        },
        render: function () {
            this.addGroups(this.data);
        },
        addGroups: function (data) {
            for (var i = 0; i < data.length; i++) {
                var groupWrapper = $("<div></div>");
                var groupId = $("<div>" + data[i]["groupId"] + "</div>");
                var delBut = $("<input type='button' value='删除此标签组' />");
                var groupDel = $("<div></div>").append(delBut);
                var group = $("<div></div>");
                this.dataDomMap[data[i]["groupId"]] = group;
                groupId.addClass("group-id").appendTo(groupWrapper);
                group.addClass("group").appendTo(groupWrapper);
                groupDel.addClass("group-del").appendTo(groupWrapper);
                groupWrapper.addClass("group-wrapper").appendTo(this.root);
                var nodes = data[i]["nodes"];
                groupWrapper.click({gs: this.self, gid: data[i]["groupId"], dm: this.dataDomMap}, function (e) {
                    e.data.gs.activeGroup = e.data.dm[e.data.gid];
                    e.data.gs.activeGroup.id = e.data.gid;
                    $(this).css("background-color", "rgb(235, 252, 254)").siblings().css("background-color", "#fff");
                    //  A 背景初始化
                    $('#menu .bg').css("background-image", "url(image/33.png)");
                    $('#menu input').val("");

                    var pros = Object.getOwnPropertyNames(e.data.dm[e.data.gid]);
                    for (var i = 0; i < pros.length; i++) {
                        if (pros[i] === "0" || pros[i] === "length" || pros[i] === "id")
                            continue;
                        var pros1 = Object.getOwnPropertyNames(e.data.dm[e.data.gid][pros[i]]);
                        
                        for (var j = 0; j < pros1.length; j++) {
                            if (pros1[j] === "0" || pros1[j] === "length" || pros1[j] === "prevObject" || pros1[j] === "context")
                                continue;
                            var n = m.dataDomMap[pros[i]].parent().find("[data-text*='" + pros1[j] + "']").first();
                            if (!n[0]) {
                                n = m.dataDomMap[pros[i]].parent().find("[data-text*='" + pros[i] + "']").first();
                            }
                            if (n[0] && n[0].tagName) {
                                if (n[0].tagName === "INPUT") {
                                    n.val(pros1[j]);
                                } else if (n[0].tagName === "A") {
                                    n.css("background-image", "url(image/22.png)");
                                }
                            }else{
                                
                            }
                        }
                    }
                });
                if (i === 0) {
                    groupWrapper.click();
                }
                delBut.click({group: groupWrapper, gid: data[i]["groupId"], dm: this.dataDomMap}, function (e) {
                    e.data.group.remove();
                    delete e.data.dm[e.data.gid];
                });

                if (!nodes)
                    continue;
                
                for (var j = 0; j < nodes.length; j++) {
                    var node = $("<div class='group-node'></div>").appendTo(group);
                    node.attr("data-title", nodes[j]["title"]);
                    var span = $("<span class='group-node-tags'>" + nodes[j]["title"] + ": </span>").appendTo(node);
                    span.attr("data-field", nodes[j]["field"]);
                    span.attr("data-table", nodes[j]["table"]);
                    span.attr("data-type", nodes[j]["type"]);
                    
                    this.dataDomMap[data[i]["groupId"]][nodes[j]["title"]] = node;
                    for (var k = 0; k < nodes[j]["tags"].length; k++) {
                        if (k === 0) {
                            var tag = $("<span class='group-node-tags'>" + nodes[j]["tags"][k] + "</span>");
                            node.append(tag);
                            this.dataDomMap[data[i]["groupId"]][nodes[j]["title"]][nodes[j]["tags"][k]] = tag;
                        } else {
                            var tag = $("<span class='group-node-tags'>, " + nodes[j]["tags"][k] + "</span>");
                            node.append(tag);
                            this.dataDomMap[data[i]["groupId"]][nodes[j]["title"]][nodes[j]["tags"][k]] = tag;
                        }
                    }
                    this.nodeClick(node, nodes[j]["title"]);
                }
            }
        },
        addTag: function (gid, parentText, text, field, table, otype) {
            if (!parentText)
                return;

            var node = this.dataDomMap[gid][parentText];
            if (!node) {
                var div = $("<div class='group-node'></div>");
                div.attr("data-title", parentText);
                var titleSpan = $("<span>" + parentText + ": </span>").appendTo(div);
                titleSpan.attr("data-field", field);
                titleSpan.attr("data-table", table);
                titleSpan.attr("data-type", otype);
                var tag = $("<span>" + text + "</span>");
                div.append(tag);
                this.activeGroup.append(div);
                this.dataDomMap[gid][parentText] = div;
                this.dataDomMap[gid][parentText][text] = tag;
                this.nodeClick(div, parentText);
            } else {
                var tag = this.dataDomMap[gid][parentText][text];
                if (!tag) {
                    var tag = $("<span>, " + text + "</span>");
                    node.append(tag);
                    this.dataDomMap[gid][parentText][text] = tag;
                } else {
                    tag.remove();
                    delete this.dataDomMap[gid][parentText][text];
                    var third = this.dataDomMap[gid][parentText].children("span:eq(2)");
                    var second = this.dataDomMap[gid][parentText].children("span:eq(1)");
                    if (second.length > 0) {
                        second.text(second.text().replace(/, /g, ""));
                    } else if (second.length == 0) {
                        node.remove();
                        delete this.dataDomMap[gid][parentText];
                    }
                }
            }
        },
        nodeClick: function (node, title) {
            console.debug(this);
            node.click({title: title, hook: this.clickHook}, function (e) {
                e.data.hook(e.data.title);
            });
        },
        setClickHook: function (func) {
            this.clickHook = func;
        },
        addGroup: function () {
            var group = $("<div></div>");
            var length = Object.getOwnPropertyNames(this.dataDomMap).length;
            this.dataDomMap[++length] = group;
            group.addClass("group").appendTo(this.root);
        }
    }
    return new groups(_id);
};

var bb = function (title) {
    //alert(title);
    m.dataDomMap[title].click();
};


function calendarShow() {
    if ($('#demo').length === 0) {
        var html = $('<article id="demo">' +
                '<form method="post"style="position: relative;"><div><p class="date-strip">' +
                '<input type="text"class="dt" id="startTime" readOnly="true" placeholder="开始日期" onkeyup="checkEndTime()"/>' +
                '<i style="padding: 0 6px;margin-right: 2px;">-</i>' +
                '<input type="text"class="dt" id="endTime" readOnly="true" placeholder="结束日期" onkeyup="checkEndTime()"/>' +
                '</p></div><span class="cloneTrue"onclick="cloneP()">+</span><button type="button"onclick="submitVal(this)">提交</button>' +
                '</form></article>');
        $(this).parent().append(html);
        // 当前对象是否有一组日期
        //console.debug($(this));
        //console.debug($(this).val());
        if ($(this).val().length > 22) {
            var cc = $(this).val();
            var r = /\[(.+?)\]/g;
            var m = cc.match(r);
            var obj = {"times": []};
            for (var i = 0; i < m.length; i++) {
                var a = m[i].substring(m[i].indexOf("[") + 1, m[i].indexOf(","));
                var b = m[i].substring(m[i].indexOf(",") + 1, m[i].indexOf("]"))
                obj.times.push({"start_time": a, "end_time": b})
            }
            // 当前对象val()值 截取后转换为JSON类型，并以倒序循环添加到form中；
            var Time = obj.times;
            for (var n = Time.length - 1; n >= 0; n--) {
                var html = '<p class="date-strip">' +
                        '<input type="text"class="dt" id="startTime" readOnly="true" value=' + Time[n].start_time + ' placeholder="选择活动开始日期" onclick="checkEndTime()"/>' +
                        '<i style="padding: 0 6px;margin-right: 2px;">-</i>' +
                        '<input type="text"class="dt" id="endTime" readOnly="true" value=' + Time[n].end_time + ' placeholder="选择活动结束日期" onclick="checkEndTime()"/>' +
                        '</p>';
                $('#demo div').prepend(html);
            }
        }
        //formCoordinates($(this));
        createDate($('.dt'));
    } else {
        $('#demo').remove();
    }
}

function formCoordinates(input) {
    var left = input.offset().left;
    var top = input.offset().top + 60;
    $('#demo').css({left: left, top: top});
}
//  clone <p>
function cloneP() {
    // 获取最后一个P元素子级INPUT的值；
    var firstInput = $('#demo .date-strip:last').children("#startTime").val();
    var lastInput = $('#demo .date-strip:last').children("#endTime").val();
    if (firstInput != "" && lastInput != "") {
        var cloneP = $('.date-strip').eq(0).clone(true);
        cloneP.children("input").val("");
        $('#demo div').append(cloneP);
    } else {
        alert('请填写完整日期')
        return false;
    }
}
// submit
function submitVal(e) {
    var list = $('#demo div').find('p');
    var _list = [];
    var _count = true;
    var isTime = true;
    for (var i = 0; i < list.length; i++) {
        var startTime = $(list).eq(i).children('#startTime').val();
        var endTime = $(list).eq(i).children('#endTime').val();
        // 过滤掉日期长度不达标的val()值；
        if (startTime.length > 9 && endTime.length > 9) {
            list[i] = "[" + startTime + "," + endTime + "]";
            _list.push(list[i]);
        } else {
            //  1对日期只能都为空 || 都不为空；
            if (startTime.length < 1 && endTime.length < 1) {
                _count = true;
            } else {
                _count = false;
            }
        }
        // 对比开始结束时间早晚；
        endTime < startTime ? isTime = false : true;
    }
    if (_count == true && isTime == true) {
        console.log($(this))
        $(e).parents('#demo').siblings('.date-item').val(_list).blur();
        $('#demo').remove();
    } else {
        //alert(2)
        _count != true ? alert("日期格式不正确！") : alert("结束时间必须晚于开始时间！");
    }
}
//date
function createDate(event) {
    event.focus(function () {
        $("<div id='da'></div>").calendar({
            trigger: $(this),
            zIndex: 999,
            format: 'yyyy-mm-dd'
        }).appendTo($("body"));
    })
}
function checkEndTime() {
    var isTime = true
    var startTime = $("#startTime").val();
    var endTime = $("#endTime").val();
    if (endTime < startTime) {
        isTime = false;
    } else {
        isTime = true;
    }

    if (isTime != true) {
        alert("结束时间必须晚于开始时间！");
    }
}


var m, groups;
$(function () {
    m = hmenu("menu");
    m.setClickHook(aa);
    m.loadData(d);
    groups = htagGroups("right");
    groups.setClickHook(bb);
    
    if (d2.length > 0){
        count = d2.length;
        groups.loadData(d2);
    }else{
        d2 = [{"groupId": "标签组：1"}];
        groups.loadData(d2);
    }
    $('#add_TabGroups').click(function () {
        var d3 = [{
                groupId: "标签组：" + ++count
            }];
        groups.addGroups(d3)
    });
    $("#submit-prom").click(confirm_submit);
   
});