<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019/10/25 8:12 上午
 */

class sa0Tool
{
    public function __construct()
    {
        $this->forConstruct();
    }

    final function forConstruct()
    {
        // 引入 依赖文件 开始
        $this->forInclude();
        // 引入 依赖文件 结束

        // PDO 创建 开始
        $this->createPDO();
        // PDO 创建 结束

        // Redis 创建 开始
        $this->createRedis();
        // Redis 创建 结束
    }

    final function json($data, $msg = true)
    {
        exit(json_encode(array('msg' => $msg, 'data' => $data), JSON_UNESCAPED_UNICODE));
    }

    final function forInclude()
    {
        $includeArray = isset(APP_CONFIG['include']['public']) ? APP_CONFIG['include']['public'] : array();
        $includeArray = isset(APP_CONFIG['include'][CONTROLLER_NAME]) ? array_merge($includeArray, APP_CONFIG['include'][CONTROLLER_NAME]) : $includeArray;
        $includeArray = array_flip(array_flip($includeArray));
        foreach ($includeArray as $value) {
            $filePath = is_file(__DIR__ . '/tool/' . $value . '.php') ? __DIR__ : false;
            $filePath = is_file(APP_PATH . '/tool/' . $value . '.php') ? APP_PATH : $filePath;
            if (!$filePath) $this->json(strtoupper('include ' . $value . ' not found'), false);

            include $filePath . '/tool/' . $value . '.php';
        }
    }

    final function createPDO()
    {
        if (!isset(APP_CONFIG['db'])) $this->json(strtoupper('db config not found'), false);
        $do = array(false, false);
        $do[0] = isset(APP_CONFIG['include']['public']);
        $do[1] = isset(APP_CONFIG['include'][CONTROLLER_NAME]);
        if ($do[0]) $do[0] = in_array('pdo', APP_CONFIG['include']['public']);
        if ($do[1]) $do[1] = in_array('pdo', APP_CONFIG['include'][CONTROLLER_NAME]);
        $do = $do[0] || $do[1];
        if (!$do) return;
        $this->pdo = new pdoController();
    }

    final function createRedis()
    {
        if (!isset(APP_CONFIG['redis'])) $this->json(strtoupper('redis config not found'), false);
        $this->redis = (new phpRedis)->getRedis();
    }
}