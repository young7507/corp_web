<?php /*a:1:{s:61:"D:\web\php\thinkphp\corp-web\www\view\kcadmin\news\index.html";i:1788666071;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>最新消息管理</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/kcadmin/js/easyui/themes/default/easyui.css">
    <link rel="stylesheet" href="/static/kcadmin/css/style.css">
</head>
<body>
<div class="container-fluid p-3">
    <div class="panel-heading d-flex justify-content-between align-items-center" style="margin-bottom:10px;">
        <span>最新消息管理</span>
        <button class="btn btn-primary btn-sm" onclick="window.location.href='<?php echo url("news/add"); ?>'">添加消息</button>
    </div>
    <table id="dg" class="easyui-datagrid" style="width:100%;height:450px;"
        data-options="singleSelect:true,pagination:true,rownumbers:true,fitColumns:true">
        <thead>
            <tr>
                <th data-options="field:'id',width:60,align:'center'">ID</th>
                <th data-options="field:'title',width:150,align:'center'">标题</th>
                <th data-options="field:'subtitle',width:120,align:'center'">副标题</th>
                <th data-options="field:'catename',width:80,formatter:cateFormatter,align:'center'">分类</th>
                <th data-options="field:'thumb',width:100,formatter:imgFormatter,align:'center'">缩略图</th>
                <th data-options="field:'tui',width:60,formatter:tuiFormatter,align:'center'">推荐</th>
                <th data-options="field:'pubtime',width:140,formatter:timeFormatter,align:'center'">发布时间</th>
                <th data-options="field:'ord',width:60,align:'center'">排序</th>
                <th data-options="field:'_op',width:180,formatter:opFormatter,align:'center'">操作</th>
            </tr>
        </thead>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="/static/kcadmin/js/easyui/jquery.easyui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
<script src="/static/kcadmin/js/common.js"></script>
<script>
var EDIT_URL = '<?php echo url("news/edit"); ?>';
</script>

<script>
    var cateMap = {};
    function loadCates() {
        axiosGet(API_BASE + '/cate/getList', {'ctype': 2}, function(res) {
            var list = res.data || [];
            list.forEach(function(item) {
                cateMap[item.id] = item.catename;
            });
        });
    }
    function cateFormatter(val) { return cateMap[val] || val; }
    function imgFormatter(val) {
        if (val) return '<img src="/' + val + '" height="40">';
        return '';
    }
    function tuiFormatter(val) { return val == 1 ? '<span class="text-success">是</span>' : '否'; }
    function timeFormatter(val) { return formatTimestamp(val); }
    function opFormatter(val, row) {
        return '<a href="javascript:void(0)" onclick="editRow(' + row.id + ')">编辑</a> ' +
               '<a href="javascript:void(0)" onclick="delRow(' + row.id + ')">删除</a> ' +
               '<a href="javascript:void(0)" onclick="toggleTui(' + row.id + ',' + (row.tui == 1 ? 0 : 1) + ')">' + (row.tui == 1 ? '取消推荐' : '设为推荐') + '</a>';
    }

    function loadData() {
        axiosGet(API_BASE + '/news/indexlist', { page: 1, rows: 20 }, function(res) {
            $('#dg').datagrid('loadData', { total: res.data.total, rows: res.data.rows });
        });
    }

    $(function() {
        loadCates();
        loadData();
    });

    function editRow(id) {
        window.location.href = EDIT_URL + '?id=' + id;
    }

    function delRow(id) {
        if (!confirm('确定删除？')) return;
        axiosPost(API_BASE + '/news/del', { id: id }, function(res) {
            alert(res.msg);
            loadData();
        });
    }

    function toggleTui(id, tui) {
        axiosPost(API_BASE + '/news/updateStatus', { id: id, tui: tui }, function(res) {
            loadData();
        });
    }
</script>

</body>
</html>