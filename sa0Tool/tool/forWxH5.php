<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019-10-10 10:39
 */
class forWxH5Controller
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getUserCode($state = 'yo')
    {
//        $redirect_uri = urlencode($this->config['url']);
//
//        echo '<div id="login_container"></div>
//                        <script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
//                        <script>
//                        var obj = new WxLogin({
//                 self_redirect:true,
//                 id:"login_container",
//                 appid: "wx3dd19df77739cb5e",
//                 scope: "snsapi_login",
//                 redirect_uri: ' . $redirect_uri . ',
//                  state: "123",
//                 });
//                </script>';
        $appID = $this->config['appId']; // 公众号AppID
        $redirectUri = $this->config['url'];  // 授权接口地址
        $strUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appID . "&redirect_uri=" . $redirectUri . "&response_type=code&scope=snsapi_userinfo&state=" . $state . "#wechat_redirect";
        header('location:' . $strUrl);
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