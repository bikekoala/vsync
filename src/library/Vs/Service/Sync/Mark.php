<?PHP
/**
 * 标记同步方式，并记录所需凭证
 */
class Vs_Service_Sync_Mark extends Vs_Service_Sync_Abstract
{
    public function duplex()
    {
        $this->_setType($this->conf['sync']['duplex']);
    }

    public function tencentToSina()
    {
        $this->_setType($this->conf['sync']['t2s']);
    }

    public function sinaToTencent()
    {
        $this->_setType($this->conf['sync']['s2t']);
    }

    public function close()
    {
        $this->_setType($this->conf['sync']['close']);
    }

    private function _setType($type)
    {
        $id = $this->getSyncId();
        $stat = Vs_Entity_Sync::single()->update($id, $type);

        if (! $stat) {
            $tid = Vs_Entity_Tencent::single()->add($_SESSION['t_access_token'], $_SESSION['t_refresh_token'], $_SESSION['t_openid']);
            $sid = Vs_Entity_Sina::single()->add($_SESSION['s_access_token'], $_SESSION['s_uid']);
            Vs_Entity_Sync::single()->add($id, $tid, $sid, $type);
        }
    }
}
