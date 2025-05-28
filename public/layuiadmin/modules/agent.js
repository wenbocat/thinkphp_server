layui.define(['table', 'form', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-agent-manage'
        , url: '/admin/agent/getlist'
        , cols: [[
            {field: 'uid', width: 150, title: 'ID', sort: true}
            , {field: '', title: '账号', width: 150, templet:function (d) {
                    return d.user.account;
                }}
            , {field: '', title: '昵称', width: 200, templet:function (d) {
                    return d.user.nick_name;
                }}
            , {field: 'member_count', title: '下级数量', width: 100}
            , {field: 'profit', title: '可提现收益', width: 100}
            , {field: 'total_profit', title: '累计收益', width: 100}
            , {field: '', title: '邀请码', minWidth: 100, templet:function (d) {
                    return d.invite_code;
                }}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-agent-manage)', function(obj){
        if(obj.event === 'ban'){
            layer.confirm('确定封禁/解封用户？', function(index){
                var status = obj.data.status == 0?1:0;
                admin.req({
                    url: '/admin/agent/ban_post'
                    ,data: {"id":obj.data.id,"status":status}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-agent-manage");
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
                ,title: '编辑用户'
                ,content: '/admin/agent/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['500px', '450px']
            });
        }
    });

    form.on('submit(agent-add)', function(obj){
        //提交
        admin.req({
            url: '/admin/agent/add_post'
            ,data: obj.field
            ,success: function(res){
                if (res.code == 0){
                    layer.msg(res.msg, {icon: 1},function () {
                        window.location.reload();
                    });
                } else{
                    layer.msg(res.msg, {icon: 5});
                }
            }
        });
        return false;
    });

    /*—————————————————————————————————————————————————— 提现 ————————————————————————————————————————*/

    table.render({
        elem: '#LAY-agent-withdrawals-manage'
        , url: '/admin/agent/getWithdrawList'
        , cols: [[
            {field: 'id', width: 80, title: 'ID', sort: true}
            , {field: '', width: 180, title: '用户', templet: function (d) {
                    return d.agent.user.nick_name + "(" + d.agent.user.id + ")";
                }}
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
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-agent-withdrawalslist-withdrawals'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-agent-withdrawals-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确定删除该申请吗？', function(index){
                admin.req({
                    url: '/admin/agent/withdrawals_del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-agent-withdrawals-manage");
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
                ,content: '/admin/agent/withdrawals_edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['650px', '400px']
            });
        }
    });

    exports('agent', {})
});