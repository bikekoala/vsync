<?PHP
class Vs_Service_Tencent_Api extends Vs_Service_Abstract
{
    const API = 'https://open.t.qq.com/api/'; // 接口url

    public function getUserInfo()
    {
        return $this->_api('user/info');
    }

    public function isFans()
    {
        $params['names'] = $this->conf['tencent']['account'];
        $params['flat'] = 1; // 检测收听的人
        $r = $this->_api('friends/check', $params);
        return ! (bool) $r['ret'];
    }

    /**
     * 发起一个腾讯API请求
     * @param $command 接口名称 如：t/add
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 POST|GET
     * @return string
     */
    private function _api($command, $params = array(), $method = 'GET')
    {
        //鉴权参数
        $params['access_token'] = $_SESSION['t_access_token'];
        $params['openid'] = $_SESSION['t_openid'];
        $params['oauth_consumer_key'] = $this->conf['tencent']['app_key'];
        $params['oauth_version'] = '2.a';
        $params['clientip'] = Su_Func::ip();
        $params['scope'] = 'all';
        $params['appfrom'] = 'php-sdk2.0beta';
        $params['seqid'] = time();
        $params['serverip'] = $_SERVER['SERVER_ADDR'];
        $url = self::API . trim($command, '/');

        //请求接口
        $curl = new Su_Curl($url);
        return $curl->rest($params, $method);
    }
}
