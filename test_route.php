<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$_SERVER['REQUEST_URI'] = '/kcadmin/login/index';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = ['username' => 'admin123', 'userpwd' => 'admin123'];
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/public';

chdir(__DIR__ . '/public');

require __DIR__ . '/vendor/autoload.php';

$app = new \think\App();
$http = $app->http;
$response = $http->run();

echo "HTTP_CODE: " . $response->getCode() . "\n";
echo "BODY: " . $response->getContent() . "\n";