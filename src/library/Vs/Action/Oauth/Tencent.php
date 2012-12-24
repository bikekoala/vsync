<?PHP
/**
 * 腾讯微博授权验证操作类
 */
class Vs_Action_Oauth_Tencent extends Vs_Action_Abstract
{
    protected $_needAuth = false;

    public function run()
    {
        // 回调地址
        $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?do=Oauth.Tencent';
        $auth = new Vs_Service_Tencent_Auth;
        if (isset($this->request->code)) {
            // 获取授权token
            $out = $auth->getAccessToken($this->request->code, $callback);
            // 存储授权数据
            if (isset($out['access_token'])) {
                // 设置授权信息
                $out['openid'] = $this->request->openid;
                $auth->setAuthInfo($out); 
            }
            $this->redirect(strstr($callback, 'index.php', true));
        } else {
            $url = $auth->getAuthorizeURL($callback);
            $this->redirect($url);
        }
    }
}
