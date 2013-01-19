<?PHP
/**
 * Vs_Service_Abstract
 * 服务抽象类，提供基本方法
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Abstract
{
    public $conf; // 配置信息

    private $_info; // 授权等信息

    public function __construct()
    {
        $this->conf = Vs_Config::single();
    }

    /**
     * getInfo
     * 获得信息
     *
     * @param string $key
     * @return mixed
     */
    public function getInfo($key = '')
    {
        // 动态设置info信息
        if (empty($this->_info)) {
            $r = new Vs_Service_Auth;
            $info = $r->getAuth();
        } else {
            $info = $this->_info;
        }

        if ('' === $key) {
            return $info;
        } else {
            if (isset($info[$key])) {
                return $info[$key];
            } else {
                return false;
            }
        }
    }

    /**
     * setInfo
     * 设置信息
     *
     * @param array $data
     * @return void
     */
    public function setInfo($data)
    {
        $this->_info = $data;
    }

    /**
     * getSyncId
     * 组装同步唯一标识
     *
     * @return string
     */
    public function getSyncId()
    {
        $info = $this->getInfo();
        return md5($info['t_openid'] . $info['s_uid']);
    }

    /**
     * getExpireTime
     * 计算最短的过期时间秒数
     *
     * @return int
     */
    public function getExpireTime()
    {
        $sinaExpireTime = $this->conf->sina->expire_time;
        $tencentExpireTime = $this->conf->tencent->expire_time;
        return min($sinaExpireTime, $tencentExpireTime) * 24 * 60 * 60;
    }
}
