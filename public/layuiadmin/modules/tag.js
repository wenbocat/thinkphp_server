layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-tag-manage'
        , url: '/admin/setting/gettags'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'title', width: 150, title: '标题'}
            , {field: '', width: 100, title: '颜色', templet: function (d) {
                    return '<span class="layui-badge" style="background-color: '+ d.color +';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>'
                }}
            , {field: 'point', width: 150, title: '分值'}
            , {field: '', minWidth: 150, title: '类型', templet: function (d) {
                    return d.type == 1?'<span class="layui-badge layui-bg-green">加分</span>':'<span class="layui-badge layui-bg-orange">减分</span>';
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-taglist-tag'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-tag-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除？', function(index){
                admin.req({
                    url: '/admin/setting/del_tag_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-tag-manage");
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
                ,title: '编辑标签'
                ,content: '/admin/setting/edit_tag?id='+obj.data.id
                ,maxmin: true
                ,area: ['460px', '400px']
            });
        }
    });


    exports('tag', {})
});