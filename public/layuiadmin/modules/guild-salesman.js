layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-salesman-manage'
        , url: '/guild/salesman/getlist'
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
            , {field: 'invite_code', title: '邀请码'}
            , {field: 'invit_num', title: '邀请人数'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-180'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //头工具栏事件
    table.on('toolbar(LAY-salesman-manage)', function(obj){
        switch(obj.event){
            case 'add':
                layer.open({
                    type: 2
                    ,title: '添加'
                    ,content: '/guild/salesman/add'
                    ,area: ['800px', '700px']
                });
            break;
        };
    });

    //监听工具条
    table.on('tool(LAY-salesman-manage)', function(obj){
        switch(obj.event){
            case 'invit':
            break;
        };
    });

    var index = parent.layer.getFrameIndex(window.name);
    form.on('submit(salesman-add)', function(obj){
        var data = obj.field;
        //提交
        admin.req({
          url: '/guild/salesman/add_post'
          ,data: data
          ,success: function(res){
            if (res.code == 0){
                parent.layer.close(index);
                parent.layer.msg(res.msg, {icon: 1});
                parent.window.location.reload();
            }
          }
        });
        return false;
    });

    //上传头像
    var avatarSrc = $('#LAY_avatarSrc');
    upload.render({
        url: '/guild/index/noOperation'
        ,elem: '#LAY_avatarUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function(index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/guild/user/signForCos'
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
        url: '/guild/index/noOperation'
        ,elem: '#LAY_coverImgUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function (index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/guild/user/signForCos'
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
        var src = coverImgSrc.val();photos
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

    exports('guild-salesman', {})
});