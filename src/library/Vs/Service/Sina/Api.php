<?PHP
class Vs_Service_Sina_Api extends Vs_Service_Abstract
{
    const API = 'https://api.weibo.com/2/'; // 接口url

    public function getUserInfo()
    {
        $params['uid'] = $_SESSION['s_uid'];
        return $this->_api('users/show', $params);
    }

    /**
     * 发起一个新浪API请求
     * @param $command 接口名称 如：users/show
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 POST|GET
     * @return string
     */
    private function _api($command, $params = array(), $method = 'GET')
    {
        //鉴权参数
        $params['access_token'] = $_SESSION['s_access_token'];
        $url = self::API . trim($command, '/') . '.json';

        //请求接口
        $curl = new Su_Curl($url);
        return $curl->rest($params, $method);
    }
}
