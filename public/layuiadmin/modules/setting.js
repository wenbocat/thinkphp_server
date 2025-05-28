layui.define(['form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    form.on('submit(configpri-save)', function(obj){
        //提交
        admin.req({
            url: '/admin/setting/savepri'
            ,data: obj.field
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

    form.on('submit(configpub-save)', function(obj){
        //提交
        admin.req({
            url: '/admin/setting/savepub'
            ,data: obj.field
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

    form.on('switch(switch_shortvideo_check)', function(data){
        $("input[name=switch_shortvideo_check]").val(this.checked ? '1' : '0');
    });

    form.on('switch(switch_moment_check)', function(data){
        $("input[name=switch_moment_check]").val(this.checked ? '1' : '0');
    });

    form.on('switch(switch_iap)', function(data){
        $("input[name=switch_iap]").val(this.checked ? '1' : '0');
    });

    //上传qrcode
    var qrcodeSrc = $('#LAY_qrcodeSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_qrcodeUpload'
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
                                    qrcodeSrc.val("https://" + data.Location);
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
    admin.events.qrcodePreview = function(othis){
        var src = qrcodeSrc.val();
        layer.photos({
            photos: {
                "title": "查看" //相册标题
                ,"data": [{
                    "src": src //原图地址
                }]
            }
            ,shade: 0.01
            ,closeBtn: 1
            ,anim: 5
        });
    };

    exports('setting', {})
});