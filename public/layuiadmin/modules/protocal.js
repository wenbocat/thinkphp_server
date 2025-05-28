layui.define(['table', 'form', 'upload', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    table.render({
        elem: '#LAY-protocal-manage'
        , url: '/admin/protocal/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '标题', minWidth: 150, templet: function (d) {
                    return d.title;
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-protocallist-webprotocal'}
        ]]
        , page: false
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-protocal-manage)', function(obj){
        if(obj.event === 'edit'){
            var index = layer.open({
                type: 2
                ,title: '协议管理'
                ,content: '/admin/protocal/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['600px', '550px']
            });
            layer.full(index);
        }
    });

    exports('protocal', {})
});

