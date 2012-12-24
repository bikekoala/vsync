<?PHP
/**
 * 新浪微博授权验证操作类
 */
class Vs_Action_Oauth_Sina extends Vs_Action_Abstract
{
    protected $_needAuth = false;

    public function run()
    {
        // 回调地址,需要和管理平台设置的回调地址一致
        $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?do=Oauth.Sina';
        $auth = new Vs_Service_Sina_Oauth;
        if (isset($this->request->code)) {
            // 获取授权token
            $out = $auth->getAccessToken($this->request->code, $callback);
            // 存储授权数据
            if (isset($out['access_token'])) {
                // 设置授权信息
                $auth->setAuthInfo($out); 
            }
            $this->redirect(strstr($callback, 'index.php', true));
        } else {
            $url = $auth->getAuthorizeURL($callback);
            $this->redirect($url);
        }
    }
}
