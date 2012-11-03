<?PHP
class Vs_Service_Oauth_Tencent extends Vs_Service_Abstract
{
    const API = 'http://open.t.qq.com/api/';
    const AUTHORIZE = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';
    const ACCESSTOKEN = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';

    /**
     * 获取授权URL
     * @param $redirectUri 授权成功后的回调地址，即第三方应用的url
     * @param $responseType 授权类型，为code
     * @param $wap 用于指定手机授权页的版本，默认PC，值为1时跳到wap1.0的授权页，为2时同理
     * @return string
     */
    public function getAuthorizeURL($redirectUri, $responseType = 'code', $wap = false)
    {
        $params = array(
            'client_id' => $this->conf->tencent->app_key,
            'redirect_uri' => $redirectUri,
            'response_type' => $responseType,
            'wap' => $wap
        );
        return self::$AUTHORIZE.'?'.http_build_query($params);
    }

    /**
     * 获取请求token的url
     * @param $code 调用authorize时返回的code
     * @param $redirectUri 回调地址，必须和请求code时的redirectUri一致
     * @return array
     */
    public function getAccessToken($code, $redirectUri)
    {
        $params = array(
            'client_id' => $this->conf->tencent->app_key,
            'client_secret' => $this->conf->tencent->app_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri
        );
        $url = self::$ACCESSTOKEN.'?'.http_build_query($params);
        $curl = new Su_Curl($url);
        parse_str($curl->get(), $out);
        return $out;
    }
    
    /**
     * 刷新授权信息
     * 此处以SESSION形式存储做演示，实际使用场景请做相应的修改
     */
    public function refreshToken()
    {
        $params = array(
            'client_id' => $this->conf->tencent->app_key,
            'client_secret' => $this->conf->tencent->app_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $_SESSION['t_refresh_token']
        );
        $url = self::$ACCESSTOKEN.'?'.http_build_query($params);
        $curl = new Su_Curl($url);
        parse_str($curl->get(), $out);
        if (isset($out['access_token'])) {
            $this->setOAuthInfo($out);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 验证授权是否有效
     */
    public function checkOAuthValid()
    {
        //todo:使用api接口调用
        $r = Vs_Service_Api_Tencent::api('user/info');
        if (isset($r['data']['name'])) {
            return true;
        } else {
            $this->clearOAuthInfo();
            return false;
        }
    }
    
    /**
     * 清除授权
     */
    public function clearOAuthInfo()
    {
        isset($_SESSION['t_access_token']) && unset($_SESSION['t_access_token']);
        isset($_SESSION['t_refresh_token']) && unset($_SESSION['t_refresh_token']);
        isset($_SESSION['t_openid']) && unset($_SESSION['t_openid']);
        isset($_SESSION['t_expire_in']) && unset($_SESSION['t_expire_in']);
    }

    /**
     * 设置授权
     * @param array $info
     */
    public function setOAuthInfo($info)
    {
        $_SESSION['t_access_token'] = $info['access_token'];
        $_SESSION['t_refresh_token'] = $info['refresh_token'];
        $_SESSION['t_openid'] = $info['openid'];
        $_SESSION['t_expire_in'] = $info['expire_in'];
    }
}
