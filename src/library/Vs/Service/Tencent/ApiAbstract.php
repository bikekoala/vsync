<?PHP
abstract class Vs_Service_Tencent_ApiAbstract extends Vs_Service_Abstract
{
    /**
     * 执行一次接口调用
     * @param $func 接口函数名称
     * @param $params 需要传递的参数数组
     */
    public function call($func, $params = array())
    {
        // 调用接口,然后检查返回值，失败则刷新或清除授权信息
        $r = call_user_func_array(array($this, $func), $params);
        $r = $this->check($r);
        // 若刷新授权成功,再来一次
        if (true === $r) {
            $r = call_user_func_array(array($this, $func), $params);
            $r = $this->check($r);
        }
        return $r;
    }

    /**
     * 检查API返回值并处理错误
     * @param $r 调用API的返回值
     */
    protected function check($r)
    {
        // 成功返回
        if (0 === $r['ret']) {
            return $r;
        } else {
            $oauth = new Vs_Service_Tencent_Oauth;
            // 鉴权失败 & accesstoken过期
            if (3===$r['ret'] && 37===$r['errcode']) {
                // 刷新授权
                return (bool) $oauth->refreshToken();
            // 其他错误
            } else {
                // 清除授权信息,冷不丁的会出现未知错误，再次刷新即可
                if ( ! (3===$r['ret'] && 41===$r['errcode'])) {
                    $oauth->clearOauthInfo();
                }
                return false;
            }
        }
    }

    /**
     * 发起一个腾讯API请求
     * @param $command 接口名称 如：t/add
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 POST|GET
     * @return array
     */
    protected function api($command, $params = array(), $method = 'GET')
    {
        // 鉴权参数
        $params['oauth_consumer_key'] = $this->conf->tencent->app_key;
        $params['access_token'] = $_SESSION['t_access_token'];
        $params['openid'] = $_SESSION['t_openid'];
        $params['clientip'] = Su_Func::ip();
        $params['oauth_version'] = '2.a';
        $params['scope'] = 'all';
        $params['format'] = 'json';
        $url = static::API . trim($command, '/');

        // 请求接口
        $curl = new Su_Curl($url);
        return $curl->rest($params, $method);
    }
}
