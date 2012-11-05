<?PHP
/**
 * 腾讯微博API调用类
 */
class Vs_Service_Tencent_Api extends Vs_Service_Tencent_ApiAbstract
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
        return $this->api('user/info');
    }
    
    /**
     * 检查是否是指定用户的听众
     * @return bool
     */
    public function isIdol()
    {
        $params = array('names' => $this->conf->tencent->account, 'flag' => 1);
        return $this->api('friends/check', $params);
    }
}
