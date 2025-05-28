layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    table.render({
        elem: '#LAY-push-manage'
        , url: '/admin/push/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'touid', title: '接收用户', width: 200, templet: function (d) {
                    if (d.touid == 0){
                        return "全员";
                    }else {
                        return d.touid;
                    }
                }}
            , {field: 'title', title: '标题', width: 150}
            , {field: 'content', title: '内容', width: 200}
            , {field: '', title: '图片', width: 80, templet: function (d) {
                    if (d.image_url.length>0){
                        return '<img style="display: inline-block; width: auto; height: 100%;" layadmin-event="photosPreview" src="'+d.image_url+'"  value="'+d.image_url+'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: 'link', title: '链接地址', minWidth: 200, templet: function (d) {
                    return '<a target="_blank" href="'+ d.link +'">'+ d.link +'</a>'
                }}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-push-manage)', function(obj){

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


    ///////////////////////////////////////////////  add ////////////////////////////////////////////////

    form.on('submit(push-add)', function(obj){
        var data = obj.field;
        //提交
        admin.req({
            url: '/admin/push/add_post'
            ,data: data
            ,success: function(res){
                if (res.code == 0){
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function () {
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index); //再执行关闭
                        parent.window.location.reload();
                    },1200);
                } else{
                    layer.msg(res.msg, {icon: 5});
                }
            }
        });
        return false;
    });

    //上传图片
    var upImgSrc = $('#LAY_imageSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_imageUpload'
        ,auto: true
        ,acceptMime: 'image/jpg, image/png, image/JPEG, image/PNG'
        ,multiple: true
        ,choose: function (obj) {
            layer.load();
            let choosefileArr = new Array();
            obj.preview(function (index, file, result) {
                choosefileArr.push(file);
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
                        $.each(choosefileArr,function (index,file) {
                            cos.putObject({
                                Bucket: res.data.bucket,
                                Region: res.data.region,
                                Key: 'images/' + res.data.filename + file.name,
                                Body: file,
                                Headers: {'Pic-Operations': "{\"is_pic_info\":1,\"rules\":[{\"fileid\":\"/images_blur/"+res.data.filename + file.name+"\",\"rule\":\"imageMogr2/blur/50x25\"}]}"}
                            }, function (err, data) {
                                if (index == choosefileArr.length-1){
                                    layer.closeAll('loading');
                                }
                                if (err){
                                    console.log(err);
                                    layer.msg("上传失败", {icon: 5});
                                } else {
                                    console.log(data);
                                    if (data.statusCode == 200) {
                                        let src = upImgSrc.val();
                                        if (src.length > 0){
                                            src += ",https://" + data.Location;
                                        }else{
                                            src += "https://" + data.Location;
                                        }
                                        upImgSrc.val(src);
                                        $("#imgSrcBlur").val(upImgSrc.val().replace(/\/images\//g,"/images_blur/"));
                                    } else {
                                        layer.msg("图片上传失败", {icon: 5});
                                    }
                                }
                            });
                        });
                    } else {
                        layer.closeAll('loading');
                        layer.msg(res.msg, {icon: 5});
                    }
                }
            });
        }
    });

    //查看图片
    admin.events.imagePreview = function(othis){
        let urls = upImgSrc.val();
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


    exports('push', {})
});

