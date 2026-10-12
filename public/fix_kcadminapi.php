<?php
echo "<h3>kcadminapi 模块修复脚本</h3>";

$rootPath = dirname(__DIR__);
echo "项目根目录: {$rootPath}<br>";

echo "<h4>1. 清除 runtime 缓存</h4>";
$runtimePath = $rootPath . DIRECTORY_SEPARATOR . 'runtime';
$cleared = 0;
function clearDir($dir, &$count) {
    if (!is_dir($dir)) return;
    $items = glob($dir . DIRECTORY_SEPARATOR . '*');
    foreach ($items as $item) {
        if (is_dir($item)) {
            clearDir($item, $count);
        } elseif (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
            unlink($item);
            $count++;
        }
    }
}
clearDir($runtimePath, $cleared);
echo "已清除 {$cleared} 个缓存文件<br>";

echo "<h4>2. 检查 kcadminapi 目录</h4>";
$kcadminapiPath = $rootPath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'kcadminapi';
echo "路径: {$kcadminapiPath}<br>";
echo "is_dir: " . (is_dir($kcadminapiPath) ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "<br>";

if (is_dir($kcadminapiPath)) {
    echo "目录内容:<br>";
    $items = glob($kcadminapiPath . DIRECTORY_SEPARATOR . '*');
    foreach ($items as $item) {
        $name = basename($item);
        $isDir = is_dir($item);
        echo "&nbsp;&nbsp;{$name}" . ($isDir ? '/' : '') . "<br>";
        if ($isDir && $name === 'controller') {
            $controllers = glob($item . DIRECTORY_SEPARATOR . '*.php');
            foreach ($controllers as $ctrl) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;" . basename($ctrl) . "<br>";
            }
        }
    }
}

echo "<h4>3. 检查命名空间</h4>";
$loginFile = $kcadminapiPath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'Login.php';
if (is_file($loginFile)) {
    $content = file_get_contents($loginFile);
    if (preg_match('/namespace\s+([\w\\\\]+)/', $content, $m)) {
        echo "Login.php namespace: {$m[1]}<br>";
        $expected = 'app\\kcadminapi\\controller';
        if ($m[1] === $expected) {
            echo "<span style='color:green'>命名空间正确</span><br>";
        } else {
            echo "<span style='color:red'>命名空间错误! 应为 {$expected}</span><br>";
        }
    }
}

echo "<h4>4. 检查中间件引用</h4>";
$altpicFile = $kcadminapiPath . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'Altpic.php';
if (is_file($altpicFile)) {
    $content = file_get_contents($altpicFile);
    if (preg_match('/middleware\s*=\s*\[([^\]]+)\]/', $content, $m)) {
        echo "Altpic.php middleware: {$m[1]}<br>";
    }
}

echo "<h4>5. 测试类加载</h4>";
require_once $rootPath . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$loginClass = 'app\\kcadminapi\\controller\\Login';
echo "class_exists('{$loginClass}'): " . (class_exists($loginClass) ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "<br>";

$authClass = 'app\\kcadminapi\\middleware\\AdminAuth';
echo "class_exists('{$authClass}'): " . (class_exists($authClass) ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "<br>";

echo "<h4>6. 检查 open_basedir</h4>";
$obd = ini_get('open_basedir');
echo $obd ? "open_basedir: {$obd}" : 'open_basedir: (未限制)';
echo "<br>";

echo "<h4>7. 检查 think-multi-app 服务注册</h4>";
$servicesFile = $rootPath . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'services.php';
if (is_file($servicesFile)) {
    $services = include $servicesFile;
    echo "services.php 内容:<br>";
    foreach ($services as $i => $service) {
        echo "&nbsp;&nbsp;[{$i}] => {$service}<br>";
    }
    $hasMultiApp = in_array('think\\app\\Service', $services);
    echo "think-multi-app: " . ($hasMultiApp ? '<span style="color:green">已注册</span>' : '<span style="color:red">未注册</span>') . "<br>";
}

echo "<h4>8. 清除 OPcache</h4>";
if (function_exists('opcache_reset')) {
    $result = opcache_reset();
    echo "opcache_reset(): " . ($result ? '<span style="color:green">成功</span>' : '<span style="color:red">失败</span>') . "<br>";
} else {
    echo "OPcache 未启用（无需清除）<br>";
}

echo "<hr>";
echo "<h4>修复完成！请尝试访问:</h4>";
echo "<a href='/kcadminapi/login/index'>/kcadminapi/login/index</a><br>";
echo "<br>如果仍然报错，请重启 PHP 服务（PHP-FPM / Apache / 宝塔面板 PHP）后再试。";