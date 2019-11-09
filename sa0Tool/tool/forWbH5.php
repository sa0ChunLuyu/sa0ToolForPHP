<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019-10-10 10:22
 */
class forWbH5Controller
{
    public $loginInfo;

    public function __construct($config)
    {
        $this->loginInfo = $config;
    }

    public function getUserCode($state)
    {
        $appKey = $this->loginInfo['appKey'];
        $url = $this->loginInfo['url'];

        header('Content-Type: text/html; charset=UTF-8');
        $params = array();
        $params['client_id'] = $appKey;
        $params['redirect_uri'] = $url;
        $params['response_type'] = 'code';
        $params['state'] = $state;
        $params['display'] = NULL;
        header('location:' . 'https://api.weibo.com/oauth2/authorize?' . http_build_query($params));
    }


    public function getUserInfo()
    {
        $appKey = $this->loginInfo['appKey'];
        $appSecret = $this->loginInfo['appSecret'];
        $url = $this->loginInfo['url'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.weibo.com/oauth2/access_token?' . http_build_query(array(
                'grant_type' => 'authorization_code',
                'client_id' => $appKey,
                'client_secret' => $appSecret,
                'redirect_uri' => $url,
                'code' => $_GET['code'],
            )));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array());
        curl_setopt($curl, CURLOPT_HTTPHEADER, array());
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($curl);
        curl_close($curl);
        return (array)json_decode($data);
    }
}