layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-vip-manage'
        , url: '/admin/vip/getlist'
        , cols: [[
            {field: '', width: 150, title: '会员等级', templet:function (d) {
                    switch (d.level) {
                        case 1:
                            return "游侠";
                        case 2:
                            return "骑士";
                        case 3:
                            return "公爵";
                        case 4:
                            return "国王";
                    }
                }}
            , {field: 'price', minWidth: 100, title: '价格/元'}
            , {field: 'gold', minWidth: 150, title: '赠送金币数量'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-viplist-vip'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-vip-manage)', function(obj){
        if(obj.event === 'edit'){
            layer.open({
                type: 2
                ,title: '编辑'
                ,content: '/admin/vip/edit?level='+obj.data.level
                ,maxmin: true
                ,area: ['450px', '300px']
            });
        }
    });


    exports('vip', {})
});