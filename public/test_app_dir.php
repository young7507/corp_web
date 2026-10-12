<?php
require __DIR__ . '/../vendor/autoload.php';

$app = new \think\App();

echo "<h3>ThinkPHP6 路径诊断</h3>";
echo "rootPath: " . $app->getRootPath() . "<br>";
echo "basePath: " . $app->getBasePath() . "<br>";
echo "appPath: " . $app->getAppPath() . "<br>";
echo "runtimePath: " . $app->getRuntimePath() . "<br>";

echo "<h3>应用目录检测</h3>";
$apps = ['kcadmin', 'kcadminapi', 'kcindexapi', 'index'];
foreach ($apps as $appName) {
    $path = $app->getBasePath() . $appName . DIRECTORY_SEPARATOR;
    $isDir = is_dir($path);
    $status = $isDir ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>';
    echo "{$appName}: path={$path} is_dir={$status}<br>";
}

echo "<h3>app/ 下所有目录</h3>";
$dirs = glob($app->getBasePath() . '*', GLOB_ONLYDIR);
foreach ($dirs as $dir) {
    echo basename($dir) . "<br>";
}

echo "<h3>kcadminapi 目录内容</h3>";
$kcadminapiPath = $app->getBasePath() . 'kcadminapi' . DIRECTORY_SEPARATOR;
if (is_dir($kcadminapiPath)) {
    $items = glob($kcadminapiPath . '*');
    foreach ($items as $item) {
        echo basename($item) . (is_dir($item) ? '/' : '') . "<br>";
    }
} else {
    echo "<span style='color:red'>目录不存在!</span><br>";
}

echo "<h3>PHP open_basedir</h3>";
$obd = ini_get('open_basedir');
echo $obd ? $obd : '(未限制)';

echo "<h3>autoload 测试</h3>";
$loginClass = 'app\\kcadminapi\\controller\\Login';
echo "class_exists({$loginClass}): " . (class_exists($loginClass) ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "<br>";

echo "<h3>直接访问测试</h3>";
$testUrl = '/kcadminapi/login/index';
echo "请尝试访问: <a href='{$testUrl}'>{$testUrl}</a><br>";