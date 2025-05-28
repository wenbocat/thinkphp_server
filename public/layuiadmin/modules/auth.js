layui.define(['table', 'form', 'admin'], function(exports) {
    var $ = layui.$
        , table = layui.table
        , form = layui.form
        , admin = layui.admin;

    table.render({
        elem: '#LAY-auth-manage'
        , url: '/admin/auth/getlist'
        , cols: [[
            {field: 'uid', width: 100, title: 'ID', sort: true, fixed: 'left'}
            , {field: '', title: '昵称', width: 150, templet:function (d) {
                    return d.user.nick_name;
                }}
            , {field: 'real_name', title: '姓名', width: 150}
            , {field: 'id_num', title: '身份证号', width: 200}
            , {field: '', title: '身份证', width: 80, templet: function (d) {
                    if (d.id_card_url.length>0){
                        let images = d.id_card_url.split(",");
                        return '<img style="display: inline-block; width: auto; height: 100%;" src= "'+ images[0] +'" layadmin-event="photosPreview" value="'+d.id_card_url+'">'
                    }else {
                        return "未上传";
                    }
                }}
            , {field: 'submit_time', title: '申请时间', width: 200}
            , {field: 'check_time', title: '处理时间', width: 200}
            , {field: 'reject_reason', title: '处理说明', minWidth: 200}
            , {field: '', title: '状态', width: 80, templet: function (d) {
                    switch (d.status) {
                        case 0:
                            return '<span class="layui-badge layui-bg-orangen">待审核</span>';
                        case 1:
                            return '<span class="layui-badge layui-bg-green">已通过</span>';
                        case 2:
                            return '<span class="layui-badge layui-bg-red">已驳回</span>';
                        default:
                            return "";
                    }
                }}
            , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-authlist-operation'}
        ]]
        , page: true
        , limit: 20
        , height: 'full-140'
        , text: '对不起，加载出现异常！'
        , even: true
    });

    //监听工具条
    table.on('tool(LAY-auth-manage)', function(obj){
        if(obj.event === 'check_ok'){
            layer.prompt({
                formType: 2,
                value: '资料确认无误',
                title: '审核通过',
                area: ['300px', '100px'] //自定义文本域宽高
            }, function(value, index, elem){
                admin.req({
                    url: '/admin/auth/check'
                    ,data: {'uid':obj.data.uid,'status':1,'reject_reason':value}
                    ,success: function(res){
                        if (res.code == 0){
                            layer.close(index);
                            layer.msg(res.msg, {icon: 1});
                            table.reload('LAY-auth-manage');
                        }
                    }
                });
            });
        }else if(obj.event === 'check_reject'){
            layer.prompt({
                formType: 2,
                value: '',
                title: '请输入驳回原因',
                area: ['300px', '100px'] //自定义文本域宽高
            }, function(value, index, elem){
                admin.req({
                    url: '/admin/auth/check'
                    ,data: {'uid':obj.data.uid,'status':2,'reject_reason':value}
                    ,success: function(res){
                        if (res.code == 0){
                            layer.close(index);
                            layer.msg(res.msg, {icon: 1});
                            table.reload('LAY-auth-manage');
                        }
                    }
                });
            });
        }
    });

    //查看照片
    admin.events.photosPreview = function(othis){
        let urls = othis.attr("value");
        let urlarr = new Array();
        $.each(urls.split(","),function (i,val) {
            urlarr.push({"src":val});
        });
        layer.photos({
            photos: {
                "title": "查看照片" //相册标题
                ,"data": urlarr
            }
            ,shade: 0.01
            ,closeBtn: 1
            ,anim: 5
        });
    };

    exports('auth', {})
});

