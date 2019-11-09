<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019-10-10 10:39
 */
class forWxPcController
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getUserCode($state = 'yo')
    {
        $this->state = $state;
        $this->redirect_uri = urlencode($this->config['url']);
        include_once dirname(__DIR__) . '/views/wxQrLogin.view.php';
    }

    public function getUserInfo()
    {
        $appId = $this->config['appId'];
        $appSecret = $this->config['appSecret'];

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $appSecret . "&code=" . $_GET['code'] . "&grant_type=authorization_code";
        $token = file_get_contents($url);
        $token = json_decode($token, true);
        $token["access_token"];
        $user_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $token['access_token'] . "&openid=" . $token["openid"] . "&lang=zh_CN";
        $userInfo = file_get_contents($user_url);
        return (array)json_decode($userInfo);
    }
}