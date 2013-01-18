<?PHP
/**
 * Vs_Action_Sync
 * 设置微博同步方式
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Action_Sync extends Vs_Action_Abstract
{
	public function run()
	{
        // 验证授权
        $this->_checkAuth();

        // 记录同步方式
        try {
            $this->_mark();
            $this->outputJson('设置同步成功～');
        } catch (Exception $e) {
            $this->outputJson($e->getMessage(), false);
        }
    }

    /**
     * _checkAuth
     * 验证授权
     *
     * @return void
     */
    private function _checkAuth()
    {
        if (! $this->auth['tencent'] || ! $this->auth['sina']) {
            $this->outputJson('刷新页面，重新登录下吧~', false);
        }
    }

    /**
     * _mark
     * 记录围脖同步方式
     *
     * @return void
     */
    private function _mark()
    {
        $mark = new Vs_Service_Sync_Mark;

        switch ($this->request['type']) {
            case 'duplex' :
                return $mark->duplex();
            case 'close' :
                return $mark->close();
            case 's2t' :
                return $mark->sinaToTencent();
            case 't2s' :
                return $mark->tencentToSina();
            default :
                throw new Exception('无效的同步方式！');
        }
    }
}
