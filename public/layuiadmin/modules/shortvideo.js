layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    table.render({
        elem: '#LAY-shortvideo-manage'
        , url: '/admin/shortvideo/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '用户', width: 250, templet: function (d) {
                    return d.author.nick_name + "("+ d.uid+")";
                }}
            , {field: 'title', title: '标题', minWidth: 150, templet: function (d) {
                    return decodeURI(d.title);
                }}
            , {field: '', title: '封面', width: 80, templet: function (d) {
                    return '<img style="display: inline-block; width: auto; height: 100%;" src= "'+ d.thumb_url +'" layadmin-event="photosPreview" value="'+d.thumb_url+'">'
                }}
            , {field: '', title: '视频', width: 80, templet: function (d) {
                    return '<img style="display: inline-block; width: auto; height: 100%;" src= "'+ d.thumb_url +'" layadmin-event="videoPreview" value="'+d.play_url+'">'
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-shortvideolist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-shortvideo-manage)', function(obj){
        if(obj.event === 'del'){
            var title = obj.data.status == 1?"下架":"上架";
            layer.confirm('确定'+title+'视频？', function(index){
                var status = obj.data.status == 1?3:1;
                admin.req({
                    url: '/admin/shortvideo/del_post'
                    ,data: {"id":obj.data.id,"status":status}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-shortvideo-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'edit'){
            var index = layer.open({
                type: 2
                ,title: '短视频'
                ,content: '/admin/shortvideo/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['600px', '550px']
            });
            layer.full(index);
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

    //查看视频
    admin.events.videoPreview = function(othis){
        let video_url = othis.attr("value");
        layer.open({
            type: 1,
            title: '视频预览',
            content: "<video src='"+video_url+"' poster='' style='width: 800px; height: 400px;' controls></video>",
            area: ['800px', '450px']
        });
    };


    ///////////////////////////////////////////////  add ////////////////////////////////////////////////

    form.on('submit(shortvideo-add)', function(obj){
        var data = obj.field;
        //提交
        admin.req({
            url: '/admin/shortvideo/add_post'
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
                                Body: file
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
                                        let src = "https://" + data.Location;
                                        upImgSrc.val(src);
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

    //上传视频
    var upVideoSrc = $('#LAY_videoSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_videoUpload'
        ,auto: true
        ,acceptMime: 'video/mp4'
        ,exts: 'mp4'
        ,multiple: false
        ,choose: function (obj) {
            layer.load();
            obj.preview(function (index, file, result) {
                const tcVod = new TcVod.default({
                    getSignature: getVodSignature
                });

                const uploader = tcVod.upload({
                    videoFile: file,
                });
                uploader.on('media_progress', function(info) {
                    // console.log(info.percent) // 进度
                });
                uploader.done().then(function (doneResult) {
                    layer.closeAll('loading');
                    upVideoSrc.val(doneResult.video.url);
                }).catch(function (err) {
                    layer.closeAll('loading');
                    layer.msg("上传失败", {icon: 5});
                    console.log(err);
                });
            });
        }
    });
    //查看视频
    admin.events.upVideoPreview = function(othis){
        let video_url = upVideoSrc.val();
        layer.open({
            type: 1,
            title: '视频预览',
            content: "<video src='"+video_url+"' poster='' style='width: 800px; height: 400px;' controls></video>",
            area: ['800px', '450px']
        });
    };

    function getVodSignature() {
        return axios.post('/admin/shortvideo/signForVod').then(function (response) {
            return response.data.data.sign;
        })
    }

    ///////////////////////////////////////////////  report ////////////////////////////////////////////////

    table.render({
        elem: '#LAY-shortvideo-report-manage'
        , url: '/admin/shortvideo/getReportList'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '举报用户', width: 200, templet: function (d) {
                    return d.user.nick_name + "("+ d.uid+")";
                }}
            , {field: '', title: '短视频标题', width: 200, templet: function (d) {
                    return d.shortvideo.title;
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
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-shortvideolist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-shortvideo-report-manage)', function(obj){
        console.log(obj);
        if(obj.event === 'view'){
            var index = layer.open({
                type: 2
                ,title: '短视频'
                ,content: '/admin/shortvideo/edit?id='+obj.data.videoid
                ,maxmin: true
                ,area: ['600px', '550px']
            });
            layer.full(index);
        }
    });

    exports('shortvideo', {})
});

