<?php
//echo APP_PATH;
//echo '<pre>';
//print_r($_SERVER);
header("Access-Control-Allow-Origin:*");
define(USER_IP, $_SERVER['REMOTE_ADDR']);

echo '<pre>';
//print_r($_SERVER);
// [QUERY_STRING] => routeInfo=col/func&12313=234234
$routeInfo = explode("/", $_GET['routeInfo']);
$fileName = 'index';
$funcName = 'index';
//$routeInfo[0]
if (isset($routeInfo[0]) && $routeInfo[0] !== '') {
    $fileName = $routeInfo[0];
}
if (isset($routeInfo[1]) && $routeInfo[1] !== '') {
    $funcName = $routeInfo[1];
}
print_r(array($fileName, $funcName,USER_IP,APP_PATH));
// [REQUEST_METHOD] => GET
