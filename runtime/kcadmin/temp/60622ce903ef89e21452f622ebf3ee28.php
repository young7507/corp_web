<?php /*a:1:{s:60:"D:\web\php\thinkphp\corp-web\www\view\kcadmin\cate\news.html";i:1788666213;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>最新消息分类管理</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/kcadmin/js/easyui/themes/default/easyui.css">
    <link rel="stylesheet" href="/static/kcadmin/css/style.css">
</head>
<body>
<div class="container-fluid p-3">
    <div class="panel-heading d-flex justify-content-between align-items-center" style="margin-bottom:10px;">
        <span>最新消息分类管理</span>
        <button class="btn btn-primary btn-sm" id="addBtn">添加分类</button>
    </div>
    <table id="dg" class="easyui-datagrid" style="width:100%;height:400px;"
        data-options="singleSelect:true,pagination:true,rownumbers:true,fitColumns:true">
        <thead>
            <tr>
                <th data-options="field:'id',width:60,align:'center'">ID</th>
                <th data-options="field:'catename',width:200,align:'center'">分类名称</th>
                <th data-options="field:'ord',width:80,align:'center'">排序</th>
                <th data-options="field:'_op',width:120,formatter:opFormatter,align:'center'">操作</th>
            </tr>
        </thead>
    </table>
</div>

<div id="addDlg" class="easyui-dialog" title="添加分类" style="width:350px;padding:20px;" data-options="closed:true,modal:true">
    <form id="addForm" method="post">
        <div class="form-group">
            <label>分类名称(cate)</label>
            <input type="text" class="form-control" name="cate" id="addCate">
        </div>
        <div class="form-group">
            <label>排序(ord)</label>
            <input type="number" class="form-control" name="ord" id="addOrd" value="0">
        </div>
        <button type="button" class="btn btn-primary" id="addSubmitBtn">提交</button>
    </form>
</div>

<div id="editDlg" class="easyui-dialog" title="修改分类" style="width:350px;padding:20px;" data-options="closed:true,modal:true">
    <form id="editForm" method="post">
        <input type="hidden" name="id" id="editId">
        <div class="form-group">
            <label>分类名称(cate)</label>
            <input type="text" class="form-control" name="cate" id="editCate">
        </div>
        <div class="form-group">
            <label>排序(ord)</label>
            <input type="number" class="form-control" name="ord" id="editOrd" value="0">
        </div>
        <button type="button" class="btn btn-primary" id="editSubmitBtn">提交</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="/static/kcadmin/js/easyui/jquery.easyui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
<script src="/static/kcadmin/js/common.js"></script>

<script>
    function opFormatter(val, row) {
        return '<a href="javascript:void(0)" onclick="editRow(' + row.id + ')">修改</a> ' +
               '<a href="javascript:void(0)" onclick="delRow(' + row.id + ')">删除</a>';
    }

    function loadData() {
        axiosGet(API_BASE + '/cate/index', { ctype: 2, page: 1, rows: 20 }, function(res) {
            $('#dg').datagrid('loadData', { total: res.data.total, rows: res.data.rows });
        });
    }

    $(function() {
        loadData();
        $('#addBtn').click(function() {
            $('#addForm')[0].reset();
            $('#addDlg').dialog('open');
        });
        $('#addSubmitBtn').click(function() {
            var cate = $('#addCate').val();
            var ord = $('#addOrd').val();
            if (!cate || ord === '') {
                alert('分类名称和排序不能为空');
                return;
            }
            axiosPost(API_BASE + '/cate/insert', { cate: cate, ord: ord, ctype: 2 }, function(res) {
                alert(res.msg);
                $('#addDlg').dialog('close');
                loadData();
            });
        });
        $('#editSubmitBtn').click(function() {
            var cate = $('#editCate').val();
            var ord = $('#editOrd').val();
            if (!cate || ord === '') {
                alert('分类名称和排序不能为空');
                return;
            }
            axiosPost(API_BASE + '/cate/update', { id: $('#editId').val(), cate: cate, ord: ord }, function(res) {
                alert(res.msg);
                $('#editDlg').dialog('close');
                loadData();
            });
        });
    });

    function editRow(id) {
        var rows = $('#dg').datagrid('getRows');
        var row = rows.find(function(r) { return r.id == id; });
        if (!row) return;
        $('#editId').val(row.id);
        $('#editCate').val(row.catename);
        $('#editOrd').val(row.ord);
        $('#editDlg').dialog('open');
    }

    function delRow(id) {
        if (!confirm('确定删除？')) return;
        axiosPost(API_BASE + '/cate/delete', { id: id }, function(res) {
            alert(res.msg);
            loadData();
        });
    }
</script>

</body>
</html>