<?PHP
/**
 * 腾讯微博的授权验证调用类
 * access_token 初级7天，高级15天
 * refresh_token 3个月
 */
class Vs_Service_Tencent_Oauth extends Vs_Service_Abstract
{
    /**
     * 请求code,accesstoken的接口url
     */
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
     * 刷新授权信息
     */
    public function refreshToken()
    {
        $params = array(
            'client_id' => $this->conf->tencent->app_key,
            'client_secret' => $this->conf->tencent->app_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $_SESSION['t_refresh_token']
        );
        $url = self::ACCESSTOKEN . '?' . http_build_query($params);
        $curl = new Su_Curl($url);
        parse_str($curl->get(), $out);
        if (isset($out['access_token'])) {
            $this->setOauthInfo($out);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 设置授权
     * @param $info 需要记录的SESSION
     */
    public function setOauthInfo($info)
    {
        $_SESSION['t_access_token'] = $info['access_token'];
        $_SESSION['t_refresh_token'] = $info['refresh_token'];
        $_SESSION['t_openid'] = $info['openid'];
    }

    /**
     * 清除授权
     */
    public function clearOauthInfo()
    {
        if (isset($_SESSION['t_access_token'])) unset($_SESSION['t_access_token']);
        if (isset($_SESSION['t_refresh_token'])) unset($_SESSION['t_refresh_token']);
        if (isset($_SESSION['t_openid'])) unset($_SESSION['t_openid']);
    }
}
