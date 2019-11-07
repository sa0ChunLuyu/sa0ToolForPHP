<?php

class sa0ToolMaker
{
    private function push($message)
    {
        $this->message = strtoupper($message);
        include_once __DIR__ . '/views/error.php';
        exit;
    }

    function __construct()
    {

        // 设置 sa0Tool 信息 开始
        define(SA0_TOOL_TITLE, 'sa0Tool');
        define(SA0_TOOL_VERSION, 'P02');
        define(SA0_TOOL_AUTHOR, 'sa0ChunLuyu');
        define(SA0_TOOL_DESCRIBE, 'Yo,sa0Tool forPHP.');
        define(SA0_TOOL_CONTENT, 'HAPPY HACKING');
        // 设置 sa0Tool 信息 结束

        // 获取路由信息 开始
        $routeInfo = explode("/", $_GET['routeInfo']);
        $fileName = 'default_file_name';
        $funcName = 'default_func_name';

        if (isset($routeInfo[0]) && $routeInfo[0] !== '') $fileName = $routeInfo[0];

        if (isset($routeInfo[1]) && $routeInfo[1] !== '') $funcName = $routeInfo[1];
        // 获取路由信息 结束

        // 配置文件 开始
        $appConfig = array();
        if (is_file(APP_PATH . '/config.php')) include_once APP_PATH . '/config.php';

        define(APP_CONFIG, $appConfig);
        // 配置文件 结束


        // 跨域设置 开始
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        $appOrigin = isset(APP_CONFIG['allowOrigin']['appOrigin']) ? APP_CONFIG['allowOrigin']['appOrigin'] : array();
        $controllerOrigin = isset(APP_CONFIG['allowOrigin'][$fileName]) ? APP_CONFIG['allowOrigin'][$fileName] : array();
        $allow_origin = array_merge($appOrigin, $controllerOrigin);

        if (in_array($origin, $allow_origin) || in_array("*", $allow_origin)) header('Access-Control-Allow-Origin:' . $origin);
        // 跨域设置 结束

        // 请求类型判断 开始
        $requestMethod = array();
        if (isset(APP_CONFIG['requestMethod']['appMethod'])) $requestMethod = APP_CONFIG['requestMethod']['appMethod'];
        if (isset(APP_CONFIG['requestMethod'][$fileName])) $requestMethod = APP_CONFIG['requestMethod'][$fileName];
        if (count($requestMethod) !== 0) {
            if (!in_array($_SERVER['REQUEST_METHOD'], $requestMethod)) {
                $this->push('request method not allow');
                exit;
            };
        };
        // 请求类型判断 结束

        // 控制器相关 开始
        if ($fileName === 'default_file_name') {
            $httpType = isset($_SERVER['HTTPS']) ? 'http://' : 'http://';
            header('Location: ' . $httpType . $_SERVER['HTTP_HOST']);
            exit;
        }

        $controllerPath = isset(APP_CONFIG['controllerPath']) ? APP_CONFIG['controllerPath'] : 'controller';
        $filePath = APP_PATH . '/' . $controllerPath . '/' . $fileName . '.php';

        if (!is_file($filePath)) $this->push('controller not found');

        define(CONTROLLER_NAME, $fileName);
        // 控制器相关 结束

        // 函数相关 开始
        $controllerName = $fileName . 'Controller';

        if ($funcName === 'default_func_name') $this->push($controllerName);
        // 函数相关 结束

        // 引入控制器 开始
        include_once __DIR__ . '/sa0Tool.php';
        include_once $filePath;

        $controller = new $controllerName();
        // 引入控制器 结束

        // 判断函数 开始
        if (!method_exists($controller, $funcName)) $this->push('function not found');
        // 判断函数 结束

        $controller->$funcName();
        // 引入函数 结束
    }
}

$s = new sa0ToolMaker();

