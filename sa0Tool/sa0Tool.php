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
        $this->forInclude_();
        // 引入 依赖文件 结束

        // PDO 创建 开始
        $this->createPDO_();
        // PDO 创建 结束

        // Redis 创建 开始
        $this->createRedis_();
        // Redis 创建 结束
    }

    final function json_($data = 'Yo', $state = true, $stateKey = 'state')
    {
        exit(json_encode(array($stateKey => $state, 'data' => $data), JSON_UNESCAPED_UNICODE));
    }

    final function time_($type = 13)
    {
        if ($type !== 13) return time();
        return floor(microtime(get_as_float) * 1000);
    }

    final function ip_()
    {
        if ($_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) return ($_SERVER["HTTP_CLIENT_IP"]);
        if ($_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) return ($_SERVER["HTTP_X_FORWARDED_FOR"]);
        if ($_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) return ($_SERVER["REMOTE_ADDR"]);
        if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) return ($_SERVER['REMOTE_ADDR']);
        return false;
    }

    final function data_($key = null, $default_value = false)
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
        return isset($bodyData[$key]) ? $bodyData[$key] : $default_value;
    }

    final function header_($name)
    {
        return $_SERVER['HTTP_' . strtoupper($name)];
    }

    final function view_($path)
    {
        include_once APP_CONFIG['viewPath'] . '/' . $path;
    }

    final function get_($e)
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

    final function post_($e, $headers = array('content-type:application/x-www-form-urlencoded'))
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

    final function forInclude_()
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

    final function createPDO_()
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

    final function createRedis_()
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

    public function sortByKey_($array, $keys, $sort = 'asc')
    {
        $newArr = $valArr = array();
        foreach ($array as $key => $value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ? asort($valArr) : arsort($valArr);
        reset($valArr);
        foreach ($valArr as $key => $value) {
            $newArr[$sort . $key] = $array[$key];
        }
        return $newArr;
    }
}