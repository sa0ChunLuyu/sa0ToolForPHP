<?php

// sa0Tool 信息 开始
define(SA0_TOOL_TITLE, 'sa0Tool');
define(SA0_TOOL_VERSION, 'P02');
define(SA0_TOOL_AUTHOR, 'sa0ChunLuyu');
define(SA0_TOOL_DESCRIBE, 'yo,sa0Tool forPHP.');
define(SA0_TOOL_CONTENT, 'HAPPY HACKING');
// sa0Tool 信息 结束

// 获取路由信息 开始
$routeInfo = explode("/", $_GET['routeInfo']);
$fileName = 'default_file_name';
$funcName = 'default_func_name';
if (isset($routeInfo[0]) && $routeInfo[0] !== '') {
    $fileName = $routeInfo[0];
}
if (isset($routeInfo[1]) && $routeInfo[1] !== '') {
    $funcName = $routeInfo[1];
}
// 获取路由信息 结束

// 配置文件 开始
include_once APP_PATH . '/config.php';
// 配置文件 结束

// 控制器相关 开始
if ($fileName === 'default_file_name') {
    exit('default_file_name');
}

$filePath = APP_PATH . '/' . $appConfig['controllerPath'] . '/' . $fileName . '.php';
if (!is_file($filePath)) {
    exit('is_file($filePath)');
}

include_once __DIR__ . '/sa0Tool.php';
include_once $filePath;
$controllerName = $fileName . 'Controller';

$controller = new $controllerName();
// 控制器相关 结束

// 函数相关 开始
if ($funcName === 'default_func_name') {
    exit('default_func_name');
}

if (!method_exists($controller, $funcName)) {
    exit('method_exists($controller, $funcName)');
}

$controllerName->$funcName();
// 函数相关 结束
