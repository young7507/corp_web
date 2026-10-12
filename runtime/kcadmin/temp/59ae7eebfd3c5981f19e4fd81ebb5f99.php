<?php /*a:1:{s:62:"D:\web\php\thinkphp\corp-web\www\view\kcadmin\login\index.html";i:1788625837;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业官网后台 - 登录</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f5f5f5; }
        .login-box { max-width: 400px; margin: 120px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        .login-box h3 { text-align: center; margin-bottom: 25px; }
    </style>
</head>
<body>
<div class="login-box">
    <h3>企业官网后台管理</h3>
    <form id="loginForm" method="post">
        <div class="mb-3">
            <label class="form-label">用户名</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="请输入用户名" required>
        </div>
        <div class="mb-3">
            <label class="form-label">密码</label>
            <input type="password" class="form-control" name="userpwd" id="userpwd" placeholder="请输入密码" required>
        </div>
        <div class="d-grid">
            <button type="button" class="btn btn-primary" id="loginBtn">登 录</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
<script>
var API_BASE = '/kcadmin';
var MAIN_URL = '<?php echo url("index/main"); ?>';
</script>

<script>
    $('#loginBtn').click(function() {
        var username = $('#username').val();
        var userpwd = $('#userpwd').val();
        if (!username || !userpwd) {
            alert('请输入用户名和密码');
            return;
        }
        axios.post(API_BASE + '/login/index', {
            username: username,
            userpwd: userpwd
        }).then(function(res) {
            if (res.data.code === 200) {
                localStorage.setItem('token', res.data.data.token);
                localStorage.setItem('username', res.data.data.username);
                window.location.href = MAIN_URL;
            } else {
                alert(res.data.msg || '登录失败');
            }
        }).catch(function(err) {
            alert('网络错误，请重试');
        });
    });
</script>

</body>
</html>