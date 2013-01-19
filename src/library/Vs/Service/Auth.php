<?PHP
/**
 * Vs_Service_Auth
 * 授权处理类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Auth extends Vs_Service_Abstract
{
    /**
     * getAuth
     * 获取记录在cookie中的授权信息
     *
     * @return array
     */
    public function getAuth()
    {
        if (isset($_COOKIE[$this->conf->cookie->key])) {
            return $this->_unserializeAuth($_COOKIE[$this->conf->cookie->key]);
        } else {
            return array();
        }
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
