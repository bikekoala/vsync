<?PHP
/**
 * 标记同步方式，并记录所需凭证
 */
class Vs_Service_Sync_Mark extends Vs_Service_Sync_Abstract
{
    public function tencentToSina()
    {
        $this->_setType(3);
    }

    private function _setType($type)
    {
        $id = $this->getSyncId();
        // test
        Vs_Entity_Sync::single()->add($id, 1, 2, 3);
    }
}
