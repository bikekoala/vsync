<?PHP
/**
 * 腾讯微博API调用类
 */
class Vs_Service_Api_Tencent extends Vs_Service_Abstract
{
    /**
     * 接口url
     */
    const API = 'http://open.t.qq.com/api/';

    /**
     * 获取自己的详细资料
     */
    public function getUserInfo()
    {
        $r = $this->api('user/info');
        if (0 === $r['ret']) {
            return $r['data'];
        } else return false;
    }
    
    /**
     * 检查是否是指定用户的听众
     * @return bool
     */
    public function isIdol()
    {
        $params = array('names' => $this->conf->tencent->account, 'flag' => 2);
        $r = $this->api('friends/check', $params);
        if (0 === $r['ret']) {
            return $r['data'][$this->conf->tencent->account]['isidol'];
        } return false;
    }

    /**
     * 发起一个腾讯API请求
     * @param $command 接口名称 如：t/add
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 POST|GET
     * @return array
     */
    private function api($command, $params = array(), $method = 'GET')
    {
        //鉴权参数
        $params['oauth_consumer_key'] = $this->conf->tencent->app_key;
        $params['access_token'] = $_SESSION['t_access_token'];
        $params['openid'] = $_SESSION['t_openid'];
        $params['clientip'] = Su_Func::ip();
        $params['oauth_version'] = '2.a';
        $params['scope'] = 'all';
        $params['format'] = 'json';
        $url = self::API . trim($command, '/');

        //请求接口
        $curl = new Su_Curl($url);
        return $curl->rest($params, $method);
    }
}
