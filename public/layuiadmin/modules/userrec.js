layui.define(['table', 'form', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-userrec-manage'
        , url: '/admin/userrec/getlist'
        , cols: [[
            {field: 'id', width: 50, title: 'ID', sort: true, fixed: 'left'}
            , {field: 'uid', title: '用户ID', width: 150}
            , {field: '', title: '用户昵称', width: 150, templet: function (d) {
                    return d.user.nick_name
                }}
            , {field: 'start_time', title: '开始时间', width: 200}
            , {field: 'end_time', title: '截止时间', width: 200}
            , {field: 'rec_weight', title: '权重', width: 100}
            , {field: '', title: '状态', minWidth: 150, templet :function (d) {
                    if (d.end_status == 1){
                        return '<span class="layui-badge layui-bg-orange">已过期</span>';
                    }
                    if (d.start_status == 0){
                        return '<span class="layui-badge layui-bg-blue">未生效</span>';
                    }
                    if (d.rec_weight == d.user.rec_weight){
                        return '<span class="layui-badge layui-bg-green">生效中</span>';
                    }
                    return '<span class="layui-badge layui-bg-gray">异常</span>';
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-userreclist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-180'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-userrec-manage)', function(obj){
        if(obj.event === 'opt'){
            layer.confirm('选择您要对当前任务作出的操作', {
                btn: ['立即生效', '立即结束', '取消']
            }, function(index, layero){
                admin.req({
                    url: '/admin/userrec/start_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-userrec-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            }, function(index){
                admin.req({
                    url: '/admin/userrec/end_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-userrec-manage");
                            layer.msg(res.msg, {icon:1});
                        } else{
                            layer.msg(res.msg, {icon:5});
                        }
                    }
                });
            });
        }
    });

    form.on('submit(userrec-add)', function(obj){
        //提交
        admin.req({
            url: '/admin/userrec/add_post'
            ,data: obj.field
            ,success: function(res){
                if (res.code == 0){
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function () {
                        window.location.reload();
                    },1200);
                } else{
                    layer.msg(res.msg, {icon: 5});
                }
            }
        });
        return false;
    });

    exports('userrec', {})
});