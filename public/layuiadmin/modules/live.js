layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-live-manage'
        , url: '/admin/live/getlives'
        , cols: [[
            {field: '', title: '主播', width: 200, templet:function (d) {
                    return d.anchor.nick_name + "(" + d.anchor.id + ")";
                }}
            , {field: 'liveid', width: 200, title: '直播编号'}
            , {field: 'title', title: '标题', width: 200}
            , {field: '', title: '封面', width: 100, templet: function (d) {
                    return '<img style="display: inline-block; width: auto; height: 100%;" layadmin-event="photosPreview" src="'+d.thumb+'"  value="'+d.thumb+'">';
                }}
            , {field: '', title: '分类', width: 150, templet:function (d) {
                    return d.category.title;
                }}
            , {field: '', title: '播流地址', minWidth: 200, templet:function (d) {
                    return '<a target="_blank" href="'+ d.pull_url +'">'+ d.pull_url +'</a>'
                }}
            , {field: 'start_time', title: '开播时间', width: 200}
            , {field: 'gift_profit', width: 100, title: '直播收益'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-livelist-live'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    table.render({
        elem: '#LAY-livelogs-manage'
        , url: '/admin/live/getlogs'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: '', title: '主播', width: 200, templet:function (d) {
                    return d.anchor.nick_name + "(" + d.anchor.id + ")";
                }}
            , {field: 'liveid', width: 200, title: '直播编号'}
            , {field: 'title', title: '标题', width: 200}
            , {field: '', title: '分类', width: 150, templet:function (d) {
                    return d.category.title;
                }}
            , {field: 'start_time', title: '开播时间', width: 200}
            , {field: 'end_time', width: 200, title: '结束时间'}
            , {field: '', title: '直播时长', width: 150, templet:function (d) {
                    return formatSeconds(d.end_stamp - d.start_stamp);
                }}
            , {field: 'gift_profit', minWidth: 100, title: '直播收益'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
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

    //监听工具条
    table.on('tool(LAY-live-manage)', function(obj){
        if(obj.event === 'stop'){
            layer.confirm('确定要结束该直播吗？', function(index){
                admin.req({
                    url: '/admin/live/stop_post'
                    ,data: {"liveid":obj.data.liveid}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-live-manage");
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
                ,title: '编辑'
                ,content: '/admin/live/live_edit?liveid='+obj.data.liveid
                ,maxmin: true
                ,area: ['650px', '350px']
            });
        }
    });


    table.render({
        elem: '#LAY-livecategory-manage'
        , url: '/admin/live/getcategorys'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            ,{field: 'sort', width: 100, title: '排序', sort: true}
            , {field: '', title: '图标', width: 100, templet: function (d) {
                    return '<img style="display: inline-block; width: auto; height: 100%;" layadmin-event="photosPreview" src="'+d.icon+'"  value="'+d.icon+'">';
                }}
            , {field: 'title', title: '名称', minWidth: 200}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-livecategorylist-livecategory'}
        ]]
        , page: false
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //上传图片
    var iconSrc = $('#LAY_iconImgSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_iconImgUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function(index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/admin/live/signForCos'
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
                                console.log(err);
                                layer.msg("上传失败", {icon: 5});
                            } else {
                                if (data.statusCode == 200) {
                                    iconSrc.val("https://" + data.Location);
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

    //查看图标
    admin.events.coverImgPreview = function(othis){
        var src = iconSrc.val();
        layer.photos({
            photos: {
                "title": "查看图标" //相册标题
                ,"data": [{
                    "src": src //原图地址
                }]
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
        return axios.post('/admin/live/signForVod').then(function (response) {
            return response.data.data.sign;
        })
    }

    //监听工具条
    table.on('tool(LAY-livecategory-manage)', function(obj){
        if(obj.event === 'hide'){
            admin.req({
                url: '/admin/live/category_hide_post'
                ,data: {"id":obj.data.id}
                ,success(res){
                    if (res.code == 0){
                        table.reload("LAY-livecategory-manage");
                        layer.msg(res.msg, {icon:1});
                    } else{
                        layer.msg(res.msg, {icon:5});
                    }
                }
            });
        }else if(obj.event === 'show'){
            admin.req({
                url: '/admin/live/category_show_post'
                ,data: {"id":obj.data.id}
                ,success(res){
                    if (res.code == 0){
                        table.reload("LAY-livecategory-manage");
                        layer.msg(res.msg, {icon:1});
                    } else{
                        layer.msg(res.msg, {icon:5});
                    }
                }
            });
        } else if(obj.event === 'edit'){
            layer.open({
                type: 2
                ,title: '编辑'
                ,content: '/admin/live/category_edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '350px']
            });
        }
    });


    exports('live', {})
});

function formatSeconds(value) {
    var secondTime = parseInt(value);// 秒
    var minuteTime = 0;// 分
    var hourTime = 0;// 小时
    if(secondTime > 60) {//如果秒数大于60，将秒数转换成整数
        //获取分钟，除以60取整数，得到整数分钟
        minuteTime = parseInt(secondTime / 60);
        //获取秒数，秒数取佘，得到整数秒数
        secondTime = parseInt(secondTime % 60);
        //如果分钟大于60，将分钟转换成小时
        if(minuteTime > 60) {
            //获取小时，获取分钟除以60，得到整数小时
            hourTime = parseInt(minuteTime / 60);
            //获取小时后取佘的分，获取分钟除以60取佘的分
            minuteTime = parseInt(minuteTime % 60);
        }
    }
    var result = "" + parseInt(secondTime)>=10?parseInt(secondTime):"0"+parseInt(secondTime);

    if(minuteTime > 0) {
        result = "" + (parseInt(minuteTime)>=10?parseInt(minuteTime):"0"+parseInt(minuteTime)) + ":" + result;
    }else{
        result = "" + '00:'+result;
    }

    if(hourTime > 0) {
        result = "" + (parseInt(hourTime)>=10?parseInt(hourTime):"0"+parseInt(hourTime)) + ":" + result;
    }else{
        result = "" + '00:'+result;
    }

    return result;
}