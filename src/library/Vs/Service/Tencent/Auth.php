<?PHP
/**
 * Vs_Service_Tencent_Auth
 * 腾讯微博的授权验证调用类
 * access_token 初级7天，高级15天
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Tencent_Auth extends Vs_Service_Abstract
{
    /**
     * 请求code,accesstoken的接口url
     */
    const AUTHORIZE = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';
    const ACCESSTOKEN = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';

    /**
     * getAuthorizeURL
     * 获取授权URL
     *
     * @param string $redirectUri 授权成功后的回调地址，即第三方应用的url
     * @param string $responseType 授权类型，为code
     * @param string $wap 用于指定手机授权页的版本，默认PC，值为1时跳到wap1.0的授权页，为2时同理
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
            'client_id' => $this->conf->tencent->app_key,
            'client_secret' => $this->conf->tencent->app_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri
        );
        $url = self::ACCESSTOKEN . '?' . http_build_query($params);
        $curl = new Su_Curl($url);
        parse_str($curl->get(), $out);
        return $out;
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
        if (isset($auth['access_token']) && isset($auth['openid'])) {
            $info['t_access_token'] = $auth['access_token'];
            $info['t_openid'] = $auth['openid'];
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
        if (isset($auth['t_access_token'])) unset($auth['t_access_token']);
        if (isset($auth['t_openid'])) unset($auth['t_openid']);
        if (empty($auth)) {
            Su_Func::cookie($this->conf->cookie->key, null, -5, '');
        } else {
            $this->setAuth($auth);
        }
    }
}
