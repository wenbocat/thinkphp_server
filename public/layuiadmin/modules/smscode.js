layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    table.render({
        elem: '#LAY-smscode-manage'
        , url: '/admin/smscode/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'mobile', title: '手机号', width: 200}
            , {field: 'code', title: '验证码', width: 150}
            , {field: 'status', title: '状态', width: 200, templet:function (d) {
                    switch (d.status) {
                        case 0:
                            return '<span class="layui-badge layui-bg-green">未使用</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-red">已使用</span>';
                        default:
                            return "";
                    }
                }}
            , {field: 'create_time', title: '时间', width: 200}
            , {field: 'request_ip', title: '发送IP', minWidth: 200}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-smscode-manage)', function(obj){

    });


    exports('smscode', {})
});

