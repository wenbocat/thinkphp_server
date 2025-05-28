layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table;

    table.render({
        elem: '#LAY-profit-manage'
        , url: '/admin/profit/getprofitlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: '', width: 200, title: '用户',templet: function (d) {
                    return d.user.nick_name + "(" + d.user.id + ")";
                }}
            , {field: 'coin_count', width: 100, title: '金币/钻石'}
            , {field: 'content', minWidth: 200, title: '说明'}
            , {field: '', width: 100, title: '类别', templet: function (d) {
                    switch (d.type) {
                        case 0:
                            return '<span class="layui-badge layui-bg-red">支出</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-green">收入</span>';
                        default:
                            return "";
                    }
                }}
            , {field: 'create_time', width: 180, title: '时间'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });


    exports('profit', {})
});