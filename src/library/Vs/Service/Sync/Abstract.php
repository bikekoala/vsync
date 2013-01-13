<?PHP
class Vs_Service_Sync_Abstract extends Vs_Service_Abstract
{
    /**
     * 组装同步唯一标识
     */
    public function getSyncId()
    {
        return md5($_SESSION['t_access_token'] . $_SESSION['s_access_token']);
    }
}
