<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019-10-10 09:17
 */
class forQqH5Controller
{
    public $loginInfo;

    public function __construct($config)
    {
        $this->loginInfo = $config;
    }

    public function getUserCode($type)
    {
        $appId = $this->loginInfo['appId'];
        $url = $this->loginInfo['url'];

        $my_url = urlencode($url);
        $url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $appId . "&redirect_uri=" . $my_url . "&state=" . $type;
        header('location:' . $url);
    }

    public function getUserInfo()
    {

        $appId = $this->loginInfo['appId'];
        $appKey = $this->loginInfo['appKey'];
        $url = $this->loginInfo['url'];
        $unionid = 1;
        if (isset($this->loginInfo['unionid'])) {
            $unionid = $this->loginInfo['unionid'];
        }

        $token_url = 'https://graph.qq.com/oauth2.0/token?' . http_build_query(array(
                'grant_type' => 'authorization_code',
                'client_id' => $appId,
                'client_secret' => $appKey,
                'code' => $_GET['code'],
                'redirect_uri' => $url,
            ));
        $response = file_get_contents($token_url);
        $accessToken = explode("=", explode("&", $response)[0])[1];
        $graph_url = "https://graph.qq.com/oauth2.0/me?" . http_build_query(array(
                'access_token' => $accessToken,
                'unionid' => $unionid
            ));
        $str = file_get_contents($graph_url);
        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
        }
        return (array)json_decode($str);
    }
}