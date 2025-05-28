layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-guild-manage'
        , url: '/admin/guild/getlist'
        , cols: [[
            {field: 'id', width: 120, title: 'ID', sort: true}
            , {field: 'mobile', title: '账号', width: 120}
            , {field: 'name', title: '名称', minWidth: 150}
            , {field: '', title: '徽章', width: 80, templet: function (d) {
                    return '<img style="display: inline-block; width: 100%; height: 100%;" src= "'+ d.icon +'" layadmin-event="iconPreview" value= "'+ d.icon +'">';
                }}
            , {field: 'member_count', title: '成员', width: 60}
            , {field: 'diamond', title: '钻石数量', width: 100}
            , {field: 'diamond_total', title: '累计钻石', width: 100}
            , {field: 'sharing_ratio', width: 100, title: '分成比例', templet: function (d) {
                return d.sharing_ratio + "%";
                }}
            , {field: 'content', title: '公会说明', width: 200}
            , {field: 'create_time', width: 180, title: '创建日期'}
            , {field: 'last_login_time', width: 180, title: '上次登录'}
            , {field: 'last_login_ip', width: 180, title: '上次登录IP'}
            , {field: 'alipay_account', width: 180, title: '支付宝账号'}
            , {field: 'alipay_name', width: 180, title: '支付宝姓名'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-guildlist-guild'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-guild-manage)', function(obj){
        if(obj.event === 'ban'){
            var str = obj.data.status == 0 ? "解封":"封禁";
            layer.confirm('确定'+str+'公会？', function(index){
                var status = obj.data.status == 0?1:0;
                admin.req({
                    url: '/admin/guild/ban_post'
                    ,data: {"id":obj.data.id,"status":status}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-guild-manage");
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
                ,title: '编辑公会'
                ,content: '/admin/guild/edit?id='+obj.data.id
                ,maxmin: true
            });
            layer.full(index);
        }
    });

    form.on('submit(guild-add)', function(obj){
        //提交
        admin.req({
            url: '/admin/guild/add_post'
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

    form.on('submit(guild-edit)', function(obj){
        //提交
        admin.req({
            url: '/admin/guild/edit_post'
            ,data: obj.field
            ,success: function(res){
                if (res.code == 0){
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                    parent.layer.msg(res.msg, {icon: 1});
                    parent.layui.table.reload('LAY-guild-manage');
                } else{
                    layer.msg(res.msg, {icon: 5});
                }
            }
        });
        return false;
    });

    //上传头像
    var iconSrc = $('#LAY_iconSrc');
    var iconPreview = $('#LAY_iconPreview');
    upload.render({
        url: '/admin/index/noOperation'
        ,elem: '#LAY_iconUpload'
        ,auto: true
        ,choose: function (obj) {
            layer.load();
            var choosefile;
            obj.preview(function(index, file, result) {
                choosefile = file;
            });
            admin.req({
                url: '/admin/guild/signForCos'
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
                                    iconPreview.attr("value", iconSrc.val());
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
    admin.events.iconPreview = function(othis){
        var src = othis.attr("value");
        if (!src){
            src = iconSrc.val();
        }
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

    /* —————————————————————————————————————————————————— 公会提现 —————————————————————————————————————————————————————— */

    table.render({
        elem: '#LAY-withdrawals-manage'
        , url: '/admin/guild/getWithdrawList'
        , cols: [[
            {field: 'id', width: 80, title: 'ID', sort: true}
            , {field: '', width: 180, title: '公会', templet: function (d) {
                    return d.guild.name + "(" + d.guild.id + ")";
                }}
            , {field: 'diamond', title: '扣除钻石', width: 100}
            , {field: 'cash', width: 110, title: '提现金额/元'}
            , {field: 'alipay_account', width: 180, title: '支付宝账号'}
            , {field: 'alipay_name', width: 100, title: '支付宝姓名'}
            , {field: 'create_time', width: 170, title: '申请时间'}
            , {field: '', minWidth: 100, title: '状态', templet:function (d) {
                    if (d.status == 0){
                        return '<span class="layui-badge layui-bg-red">未处理</span>';
                    } else if (d.status == 1){
                        return '<span class="layui-badge layui-bg-green">已打款</span>';
                    } else if (d.status == 2){
                        return '<span class="layui-badge layui-bg-orange">已拒绝</span>'
                    } else if (d.status == 3){
                        return '<span class="layui-badge layui-bg-black">异常申请</span>'
                    }
                }}
            , {field: 'operate_time', width: 170, title: '处理时间'}
            , {field: 'trade_no', minWidth: 200, title: '转账单号'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-withdrawalslist-withdrawals'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-withdrawals-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定删除该申请吗？', function(index){
                admin.req({
                    url: '/admin/guild/withdraw_del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-withdrawals-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'edit'){
            if (obj.data.status != 0){
                layer.msg("该申请已处理完成，无需再次处理", {icon:1});
                return;
            }
            layer.open({
                type: 2
                ,title: '处理提现申请'
                ,content: '/admin/guild/withdraw_edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '400px']
            });
        }
    });

    exports('guild', {})
});