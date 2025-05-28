layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-withdrawals-manage'
        , url: '/admin/withdrawals/getlist'
        , cols: [[
            {field: 'id', width: 80, title: 'ID', sort: true}
            , {field: '', width: 180, title: '用户', templet: function (d) {
                    return d.anchor.nick_name + "(" + d.anchor.id + ")";
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
                    url: '/admin/withdrawals/del_post'
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
                ,content: '/admin/withdrawals/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '400px']
            });
        }
    });


    exports('withdrawals', {})
});