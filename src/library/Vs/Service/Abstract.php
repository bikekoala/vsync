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
        static $id; // 静态化
        if ($id) {
            return $id;
        }

        $info = $this->getInfo();
        $id = md5($info['t_openid'] . $info['s_uid']);
        return $id;
    }

    /**
     * stopSync
     * 停止自动同步
     *
     * @return void
     */
    public function stopSync()
    {
        $id = $this->getSyncId();

        $params['type'] = $this->conf->sync->close;
        Vs_Entity_Sync::single()->update($id, $params);
    }

    /**
     * setExc
     * 记录异常次数，超限时停止自动同步
     *
     * @return void
     */
    public function setExc()
    {
        $id = $this->getSyncId();
        $rec = Vs_Entity_Sync::single()->get($id);

        // 当配置限制不为0 或者 实际次数超限时停止自动同步
        if (0 == $this->conf->exc_times_limit || $rec['exc_times'] < $this->conf->exc_times_limit) {
            $params['exc_times'] = $rec['exc_times'] == 0 ? 1 : ++$rec['exc_times'];
            Vs_Entity_Sync::single()->update($id, $params);
        } else {
            $this->stopSync();
        }
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
