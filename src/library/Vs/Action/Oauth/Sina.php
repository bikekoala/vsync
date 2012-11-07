<?PHP
/**
 * 新浪微博授权验证操作类
 */
class Vs_Action_Oauth_Sina extends Vs_Action_Abstract
{
    public function run()
    {
        session_start();

        // 回调地址,需要和管理平台设置的回调地址一致
        $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?do=Oauth.Sina';
        $oauth = new Vs_Service_Sina_Oauth;
        if (isset($this->request->code)) {
            // 获取授权token
            $out = $oauth->getAccessToken($this->request->code, $callback);
            // 存储授权数据
            if (isset($out['access_token'])) {
                // 设置授权信息
                $oauth->setOauthInfo($out); 
            }
            $this->redirect(strstr($callback, 'index.php', true));
        } else {
            $url = $oauth->getAuthorizeURL($callback);
            $this->redirect($url);
        }
    }
}
