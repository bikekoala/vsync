<?PHP
/**
 * 腾讯微博API调用类
 */
class Vs_Service_Api_Tencent extends Vs_Service_Abstract
{
    /**
     * 发起一个腾讯API请求
     * @param $command 接口名称 如：t/add
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 POST|GET
     * @return array
     */
    public static function api($command, $params = array(), $method = 'GET')
    {
        //鉴权参数
        $params['oauth_consumer_key'] = $this->conf->tencent->app_key;
        $params['access_token'] = $_SESSION['t_access_token'];
        $params['openid'] = $_SESSION['t_openid'];
        $params['clientip'] = Su_Func::ip()
        $params['oauth_version'] = '2.a';
        $params['scope'] = 'all';
        $params['format'] = 'json';
        $url = $this->conf->tencent->api . trim($command, '/');

        //请求接口
        $curl = new Su_Curl($url);
        return $curl->rest($params, $method);
    }
}
