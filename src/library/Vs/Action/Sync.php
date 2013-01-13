<?PHP
/**
 * 微博同步方式
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

    private function _checkAuth()
    {
        if (! $this->auth['tencent'] || ! $this->auth['sina']) {
            $this->outputJson('请重新登录！', false);
        }
    }

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
