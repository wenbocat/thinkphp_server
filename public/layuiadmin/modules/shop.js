layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    table.render({
        elem: '#LAY-shop-manage'
        , url: '/admin/shop/getShopList'
        , cols: [[
            {field: 'id', width: 150, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '店主', width: 150, templet: function (d) {
                    return d.user.nick_name;
                }}
            , {field: 'profit', width: 120, title: '账户余额'}
            , {field: 'total_profit', title: '累计营收', width: 110}
            , {field: 'create_time', minWidth: 170, title: '开店时间'}
            , {title: '操作', width: 200, align: 'center', fixed: 'right', toolbar: '#table-shoplist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-shop-manage)', function(obj){
        if(obj.event === 'close'){
            layer.confirm('确定封禁店铺？', function(index){
                admin.req({
                    url: '/admin/shop/close_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-shop-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'open'){
            layer.confirm('确定解封店铺？', function(index){
                admin.req({
                    url: '/admin/shop/open_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-shop-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        }
    });


    /* ———————————————————————————————————————————————————————————————————————————————————————————————————————————————————— */

    table.render({
        elem: '#LAY-goods-manage'
        , url: '/admin/shop/getGoodsList'
        , cols: [[
            {field: 'id', width: 50, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '店铺', width: 200, templet: function (d) {
                    return d.shop.user.nick_name + "("+ d.shop.user.id +")";
                }}
            , {field: 'title', width: 200, title: '商品名'}
            , {field: '', title: '封面', width: 80, templet: function (d) {
                    if (d.thumb_urls.length>0){
                        var images = d.thumb_urls.split(",");
                        return '<img style="display: inline-block; width: auto; height: 100%;" src= "'+ images[0] +'" layadmin-event="photosPreview" value="'+d.thumb_urls+'">'
                    }else {
                        return "暂未上传";
                    }
                }}
            , {field: 'desc', width: 200, title: '详情'}
            , {field: '', title: '图片', width: 80, templet: function (d) {
                    if (d.desc_img_urls.length>0){
                        var images = d.desc_img_urls.split(",");
                        return '<img style="display: inline-block; width: auto; height: 100%;" src= "'+ images[0] +'" layadmin-event="photosPreview" value="'+d.desc_img_urls+'">'
                    }else {
                        return "暂未上传";
                    }
                }}
            , {field: '', title: '分类', width: 100, templet: function (d) {
                    return d.category.title;
                }}
            , {field: 'delivery', width: 100, title: '发货周期'}
            , {field: 'freight', title: '运费', width: 110}
            , {field: 'price', title: '价格', width: 110}
            , {field: 'sale_count', title: '销量', width: 110}
            , {field: 'create_time', minWidth: 170, title: '发布时间'}
            , {title: '操作', width: 100, align: 'center', fixed: 'right', toolbar: '#table-goodslist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-goods-manage)', function(obj){
        if(obj.event === 'check'){
            layer.open({
                title:"审核商品"
                ,btn: ['通过', '拒绝']
                ,yes: function(index, layero){
                    setGoodsStatus(obj.data.id,1);
                }
                ,btn2: function(index, layero){
                    setGoodsStatus(obj.data.id,3);
                }
                ,cancel: function(){
                }
            });
        } else if(obj.event === 'sale_on'){
            layer.confirm('确定上架该商品？', function(index){
                setGoodsStatus(obj.data.id,1);
            });
        } else if(obj.event === 'sale_off'){
            layer.confirm('确定下架该商品？', function(index){
                setGoodsStatus(obj.data.id,2);
            });
        }
    });

    function setGoodsStatus(goodsid,status){
        admin.req({
            url: '/admin/shop/setGoodsStatus_post'
            ,data: {"id":goodsid,"status":status}
            ,success(res){
                if (res.code == 0){
                    table.reload("LAY-goods-manage");
                    layer.msg(res.msg, {icon:1});
                } else{
                    layer.msg(res.msg, {icon:5});
                }
            }
        });
    }

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

    /* —————————————————————————————————————————————————————————————————— 订单 ————————————————————————————————————————————————— */

    table.render({
        elem: '#LAY-order-manage'
        , url: '/admin/shop/getorderlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'order_no', title: '订单号', width: 250}
            , {field: '', title: '店铺', width: 150, templet: function (d) {
                    return d.shop.nick_name + "(" + d.shop.id + ")";
                }}
            , {field: '', title: '买家', width: 150, templet: function (d) {
                    return d.user.nick_name + "(" + d.user.id + ")";
                }}
            , {field: 'total_price', title: '订单金额/元', width: 110}
            , {field: '', title: '支付金额/元', width: 110, templet: function (d) {
                    if (d.status == 1 || d.status == 3){
                        if (d.pay_type == 1){
                            return d.total_price;
                        }else{
                            return d.pay_amount;
                        }
                    }
                    return "";
                }}
            , {field: 'out_trade_no', minWidth: 250, title: '支付单号', templet: function (d) {
                    if (d.status == 1 || d.status == 3){
                        if (d.pay_type == 1){
                            return d.parent.pay_no;
                        }else{
                            return d.pay_no;
                        }
                    }
                    return "";
                }}
            , {field: 'pay_channel', width: 90, title: '支付渠道', templet: function (d) {
                    if (d.status == 1 || d.status == 3) {
                        switch (d.pay_channel) {
                            case 1:
                                return "微信支付";
                            case 2:
                                return "支付宝";
                            case 3:
                                return "Apple PayY";
                            default:
                                return "其他";
                        }
                    }
                    return '';
                }}
            , {field: 'create_time', width: 170, title: '下单时间'}
            , {field: '', width: 100, title: '支付状态', templet: function (d) {
                    switch (d.status) {
                        case 0:
                            return '<span class="layui-badge layui-bg-orange">等待支付</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-green">支付成功</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-black">订单关闭</span>';
                        case 3:
                            return '<span class="layui-badge layui-bg-red">订单完成</span>';
                        default:
                            return "";
                    }
                }}
            , {title: '操作', width: 120, align: 'center', fixed: 'right', toolbar: '#table-orderlist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-order-manage)', function(obj){
        if(obj.event === 'operation'){
            layer.open({
                type: 2
                ,title: '设为已支付'
                ,content: '/admin/shop/order_edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['500px', '400px']
            });
        }
    });

    /* —————————————————————————————————————————————————————————————————— 提现 ————————————————————————————————————————————————— */

    table.render({
        elem: '#LAY-shop-withdrawals-manage'
        , url: '/admin/shop/getWithdrawList'
        , cols: [[
            {field: 'id', width: 80, title: 'ID', sort: true}
            , {field: '', width: 180, title: '用户', templet: function (d) {
                    return d.shop.user.nick_name + "(" + d.shop.user.id + ")";
                }}
            , {field: 'apply_cash', width: 110, title: '提现金额/元'}
            , {field: 'commission_cash', width: 110, title: '手续费/元'}
            , {field: 'trade_cash', width: 110, title: '到账金额/元'}
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
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-shop-withdrawalslist-withdrawals'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-shop-withdrawals-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定删除该申请吗？', function(index){
                admin.req({
                    url: '/admin/shop/withdrawals_del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-shop-withdrawals-manage");
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
                ,content: '/admin/shop/withdrawals_edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '400px']
            });
        }
    });

    exports('shop', {})
});