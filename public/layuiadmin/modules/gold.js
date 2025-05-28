layui.define(['table', 'form', 'admin', 'upload'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , upload = layui.upload
        , admin = layui.admin;

    //
    table.render({
        elem: '#LAY-gold-manage'
        , url: '/admin/gold/getlist'
        , cols: [[
            {field: 'id', width: 100, title: 'ID', sort: true}
            , {field: 'gold', width: 150, title: '数量'}
            , {field: 'gold_added', width: 150, title: '赠送数量'}
            , {field: 'price', minWidth: 100, title: '价格/元'}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-goldlist-gold'}
        ]]
        , page: false
        , limit: 0
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-gold-manage)', function(obj){
        if(obj.event === 'del'){
            layer.confirm('确认删除？', function(index){
                var status = obj.data.status == 0?1:0;
                admin.req({
                    url: '/admin/gold/del_post'
                    ,data: {"id":obj.data.id}
                    ,success(res){
                        if (res.code == 0){
                            table.reload("LAY-gold-manage");
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
                ,content: '/admin/gold/edit?id='+obj.data.id
                ,maxmin: true
                ,area: ['450px', '300px']
            });
        }
    });


    exports('gold', {})
});