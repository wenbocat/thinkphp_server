layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-user-manage'
        , url: '/admin/user/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'account', title: '账号', width: 150, fixed: 'left'}
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
                    if (d.profile && d.profile.photos){
                        var photoArr = d.profile.photos.split(',');
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ photoArr[0] +'" layadmin-event="photosPreview" value="'+d.profile.photos+'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: 'anchor_level', title: '主播星级', width: 100}
            , {field: 'gender', width: 80, title: '性别', templet: function (d) {
                    if (d.profile){
                        return d.profile.gender?"男":"女";
                    }else{
                        return "未知";
                    }
                }}
            , {field: 'gold', width: 80, title: '金币'}
            , {field: 'diamond', width: 80, title: '钻石'}
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
            , {field: '', width: 100, title: 'vip等级', templet: function (d) {
                    switch (d.vip_level) {
                        case 1:
                            return '<span class="layui-badge layui-bg-green">游侠</span>';
                        case 2:
                            return '<span class="layui-badge" style="background-color: #96daf5">骑士</span>';
                        case 3:
                            return '<span class="layui-badge" style="background-color: #c8aeec">公爵</span>';
                        case 4:
                            return '<span class="layui-badge" style="background-color: #f5ca96">国王</span>';
                        default:
                            return "";
                    }
                }}
            , {field: 'is_anchor', width: 100, title: '认证状态', templet: function (d) {
                    return d.is_anchor?"已认证":"未认证";
                }}
            , {field: '', width: 100, title: '分红比例', templet: function (d) {
                    return d.sharing_ratio + "%";
                }}
            , {field: '', width: 100, title: '所属公会', templet: function (d) {
                    return "";
                }}
            , {field: 'regist_time', width: 180, title: '注册时间'}
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
            layer.confirm('确定封禁/解封用户？', function(index){
                var status = obj.data.status == 0?1:0;
                admin.req({
                    url: '/admin/user/del_post'
                    ,data: {"id":obj.data.id,"status":status}
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
            layer.open({
                type: 2
                ,title: '编辑用户'
                ,content: '/admin/user/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['500px', '450px']
            });
        }
    });

    form.on('submit(user-add)', function(obj){
        var data = obj.field;
        //提交
        admin.req({
          url: '/admin/user/add_post'
          ,data: data
          ,success: function(res){
            if (res.code == 0){
                layer.msg(res.msg, {icon: 1});
                setTimeout(function () {
                    window.location.reload();
                },1200);
            } else{
                layer.msg(res.msg, {icon: 5});
            }
          }
        });
        return false;
    });

    //上传头像
    var avatarSrc = $('#LAY_avatarSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_avatarUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function(index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/admin/user/signForCos'
                ,success: function(res){
                    if(res.code == 0){
                        var cos = new COS({
                            getAuthorization: function (options, callback) {
                                var credentials = res.data.credentials;
                                callback({
                                    TmpSecretId: credentials.tmpSecretId,
                                    TmpSecretKey: credentials.tmpSecretKey,
                                    XCosSecurityToken: credentials.sessionToken,
                                    ExpiredTime: res.data.expiredTime
                                });
                            }
                        });
                        cos.putObject({
                            Bucket: res.data.bucket,
                            Region: res.data.region,
                            Key: 'images/' + res.data.filename + choosefile.name,
                            Body: choosefile,
                        }, function (err, data) {
                            layer.closeAll('loading');
                            if (err){
                                layer.msg("上传失败", {icon: 5});
                            } else {
                                if (data.statusCode == 200) {
                                    avatarSrc.val("https://" + data.Location);
                                } else {
                                    layer.msg("图片上传失败", {icon: 5});
                                }
                            }
                        });
                    } else {
                        layer.closeAll('loading');
                        layer.msg(res.msg, {icon: 5});
                    }
                }
            })
        }
    });

    //查看头像
    admin.events.avartatPreview = function(othis){
        var src = avatarSrc.val();
        layer.photos({
            photos: {
                "title": "查看头像" //相册标题
                ,"data": [{
                    "src": src //原图地址
                }]
            }
            ,shade: 0.01
            ,closeBtn: 1
            ,anim: 5
        });
    };

    //上传封面图
    var coverImgSrc = $('#LAY_coverImgSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_coverImgUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function (index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/admin/user/signForCos'
                , success: function (res) {
                    if (res.code == 0) {
                        var cos = new COS({
                            getAuthorization: function (options, callback) {
                                var credentials = res.data.credentials;
                                callback({
                                    TmpSecretId: credentials.tmpSecretId,
                                    TmpSecretKey: credentials.tmpSecretKey,
                                    XCosSecurityToken: credentials.sessionToken,
                                    ExpiredTime: res.data.expiredTime
                                });
                            }
                        });
                        cos.putObject({
                            Bucket: res.data.bucket,
                            Region: res.data.region,
                            Key: 'images/' + res.data.filename + choosefile.name,
                            Body: choosefile,
                        }, function (err, data) {
                            layer.closeAll('loading');
                            if (err){
                                layer.msg("上传失败", {icon: 5});
                            } else {
                                if (data.statusCode == 200) {
                                    coverImgSrc.val("https://" + data.Location);
                                } else {
                                    layer.msg("图片上传失败", {icon: 5});
                                }
                            }
                        });
                    } else {
                        layer.closeAll('loading');
                        layer.msg(res.msg, {icon: 5});
                    }
                }
            })
        }
    });

    //查看封面图
    admin.events.coverImgPreview = function(othis){
        var src = coverImgSrc.val();
        layer.photos({
            photos: {
                "title": "查看头像" //相册标题
                ,"data": [{
                    "src": src //原图地址
                }]
            }
            ,shade: 0.01
            ,closeBtn: 1
            ,anim: 5
        });
    };

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

    ///////////////////////////////////////////////  report ////////////////////////////////////////////////

    table.render({
        elem: '#LAY-user-report-manage'
        , url: '/admin/user/getReportList'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '举报用户', width: 200, templet: function (d) {
                    return d.user.nick_name + "("+ d.uid+")";
                }}
            , {field: '', title: '被举报用户', width: 200, templet: function (d) {
                    return d.anchor.nick_name + "("+ d.anchor.id+")";
                }}
            , {field: 'title', title: '举报理由', width: 200}
            , {field: 'content', title: '举报内容', minWidth: 200}
            , {field: '', title: '图片', width: 80, templet: function (d) {
                    if (d.img_urls && d.img_urls.length > 0){
                        let images = d.img_urls.split(",");
                        return '<img style="display: inline-block; width: auto; height: 100%;" src= "'+ images[0] +'" layadmin-event="photosPreview" value="'+d.img_urls+'">'
                    }
                    return '';
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-userlist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-user-report-manage)', function(obj){
        console.log(obj);
        if(obj.event === 'view'){
            var index = layer.open({
                type: 2
                ,title: '短视频'
                ,content: '/admin/user/edit?id='+obj.data.userid
                ,maxmin: true
                ,area: ['600px', '550px']
            });
            layer.full(index);
        }
    });

    exports('user', {})
});