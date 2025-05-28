layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-order-manage'
        , url: '/admin/order/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'order_no', title: '订单号', width: 180}
            , {field: '', title: '用户', width: 150, templet: function (d) {
                    return d.user.nick_name + "(" + d.user.id + ")";
                }}
            , {field: '', width: 120, title: '订单类型', templet: function (d) {
                    switch (d.type) {
                        case 0:
                            return "金币充值";
                        case 1:
                            return "开通贵族-vip" + d.vip_level;
                        default:
                            return "其他";
                    }
                }}
            , {field: 'amount', title: '订单金额/元', width: 110}
            , {field: 'pay_amount', title: '应付金额/元', width: 110}
            , {field: 'gold', title: '购买金币', width: 90}
            , {field: 'gold_added', title: '赠送金币', width: 90}
            , {field: 'out_trade_no', minWidth: 160, title: '支付单号'}
            , {field: 'pay_channel', width: 90, title: '支付渠道', templet: function (d) {
                    switch (d.pay_channel) {
                        case 1:
                            return "微信支付";
                        case 2:
                            return "支付宝";
                        case 3:
                            return "Apple PayY";
                        case 5:
                            return "管理员";
                        default:
                            return "其他";
                    }
                }}
            , {field: 'create_time', width: 170, title: '下单时间'}
            , {field: 'pay_status', width: 100, title: '支付状态', templet: function (d) {
                    switch (d.pay_status) {
                        case 0:
                            return '<span class="layui-badge layui-bg-orange">等待支付</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-green">支付成功</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-black">支付取消</span>';
                        case 3:
                            return '<span class="layui-badge layui-bg-red">支付失败</span>';
                        default:
                            return "";
                    }
                }}
            , {title: '操作', width: 200, align: 'center', fixed: 'right', toolbar: '#table-orderlist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-order-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定删除订单？', function(index){
                admin.req({
                    url: '/admin/order/del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-order-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'operation'){
            layer.open({
                type: 2
                ,title: '设为已支付'
                ,content: '/admin/order/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['500px', '400px']
            });
        }
    });

    form.on('submit(order-add)', function(obj){
        //提交
        admin.req({
            url: '/admin/order/add_post'
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

    exports('order', {})
});