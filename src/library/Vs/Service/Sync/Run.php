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
     * @param array $info
     * @return void
     */
    public function zouni($info)
    {
        try {
            // 设置信息
            $this->setInfo($info);

            // 马上同步一下
            $this->sync($info['type']);
            // 提醒重新授权
            $this->notify(); 
        } catch (Exception $e) {
            // 记录异常次数，超限时停止自动同步
            $this->setExc();
            throw new Exception($e->getMessage());
        }
    }
}
