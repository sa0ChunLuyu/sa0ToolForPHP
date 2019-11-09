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
        $do[0] = isset(APP_CONFIG['include']['public']);
        $do[1] = isset(APP_CONFIG['include'][CONTROLLER_NAME]);
        if ($do[0]) $do[0] = in_array('phpRedis', APP_CONFIG['include']['public']);
        if ($do[1]) $do[1] = in_array('phpRedis', APP_CONFIG['include'][CONTROLLER_NAME]);
        $do = $do[0] || $do[1];
        if (!$do) return;
        $this->redis = (new phpRedis)->getRedis();
    }

    final function requestData($key = null)
    {
        $bodyData = @file_get_contents('php://input');
        $bodyData = json_decode($bodyData, true);
        if (!$key) return array(
            'POST' => $_POST,
            'GET' => $_GET,
            'BODY' => $bodyData,
        );
        if (isset($_POST[$key])) return $_POST[$key];
        if (isset($_GET[$key])) return $_GET[$key];
        return isset($bodyData[$key]) ? $bodyData[$key] : false;
    }

    final function header($name)
    {
        return $_SERVER['HTTP_' . strtoupper($name)];
    }

    final function view($path)
    {
        include_once APP_CONFIG['viewPath'] . '/' . $path;
    }

    final function sa0Get($e)
    {
        $url = $e['url'] . http_build_query($e['data']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    final function sa0Post($e, $headers = array('content-type:application/x-www-form-urlencoded'))
    {
        $durl = $e['url'];
        $post_data = json_encode($e['data'], true);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $durl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
}