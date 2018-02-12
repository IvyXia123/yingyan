var htagGroups2 = function (_id) {
    var groups = function (id) {
        this.dataDomMap = {};
        this.root = $("#" + id);
        this.self = this;
        this.activeGroup = null;
    };
    groups.prototype = {
        constructor: groups,
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
                var group = $("<div></div>");
                //Object.getOwnPropertyNames(a).length
                this.dataDomMap[data[i]["groupId"]] = group;
                groupId.addClass("group-id").appendTo(groupWrapper);
                group.addClass("group").appendTo(groupWrapper);
               
                groupWrapper.addClass("group-wrapper").appendTo(this.root);
                var nodes = data[i]["nodes"];

                if (!nodes)
                    continue;
                for (var j = 0; j < nodes.length; j++) {
                    var node = $("<div class='group-node'></div>").appendTo(group);
                    node.attr("data-title", nodes[j]["title"]);
                    var span = $("<span class='group-node-tags'>" + nodes[j]["title"] + ": </span>").appendTo(node);
                    span.attr("data-field", nodes[j]["field"]);
                    span.attr("data-table", nodes[j]["table"]);
                    span.attr("data-type", nodes[j]["otype"]);
                    //node.append($("<span class='group-node-tags' data-field='" + nodes[j]["field"] + "'>" + nodes[j]["title"] + ": </span>"));
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
                }

            }
        }    
    }
    return new groups(_id);
};