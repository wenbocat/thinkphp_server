layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    // 列表
    table.render({
        elem: '#LAY-recharge-manage'
        , url: '/guild/salesman/rechargeList'
        , toolbar: '#toolbar' //开启头部工具栏，并为其绑定左侧模板
        , defaultToolbar: ['filter', 'exports', 'print']
        , totalRow: true //开启合计行
        , cols: [[
            {field: 'id', title: 'ID', sort: true, fixed: 'left', totalRowText: '合计：'}
            , {field: 'order_no', title: '订单号'}
            , {field: 'amount', title: '应付金额', totalRow: '{{ parseInt(d.TOTAL_NUMS) }} 元'}
            , {field: 'pay_amount', title: '实付金额', totalRow: '{{ parseInt(d.TOTAL_NUMS) }} 元'}
            , {field: 'gold_added', title: '赠送金币'}
            , {field: 'out_trade_no', title: '外部单号'}
            , {field: '', title: '支付通道', templet: function (d) {
                    switch (d.pay_channel) {
                        case 1:
                            return '<span class="layui-badge layui-bg-green">微信</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-green">支付宝</span>';
                        case 3:
                            return '<span class="layui-badge layui-bg-green">苹果支付</span>';
                        case 4:
                            return '<span class="layui-badge layui-bg-green">其它</span>';
                        case 5:
                            return '<span class="layui-badge layui-bg-green">人工</span>';
                        default:
                            return "";
                    }
                }}
            , {field: '', title: '支付状态', templet: function (d) {
                    switch (d.pay_status) {
                        case 0:
                            return '<span class="layui-badge layui-bg-red">待支付</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-green">支付成功</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-green">支付取消</span>';
                        case 3:
                            return '<span class="layui-badge layui-bg-green">支付失败</span>';
                        default:
                            return "";
                    }
                }}
            , {field: 'nick_name', title: '业务员'}
            , {field: 'create_time', title: '创建时间'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-180'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //头工具栏事件
    table.on('toolbar(LAY-recharge-manage)', function(obj){
        switch(obj.event){
            case 'add':
                layer.open({
                    type: 2
                    ,title: '添加'
                    ,content: '/admin/recharge/add'
                    ,area: ['800px', '700px']
                });
            break;
        };
    });

    //监听工具条
    table.on('tool(LAY-recharge-manage)', function(obj){
        switch(obj.event){
            case 'recharge':
            break;
        };
    });

    exports('guild-recharge', {})
});