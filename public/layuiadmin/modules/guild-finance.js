layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table;

    table.render({
        elem: '#LAY-guild-profit-manage'
        , url: '/guild/finance/getprofitlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'diamond', width: 100, title: '钻石'}
            , {field: 'content', minWidth: 200, title: '说明'}
            , {field: 'create_time', width: 180, title: '时间'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });


    table.render({
        elem: '#LAY-guild-withdrawals-manage'
        , url: '/guild/finance/getWithdrawList'
        , cols: [[
            {field: 'id', width: 80, title: 'ID', sort: true}
            , {field: 'diamond', width: 110, title: '扣除钻石'}
            , {field: 'cash', width: 110, title: '提现金额/元'}
            , {field: 'alipay_account', width: 200, title: '支付宝账号'}
            , {field: 'alipay_name', width: 150, title: '支付宝姓名'}
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
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });


    exports('guild-finance', {})
});