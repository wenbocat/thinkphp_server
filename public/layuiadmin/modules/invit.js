layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-invit-manage'
        , url: '/admin/salesman/invitList'
        , toolbar: '#toolbar' //开启头部工具栏，并为其绑定左侧模板
        , defaultToolbar: ['filter', 'exports', 'print']
        , cols: [[
            {field: 'id', title: 'ID', sort: true, fixed: 'left'}
            , {field: 'account', title: '账号'}
            , {field: 'nick_name', title: '昵称', templet: function (d) {
                    return decodeURI(d.nick_name);
                }}
            , {field: '', title: '头像', templet: function (d) {
                    if (d.avatar){
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ d.avatar +'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: '', title: '照片', templet: function (d) {
                    if (d.profile.photos){
                        var photoArr = d.profile.photos.split(',');
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ photoArr[0] +'" layadmin-event="photosPreview" value="'+d.profile.photos+'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: 'gender', title: '性别', templet: function (d) {
                    return d.profile.gender?"男":"女";
                }}
            , {field: 'gold', title: '金币'}
            , {field: 'diamond', title: '钻石'}
            , {field: 'salesman_name', title: '业务员'}
            , {title: '操作', align: 'center', fixed: 'right', toolbar: '#table-invitlist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-180'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //头工具栏事件
    table.on('toolbar(LAY-invit-manage)', function(obj){
        switch(obj.event){
            case 'add':
                layer.open({
                    type: 2
                    ,title: '添加'
                    ,content: '/admin/invit/add'
                    ,area: ['800px', '700px']
                });
            break;
        };
    });

    //监听工具条
    table.on('tool(LAY-invit-manage)', function(obj){
        switch(obj.event){
            case 'invit':
                layer.open({
                    type: 2
                    ,title: '邀请列表'
                    ,content: '/admin/invit/invitList?agent_id='+obj.data.id
                    ,area: ['1200px', '700px']
                });
            break;
        };
    });

    //查看照片
    admin.events.photosPreview = function(othis){
        let urls = othis.attr("value");
        let urlarr = new Array();
        $.each(urls.split(","),function (i,val) {
            urlarr.push({"src":val});
        });
        layer.photos({
            photos: {
                "title": "查看照片" //相册标题
                ,"data": urlarr
            }
            ,shade: 0.01
            ,closeBtn: 1
            ,anim: 5
        });
    };

    exports('invit', {})
});