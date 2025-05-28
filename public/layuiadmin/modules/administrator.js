layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-administrator-manage'
        , url: '/admin/admin/getList'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'name', width: 200, title: '名称'}
            , {field: 'account', minWidth: 200, title: '登录名'}
            , {field: '', width: 200, title: '角色',templet:function (d) {
                    return d.role.name;
                }}
            , {field: '', width: 100, title: '状态', templet: function (d) {
                    return d.status == 1?'<span class="layui-badge layui-bg-green">正常</span>':'<span class="layui-badge layui-bg-orange">封禁</span>';
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-administratorlist-administrator'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-administrator-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除该账号？', function(index){
                admin.req({
                    url: '/admin/admin/del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-administrator-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'edit'){
            var index = layer.open({
                type: 2
                ,title: '编辑角色'
                ,content: '/admin/admin/edit?id='+obj.data.id
                ,maxmin: true
            });
            layer.full(index);
        }
    });


    exports('administrator', {})
});