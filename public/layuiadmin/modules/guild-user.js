layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-user-manage'
        , url: '/guild/user/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'account', title: '账号', width: 150}
            , {field: 'nick_name', title: '昵称', minWidth: 150, templet: function (d) {
                    return decodeURI(d.nick_name);
                }}
            , {field: '', title: '头像', width: 80, templet: function (d) {
                    if (d.avatar){
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ d.avatar +'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: '', title: '照片', width: 80, templet: function (d) {
                    if (d.profile.photos){
                        var photoArr = d.profile.photos.split(',');
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ photoArr[0] +'" layadmin-event="photosPreview" value="'+d.profile.photos+'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: 'anchor_level', title: '主播星级', width: 100}
            , {field: 'gender', width: 80, title: '性别', templet: function (d) {
                    return d.profile.gender?"男":"女";
                }}
            , {field: 'gold', width: 80, title: '金币'}
            , {field: 'diamond', width: 120, title: '钻石'}
            , {field: 'online_status', width: 100, title: '在线状态', templet: function (d) {
                    switch (d.online_status) {
                        case 1:
                            return '<span class="layui-badge layui-bg-green">在线</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-orange">暂离</span>';
                        case 3:
                            return '<span class="layui-badge layui-bg-red">通话中</span>';
                        case 9:
                            return '<span class="layui-badge layui-bg-black">离线</span>';
                        default:
                            return "";
                    }
                }}
            , {field: '', width: 100, title: '分红比例', templet: function (d) {
                    return d.sharing_ratio + "%";
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-userlist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-180'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-user-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定解约用户？', function(index){
                admin.req({
                    url: '/guild/user/del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-user-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'edit'){
            layer.prompt({
                formType: 0,
                value: obj.data.sharing_ratio,
                title: '分红比例',
            }, function(value, index, elem){
                admin.req({
                    url: '/guild/user/setSharingRatio_post'
                    ,data: {'id':obj.data.id,'sharing_ratio':value}
                    ,success: function(res){
                        if (res.code == 0){
                            layer.close(index);
                            layer.msg(res.msg, {icon: 1});
                            table.reload('LAY-user-manage');
                        }
                    }
                });
            });
        }
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

    /* ———————————————————————————————————————————————————————————————————————————————————————————————————————————————————— */

    table.render({
        elem: '#LAY-apply-manage'
        , url: '/guild/user/getApplyList'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '账号', width: 150, templet: function (d) {
                    return d.user.account;
                }}
            , {field: 'nick_name', title: '昵称', minWidth: 150, templet: function (d) {
                    return decodeURI(d.user.nick_name);
                }}
            , {field: '', title: '头像', width: 60, templet: function (d) {
                    return "<img style='display: inline-block; width: 100%; height: 100%;' src= "+ d.user.avatar +" layadmin-event='photosPreview' value='"+ d.user.avatar +"'>";
                }}
            , {field: '', title: '照片', width: 120, templet: function (d) {
                    if (d.user.profile.photos && d.user.profile.photos.length>0){
                        var photoArr = d.user.photos.split(',');
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ photoArr[0] +'" layadmin-event="photosPreview" value="'+d.user.photos+'">'
                    }else {
                        return "暂未上传";
                    }
                }}
            , {field: '', title: '主播星级', width: 100, templet: function (d) {
                    return d.user.anchor_level;
                }}
            , {field: '', width: 80, title: '性别', templet: function (d) {
                    return d.user.profile.gender?"男":"女";
                }}
            , {field: '', width: 80, title: '状态', templet: function (d) {
                    switch (d.status) {
                        case 0:
                            return '<span class="layui-badge layui-bg-orange">待审核</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-green">已通过</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-red">已拒绝</span>';
                        default:
                            return "";
                    }
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-applylist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-180'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-apply-manage)', function(obj){
        if(obj.event === 'check_ok'){
            layer.confirm('确定接受该用户加入公会？', function(index){
                admin.req({
                    url: '/guild/user/checkApply'
                    ,data: {"id":obj.data.id,'status':1}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-apply-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'check_reject'){
            layer.confirm('确定拒绝该用户加入公会？', function(index){
                admin.req({
                    url: '/guild/user/checkApply'
                    ,data: {"id":obj.data.id,'status':2}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-apply-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        }
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

    exports('guild-user', {})
});