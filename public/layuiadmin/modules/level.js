layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-pointlevel-manage'
        , url: '/admin/level/getpointlevel'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'level', width: 150, title: '等级'}
            , {field: 'point', minWidth: 100, title: '经验值'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-pointlevellist-pointlevel'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-pointlevel-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除？', function(index){
                admin.req({
                    url: '/admin/level/pointlevel_del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-pointlevel-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        } else if(obj.event === 'edit'){
            layer.prompt({
                formType: 0,
                value: obj.data.point,
                title: '编辑'
            },function(val, index) {
                layer.close(index);
                if (val == ""){
                    return;
                }
                admin.req({
                    url: '/admin/level/pointlevel_edit_post'
                    ,data: {"id":obj.data.id, "point":val}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-pointlevel-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        }
    });

    table.render({
        elem: '#LAY-starlevel-manage'
        , url: '/admin/level/getstarlevel'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'level', width: 150, title: '等级'}
            , {field: 'point', minWidth: 150, title: '经验值'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-starlevellist-starlevel'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-starlevel-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除？', function(index){
                admin.req({
                    url: '/admin/level/starlevel_del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-starlevel-manage");
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
                ,title: '编辑'
                ,content: '/admin/level/starlevel_edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['450px', '300px']
            });
        }
    });


    exports('level', {})
});