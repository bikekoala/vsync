<?PHP
class Vs_Action_Oauth_Tencent extends Vs_Action_Abstract
{
    public function run()
    {
        session_start();
        $this->format(Su_Const::FT_HTML);

        $oauth = new Vs_Service_Oauth_Tencent;
        $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?do=Oauth.Tencent';

        if (isset($this->request->code)) {
                // 获取授权token
                $out = $oauth->getAccessToken($this->request->code, $callback);
                // 存储授权数据
                if (isset($out['access_token'])) {
                    // 设置授权信息
                    $out['openid'] = $this->request->openid;
                    $oauth->setOAuthInfo($out);
                    // 验证授权
                    if ($oauth->checkOAuthValid()) {
                        // 刷新页面
                        $this->redirect($callback);
                    }
                }
                exit('<h3>授权失败,请重试</h3>');
            } else {
                $url = $oauth->getAuthorizeURL($callback);
                $this->redirect($url);
            }
    }
}
