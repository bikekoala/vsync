<?PHP
/**
 * 腾讯微博授权验证操作类
 */
class Vs_Action_Oauth_Tencent extends Vs_Action_Abstract
{
    public function run()
    {
        session_start();

        // 回调地址
        $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?do=Oauth.Tencent';
        $oauth = new Vs_Service_Oauth_Tencent;
        if (isset($this->request->code)) {
                // 获取授权token
                $out = $oauth->getAccessToken($this->request->code, $callback);
                // 存储授权数据
                if (isset($out['access_token'])) {
                    // 设置授权信息
                    $out['openid'] = $this->request->openid;
                    $oauth->setOAuthInfo($out); 
                }
                $this->redirect(strstr($callback, 'index.php', true));
            } else {
                $url = $oauth->getAuthorizeURL($callback);
                $this->redirect($url);
            }
    }
}
