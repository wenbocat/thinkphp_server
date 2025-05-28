layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-role-manage'
        , url: '/admin/role/getRoleList'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'name', width: 150, title: '名称'}
            , {field: 'remark', minWidth: 200, title: '说明'}
            , {field: '', width: 100, title: '状态', templet: function (d) {
                    return d.status == 1?'<span class="layui-badge layui-bg-green">生效中</span>':'<span class="layui-badge layui-bg-orange">已关闭</span>';
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-rolelist-role'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-role-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除？', function(index){
                admin.req({
                    url: '/admin/role/del_role_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-role-manage");
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
                ,content: '/admin/role/edit_role?id='+obj.data.id
                ,maxmin: true
            });
            layer.full(index);
        }
    });

    table.render({
        elem: '#LAY-auth-manage'
        , url: '/admin/role/getAdminAuthList/?pid='+$('#pid').val()
        , cols: [[
            {field:'sort', title:'排序', width:110, templet: '#table-list-admin-auth-sort', unresize: true}
            , {field: 'title', width: 150, title: '名称'}
            , {field: 'path', minWidth: 200, title: '路径'}
            , {field: 'icon', minWidth: 200, title: '图标'}
            , {field: '', width: 100, title: '状态', templet: function (d) {
                    return d.status == 1?'<span class="layui-badge layui-bg-green">生效中</span>':'<span class="layui-badge layui-bg-orange">已关闭</span>';
                }}
            , {title: '操作', width: 250, align: 'center', fixed: 'right', toolbar: '#table-admin-auth-handler'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-auth-manage)', function(obj){
        if(obj.event === 'check'){
            admin.req({
                url: '/admin/role/check_auth_post'
                ,data: {"id":obj.data.id}
                ,success(res){
                    if (res.code == 0){
                        table.reload("LAY-auth-manage");
                        layer.msg(res.msg, {icon:1});
                    } else{
                        layer.msg(res.msg, {icon:5});
                    }
                }
            });
        } else if(obj.event === 'edit'){
            layer.open({
                type: 2
                ,title: '编辑权限'
                ,content: '/admin/role/edit_auth?id='+obj.data.id
                ,area: ['650px', '500px']
            });
        } else if(obj.event === 'sub'){
            var index = layer.open({
                type: 2
                ,title: '下级权限'
                ,content: '/admin/role/index?pid='+obj.data.id
                ,maxmin: true
            });
            layer.full(index);
        }
    });


    exports('role', {})
});