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
        $info = empty($this->_info) ? $this->_getAuth() : $this->_info;
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
     * _getAuth
     * 获取记录在cookie中的授权
     *
     * @return array
     */
    protected function _getAuth()
    {
        if (isset($_COOKIE[$this->conf->cookie->key])) {
            return $this->_unserializeAuth($_COOKIE[$this->conf->cookie->key]);
        } else {
            return array();
        }
    }

    /**
     * _unserializeAuth
     * 解序列化cookie授权
     *
     * @param array $auth
     * @return array
     */
    protected function _unserializeAuth($auth)
    {
        $auth = Su_Func::encrypt($auth, $this->conf->cookie->encrypt_key, 'DECODE');
        $tmps = explode('|', $auth);
        if (count($tmps) !== 3)  {
            throw new Exception('Invalid su_auth.', 400);
        }
        $arr['auth'] = $tmps[0];
        $arr['timestamp'] = $tmps[1];
        if (crc32(implode('|', $arr) . $this->conf->cookie->serial_secret) != $tmps[2]) {
            throw new Exception('Auth validate fail.', 400);
        }
        return unserialize($arr['auth']);
    }

    /**
     * _serializeAuth
     * 序列化cookie授权
     *
     * @param array $auth
     * @return array
     */
    protected function _serializeAuth($auth)
    {
        $arr[] = serialize($auth);
        $arr[] = time();
        $str = implode('|', $arr);
        $str = $str . '|' . crc32($str . $this->conf->cookie->serial_secret);
        return Su_Func::encrypt($str, $this->conf->cookie->encrypt_key);
    }
}
