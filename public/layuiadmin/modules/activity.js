layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-activity-manage'
        , url: '/admin/activity/getactivitys'
        , cols: [[
            {field: 'id', title: 'ID', width: 100}
            , {field: 'title', title: '标题', minWidth: 200}
            , {field: '', title: '封面', width: 100, templet: function (d) {
                    return '<img style="display: inline-block; width: auto; height: 100%;" layadmin-event="photosPreview" src="'+d.thumb_url+'"  value="'+d.thumb_url+'">';
                }}
            , {field: '', title: '类别', width: 100, templet: function (d) {
                    switch (d.type) {
                        case 1:
                            return "<span class=\"layui-badge layui-bg-green\">活动</span>";
                        case 2:
                            return "<span class=\"layui-badge layui-bg-red\">公告</span>";
                        default:
                            break;
                    }
                }}
            , {field: 'start_time', title: '开始时间', width: 200}
            , {field: 'end_time', title: '结束时间', width: 200}
            , {title: '操作', width: 250, align: 'center', fixed: 'right', toolbar: '#table-activitylist-activity'}
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
    table.on('tool(LAY-activity-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定要删除该条目吗？', function(index){
                admin.req({
                    url: '/admin/activity/del_post'
                    ,data: {"activityid":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-activity-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'edit'){
            var layerForm = layer.open({
                type: 2
                ,title: '编辑'
                ,content: '/admin/activity/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '650px']
            });
            layer.full(layerForm);
        }
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
                url: '/admin/activity/signForCos'
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


    exports('activity', {})
});