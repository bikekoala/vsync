<?PHP
/**
 * Vs_Service_Sync_Mark
 * 记录围脖同步方式
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Sync_Mark extends Vs_Service_Sync_Abstract
{
    /**
     * duplex
     * 双向同步围脖
     *
     * @return void
     */
    public function duplex()
    {
        $this->_process($this->conf['sync']['duplex']);
    }

    /**
     * tencentToSina
     * 腾讯围脖->新浪围脖
     *
     * @return void
     */
    public function tencentToSina()
    {
        $this->_process($this->conf['sync']['t2s']);
    }

    /**
     * sinaToTencent
     * 新浪围脖->腾讯围脖
     *
     * @return void
     */
    public function sinaToTencent()
    {
        $this->_process($this->conf['sync']['s2t']);
    }

    /**
     * close
     * 关闭同步
     *
     * @return void
     */
    public function close()
    {
        $this->_process($this->conf['sync']['close']);
    }

    /**
     * _process
     * 处理同步请求
     *
     * @param int $type
     * @return void
     */
    private function _process($type)
    {
        // 更新同步记录
        $this->_setSync($type);

        // 马上同步一下
        $this->sync($type);

        // 提醒重新授权
        $this->notify();
    }

    /**
     * _setSync
     * 更新同步数据，用于异步同步
     *
     * @param int $type
     * @return void
     */
    private function _setSync($type)
    {
        $info = $this->getInfo();
        $id = $this->getSyncId();
        $params['t_access_token'] = $info['t_access_token'];
        $params['t_openid'] = $info['t_openid'];
        $params['s_access_token'] = $info['s_access_token'];
        $params['s_uid'] = $info['s_uid'];
        $params['type'] = $type;
        $params['is_notify'] = 0;
        $params['exc_times'] = 0;
        $rec = Vs_Entity_Sync::single()->get($id);
        if ($rec) {
            // update, the same access token
            if ($rec['t_access_token'] === $params['t_access_token'] &&
                $rec['s_access_token'] === $params['s_access_token']) {
                Vs_Entity_Sync::single()->update($id, $params);
            // update, the new access token
            } else {
                $params['time'] = time();
                Vs_Entity_Sync::single()->update($id, $params);
            }
        } else {
            // insert
            $params['id'] = $id;
            $params['time'] = time();
            Vs_Entity_Sync::single()->add($params);
        }
    }
}
