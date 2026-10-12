<?php /*a:1:{s:64:"D:\web\php\thinkphp\corp-web\www\view\kcadmin\message\index.html";i:1788666370;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>留言板管理</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/kcadmin/js/easyui/themes/default/easyui.css">
    <link rel="stylesheet" href="/static/kcadmin/css/style.css">
</head>
<body>
<div class="container-fluid p-3">
    <div class="panel-heading">留言板列表</div>
    <table id="dg" class="easyui-datagrid" style="width:100%;height:480px;"
        data-options="singleSelect:true,pagination:true,rownumbers:true,fitColumns:true">
        <thead>
            <tr>
                <th data-options="field:'id',width:60,align:'center'">ID</th>
                <th data-options="field:'username',width:100,align:'center'">姓名</th>
                <th data-options="field:'phone',width:100,align:'center'">手机号</th>
                <th data-options="field:'email',width:120,align:'center'">邮箱</th>
                <th data-options="field:'msgcon',width:200,align:'center'">留言内容</th>
                <th data-options="field:'reply',width:150,formatter:replyFormatter,align:'center'">回复</th>
                <th data-options="field:'addtime',width:140,formatter:timeFormatter,align:'center'">留言时间</th>
                <th data-options="field:'_op',width:120,formatter:opFormatter,align:'center'">操作</th>
            </tr>
        </thead>
    </table>
</div>

<div id="replyDlg" class="easyui-dialog" title="回复留言" style="width:400px;padding:20px;" data-options="closed:true,modal:true">
    <form id="replyForm" method="post">
        <input type="hidden" name="id" id="replyId">
        <div class="form-group">
            <label>回复内容(reply)</label>
            <textarea class="form-control" name="reply" id="replyContent" rows="4" placeholder="请输入回复内容"></textarea>
        </div>
        <button type="button" class="btn btn-primary" id="replySubmitBtn">提交</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="/static/kcadmin/js/easyui/jquery.easyui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
<script src="/static/kcadmin/js/common.js"></script>

<script>
    function replyFormatter(val) {
        return val ? val : '<span class="text-muted">未回复</span>';
    }
    function timeFormatter(val) { return formatTimestamp(val); }
    function opFormatter(val, row) {
        return '<a href="javascript:void(0)" onclick="replyRow(' + row.id + ')">回复</a> ' +
               '<a href="javascript:void(0)" onclick="delRow(' + row.id + ')">删除</a>';
    }

    function loadData() {
        axiosGet(API_BASE + '/message/indexlist', { page: 1, rows: 20 }, function(res) {
            $('#dg').datagrid('loadData', { total: res.data.total, rows: res.data.rows });
        });
    }

    $(function() {
        loadData();
        $('#replySubmitBtn').click(function() {
            var reply = $('#replyContent').val();
            if (!reply) {
                alert('回复内容不能为空');
                return;
            }
            axiosPost(API_BASE + '/message/update', { id: $('#replyId').val(), reply: reply }, function(res) {
                alert(res.msg);
                $('#replyDlg').dialog('close');
                loadData();
            });
        });
    });

    function replyRow(id) {
        var rows = $('#dg').datagrid('getRows');
        var row = rows.find(function(r) { return r.id == id; });
        if (!row) return;
        $('#replyId').val(row.id);
        $('#replyContent').val(row.reply || '');
        $('#replyDlg').dialog('open');
    }

    function delRow(id) {
        if (!confirm('确定删除？')) return;
        axiosPost(API_BASE + '/message/delete', { id: id }, function(res) {
            alert(res.msg);
            loadData();
        });
    }
</script>

</body>
</html>