<?php /*a:1:{s:61:"D:\web\php\thinkphp\corp-web\www\view\kcadmin\index\main.html";i:1788623353;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业官网后台管理</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/kcadmin/js/easyui/themes/default/easyui.css">
    <link rel="stylesheet" href="/static/kcadmin/css/style.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <div class="bg-dark text-white" id="sidebar-wrapper">
        <div class="sidebar-heading py-3 px-3 border-bottom border-secondary">
            <h6 class="mb-0">企业官网后台</h6>
            <small id="adminUser"></small>
        </div>
        <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-secondary sidebar-link" data-submenu="globalMenu">
                全局管理 <span class="float-end">&#9662;</span>
            </a>
            <div id="globalMenu" class="submenu bg-dark" style="display:none;">
                <a href="<?php echo url('sys/password'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">修改密码</a>
                <a href="<?php echo url('altpic/index'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">首页轮播管理</a>
                <a href="<?php echo url('sys/about'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">关于我们</a>
                <a href="<?php echo url('sys/contact'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">联络我们</a>
            </div>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-secondary sidebar-link" data-submenu="productMenu">
                产品展示管理 <span class="float-end">&#9662;</span>
            </a>
            <div id="productMenu" class="submenu bg-dark" style="display:none;">
                <a href="<?php echo url('pro/index'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">产品展示管理</a>
                <a href="<?php echo url('cate/product'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">产品展示分类</a>
            </div>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-secondary sidebar-link" data-submenu="newsMenu">
                最新消息管理 <span class="float-end">&#9662;</span>
            </a>
            <div id="newsMenu" class="submenu bg-dark" style="display:none;">
                <a href="<?php echo url('news/index'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">最新消息管理</a>
                <a href="<?php echo url('cate/news'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">最新消息分类</a>
            </div>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-secondary sidebar-link" data-submenu="msgMenu">
                留言板管理 <span class="float-end">&#9662;</span>
            </a>
            <div id="msgMenu" class="submenu bg-dark" style="display:none;">
                <a href="<?php echo url('message/index'); ?>" class="list-group-item list-group-item-action bg-dark text-white-50 border-0 ps-5" target="mainFrame">留言板列表</a>
            </div>
            <a href="<?php echo url('login/logout'); ?>" class="list-group-item list-group-item-action bg-dark text-white border-secondary" id="logoutBtn">
                退出
            </a>
        </div>
    </div>
    <div id="page-content-wrapper" class="flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-3">
            <button class="btn btn-sm btn-outline-secondary" id="menuToggle">☰</button>
            <span class="ms-3 fw-bold" id="breadcrumb">首页</span>
        </nav>
        <div class="container-fluid p-4">
            <iframe name="mainFrame" id="mainFrame" src="<?php echo url('sys/password'); ?>" width="100%" height="calc(100vh - 80px)" frameborder="0"></iframe>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/static/kcadmin/js/easyui/jquery.easyui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
<script src="/static/kcadmin/js/common.js"></script>
<script>
var LOGIN_URL = '<?php echo url("login/index"); ?>';
</script>

<script>
    $(function() {
        var token = localStorage.getItem('token');
        if (!token) {
            window.location.href = LOGIN_URL;
            return;
        }
        $('#adminUser').text(localStorage.getItem('username') || 'admin');
        $('.sidebar-link').click(function() {
            var submenuId = $(this).data('submenu');
            if (submenuId) {
                $('.submenu').not('#' + submenuId).slideUp();
                $('#' + submenuId).slideToggle();
            }
        });
        $('a[target="mainFrame"]').click(function() {
            $('#breadcrumb').text($(this).text().trim());
        });
        $('#menuToggle').click(function() {
            $('#sidebar-wrapper').toggleClass('collapsed');
        });
        $('#logoutBtn').click(function() {
            axios.post(API_BASE + '/login/logout', {}, { headers: { 'token': token } }).finally(function() {
                localStorage.removeItem('token');
                localStorage.removeItem('username');
                window.location.href = LOGIN_URL;
            });
        });
    });
</script>

</body>
</html>