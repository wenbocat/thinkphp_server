layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-gift-manage'
        , url: '/admin/gift/getlist'
        , cols: [[
            {field: 'id', width: 50, title: 'ID', sort: true}
            , {field: 'sort', width: 60, title: '排序'}
            , {field: 'title', width: 150, title: '名称'}
            , {field: 'icon', title: '图标', width: 100, templet: '#imgTpl'}
            , {field: '', minWidth: 150, title: '动画', templet: function (d) {
                    if (d.animat_type == 1){
                        return "<img style='display: inline-block; width: auto; height: 100%;' src= '"+d.animation+"'>";
                    }else{
                        return d.animation
                    }
                }}
            , {field: '', width: 100, title: '动画类型', templet: function (d) {
                    return d.animat_type == 1?"gif":"svga";
                }}
            , {field: '', width: 100, title: '动画时长', templet: function (d) {
                    return d.duration + " ms";
                }}
            , {field: '', width: 100, title: '礼物类型', templet: function (d) {
                    return d.type == 1?"豪华礼物":"普通礼物";
                }}
            , {field: '', width: 100, title: '特殊类型', templet: function (d) {
                    var str = '无';
                    switch (d.use_type) {
                        case 1:
                            str = '全频道礼物';
                            break;
                        case 2:
                            str = "守护专属";
                            break;
                        default:
                            str = "无";
                            break;
                    }
                    return str;
                }}
            , {field: 'price', width: 100, title: '价格'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-giftlist-gift'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-gift-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定要下架/上架该礼物吗？', function(index){
                var status = obj.data.status == 0?1:0;
                admin.req({
                    url: '/admin/gift/del_post'
                    ,data: {"id":obj.data.id,"status":status}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-gift-manage");
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
                ,content: '/admin/gift/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '550px']
            });
        }
    });

    //上传图标
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
                url: '/admin/gift/signForCos'
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

    //上传动画
    var animationSrc = $('#LAY_animationSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_animationUpload'
        ,auto: true
        ,accept: 'file'
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function(index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/admin/gift/signForCos'
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
                            Key: 'animations/' + res.data.filename + choosefile.name,
                            Body: choosefile,
                        }, function (err, data) {
                            layer.closeAll('loading');
                            if (err){
                                layer.msg("上传失败", {icon: 5});
                            } else {
                                if (data.statusCode == 200) {
                                    animationSrc.val("https://" + data.Location);
                                } else {
                                    layer.msg("上传失败", {icon: 5});
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

    ////////////////////////////////  SEND-GIFT-LOG    ///////////////////////////

    table.render({
        elem: '#LAY-giftsend-manage'
        , url: '/admin/gift/getsendlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: '', width: 150, title: '赠送礼物',templet: function (d) {
                    return d.gift.title + "(" + d.gift.id + ")";
                }}
            , {field: '', width: 200, title: '赠礼用户',templet: function (d) {
                    return d.user.nick_name + "(" + d.user.id + ")";
                }}
            , {field: '', width: 200, title: '收礼用户',templet: function (d) {
                    return d.anchor.nick_name + "(" + d.anchor.id + ")";
                }}
            , {field: 'liveid', width: 150, title: '直播id'}
            , {field: 'count', width: 100, title: '数量'}
            , {field: 'spend', width: 100, title: '总价'}
            , {field: 'create_time', minWidth: 180, title: '时间'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });


    exports('gift', {})
});