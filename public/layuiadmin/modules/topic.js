layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-topic-manage'
        , url: '/admin/topic/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'title', width: 150, title: '标题'}
            , {field: '', title: '背景图', width: 80, templet: function (d) {
                    if (d.back_img_url){
                        return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ d.back_img_url +'">'
                    }else {
                        return "无";
                    }
                }}
            , {field: 'desc', width: 250, title: '简介'}
            , {field: 'used_times', width: 150, title: '参与人次'}
            , {field: '', minWidth: 150, title: '状态', templet: function (d) {
                    return d.status == 1?'<span class="layui-badge layui-bg-green">展示</span>':'<span class="layui-badge layui-bg-orange">隐藏</span>';
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-topiclist-topic'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-topic-manage)', function(obj){
        if(obj.event === 'del'){
            var status = obj.data.status == 1?0:1;
            var tip = obj.data.status == 1?"确认隐藏话题？":"确认显示话题？";
            layer.confirm(tip, function(index){
                admin.req({
                    url: '/admin/topic/del_post'
                    ,data: {"id":obj.data.id,'status':status}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-topic-manage");
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
                ,title: '编辑标签'
                ,content: '/admin/topic/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['660px', '400px']
            });
        }
    });

    //上传头像
    var back_img_url_Src = $('#LAY_back_img_url_Src');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_back_img_url_Upload'
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
                                    back_img_url_Src.val("https://" + data.Location);
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
    admin.events.back_img_url_Preview = function(othis){
        var src = back_img_url_Src.val();
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


    exports('topic', {})
});