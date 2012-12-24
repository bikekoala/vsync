<?PHP
/**
 * 新浪微博的授权验证调用类
 * access_token 测试1天,普通7天，真抠门。。。
 * refresh_token 3个月
 */
class Vs_Service_Sina_Auth extends Vs_Service_Abstract
{
    /**
     * 请求code,accesstoken的接口url
     */
    const AUTHORIZE = 'https://api.weibo.com/oauth2/authorize';
    const ACCESSTOKEN = 'https://api.weibo.com/oauth2/access_token';

    /**
     * 获取授权URL
     * @param $redirectUri 授权成功后的回调地址，即第三方应用的url
     * @param $responseType 授权类型，为code
     * @param $display 授权页面的终端类型，默认default,适用于web浏览器，其他见 http://open.weibo.com/wiki/Oauth2/authorize
     * @return string
     */
    public function getAuthorizeURL($redirectUri, $responseType = 'code', $display = 'default')
    {
        $params = array(
            'client_id' => $this->conf->sina->app_key,
            'redirect_uri' => $redirectUri,
            'response_type' => $responseType,
            'display' => $display
        );
        return self::AUTHORIZE . '?' . http_build_query($params);
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
            'client_id' => $this->conf->sina->app_key,
            'client_secret' => $this->conf->sina->app_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri
        );
        $url = self::ACCESSTOKEN . '?' . http_build_query($params);
        $curl = new Su_Curl($url);
        return $curl->rest();
    }
   
    /**
     * 设置授权
     * @param $info 需要记录的SESSION
     */
    public function setAuthInfo($info)
    {
        $_SESSION['s_access_token'] = $info['access_token'];
        $_SESSION['s_uid'] = $info['uid'];
    }

    /**
     * 清除授权
     */
    public function clearAuthInfo()
    {
        if (isset($_SESSION['s_access_token'])) unset($_SESSION['s_access_token']);
        if (isset($_SESSION['s_uid'])) unset($_SESSION['s_uid']);
    }

    /**
     * 检查授权
     */
    public function checkAuth()
    {
        return isset($_SESSION['s_access_token']) ? $_SESSION['s_access_token'] : false;
    }
}
