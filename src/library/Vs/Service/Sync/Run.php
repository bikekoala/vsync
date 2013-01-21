<?PHP
/**
 * Vs_Service_Sync_Run
 * 定时同步
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Sync_Run extends Vs_Service_Sync_Abstract
{
    /**
     * zouni
     * 执行同步请求
     *
     * @return void
     */
    public function zouni()
    {
        $list = Vs_Entity_Sync::single()->getList();
        foreach ($list as $item) {
            try {
                $this->setInfo($item); // 设置信息
                $this->sync($item['type']); // 马上同步一下
                $this->notify(); // 提醒重新授权
            } catch (Exception $e) {
                // 记录异常次数，超限时停止自动同步
                $this->setExc();
            }
        }
    }
}
