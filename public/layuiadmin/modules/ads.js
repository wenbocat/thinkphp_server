layui.define(['table', 'form', 'admin', 'upload', 'element'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , element = layui.element
        , upload = layui.upload
        , admin = layui.admin;

    var elem_config = ["LAY-ads-applaunch-manage",'LAY-ads-apphome-manage','LAY-ads-apphomepop-manage','LAY-ads-svideo-manage','LAY-ads-appmoments-manage'];
    var current_index = 0;
    element.on('tab(tabBrief)', function(data){
        current_index = data.index;
        table.render({
            elem: "#"+elem_config[data.index]
            , url: '/admin/ads/getlist'
            , where: {type:(data.index+1)}
            , cols: [[
                {field: 'id', width: 100, title: 'ID', sort: true}
                , {field: 'title', width: 200, title: '标题'}
                , {field: '', title: '图片', width: 100, templet: function (d) {
                        return '<img style="display: inline-block; width: auto; height: 100%;" layadmin-event="photosPreview" src="'+d.image_url+'"  value="'+d.image_url+'">';
                    }}
                , {field: 'jump_url', minWidth: 200, title: '跳转链接', templet: function (d) {
                        return '<a target="_blank" href="'+ d.jump_url +'">'+ d.jump_url +'</a>'
                    }}
                , {field: '', width: 150, title: '跳转方式', templet: function (d) {
                        return d.jump_type == 1?"内部跳转":"外部跳转";
                    }}
                , {field: '', width: 150, title: '状态', templet: function (d) {
                        return d.status == 1?'<span class="layui-badge layui-bg-green">生效中</span>':'<span class="layui-badge layui-bg-orange">未生效</span>';
                    }}
                , {field: 'create_time', width: 180, title: '添加时间'}
                , {title: '操作', width: 180, align: 'center', fixed: 'right', toolbar: '#table-adslist-ads'}
            ]]
            , page: false
            , limit: 0
            , height: 'full-140'
            , text: '对不起，加载出现异常！'
            , even: true
        });
    });
    //默认加载启动页广告
    table.render({
        elem: '#LAY-ads-applaunch-manage'
        , url: '/admin/ads/getlist'
        , where: {type:1}
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'title', width: 200, title: '标题'}
            , {field: '', title: '图片', width: 100, templet: function (d) {
                    return '<img style="display: inline-block; width: auto; height: 100%;" layadmin-event="photosPreview" src="'+d.image_url+'"  value="'+d.image_url+'">';
                }}
            , {field: 'jump_url', minWidth: 200, title: '跳转链接', templet: function (d) {
                    return '<a target="_blank" href="'+ d.jump_url +'">'+ d.jump_url +'</a>'
                }}
            , {field: '', width: 150, title: '跳转方式', templet: function (d) {
                    return d.jump_type == 1?"内部跳转":"外部跳转";
                }}
            , {field: '', width: 150, title: '状态', templet: function (d) {
                    return d.status == 1?'<span class="layui-badge layui-bg-green">生效中</span>':'<span class="layui-badge layui-bg-orange">未生效</span>';
                }}
            , {field: 'create_time', width: 180, title: '添加时间'}
            , {title: '操作', width: 180, align: 'center', fixed: 'right', toolbar: '#table-adslist-ads'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除？', function(index){
                admin.req({
                    url: '/admin/ads/appdel_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload(elem_config[current_index]);
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
                ,content: '/admin/ads/appedit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '400px']
            });
        } else if(obj.event === 'opt'){
            layer.confirm('确认设置为失效/生效状态？', function(index){
                admin.req({
                    url: '/admin/ads/opt_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload(elem_config[current_index]);
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

    //上传图标
    var imgSrc = $('#LAY_imgSrc');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_imgUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function(index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/admin/ads/signForCos'
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
                                    imgSrc.val("https://" + data.Location);
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
    admin.events.imgPreview = function(othis){
        var src = imgSrc.val();
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


    exports('ads', {})
});