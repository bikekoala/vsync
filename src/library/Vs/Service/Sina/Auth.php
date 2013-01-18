<?PHP
/**
 * Vs_Service_Sina_Auth
 * 新浪微博的授权验证调用类
 * access_token 测试1天,普通7天，真抠门。。。
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Sina_Auth extends Vs_Service_Abstract
{
    /**
     * 请求code,accesstoken的接口url
     */
    const AUTHORIZE = 'https://api.weibo.com/oauth2/authorize';
    const ACCESSTOKEN = 'https://api.weibo.com/oauth2/access_token';

    /**
     * getAuthorizeURL
     * 获取授权URL
     *
     * @param string $redirectUri 授权成功后的回调地址，即第三方应用的url
     * @param string $responseType 授权类型，为code
     * @param string $display 授权页面的终端类型，默认default, 适用于web浏览器
                              其他见 http://open.weibo.com/wiki/Oauth2/authorize
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
     * getAccessToken
     * 获取请求token的url
     *
     * @param string $code 调用authorize时返回的code
     * @param string $redirectUri 回调地址，必须和请求code时的redirectUri一致
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
     * setAuth
     * 设置授权
     *
     * @param $auth 需要记录的授权信息
     * @return void
     */
    public function setAuth($auth)
    {
        if (isset($auth['access_token']) && isset($auth['uid'])) {
            $info['s_access_token'] = $auth['access_token'];
            $info['s_uid'] = $auth['uid'];
            $auth = array_merge($this->_getAuth(), $info);
        }
        $auth = $this->_serializeAuth($auth);
        Su_Func::cookie($this->conf->cookie->key, $auth, $this->conf->cookie->expire_time, '');
    }

    /**
     * clearAuth
     * 清除授权
     *
     * @return void
     */
    public function clearAuth()
    {
        $auth = $this->_getAuth();
        if (isset($auth['s_access_token'])) unset($auth['s_access_token']);
        if (isset($auth['s_uid'])) unset($auth['s_uid']);
        if (empty($auth)) {
            Su_Func::cookie($this->conf->cookie->key, null, -5, '');
        } else {
            $this->setAuth($auth);
        }
    }
}
