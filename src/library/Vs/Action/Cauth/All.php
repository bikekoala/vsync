<?PHP
/**
 * Vs_Action_Cauth_All
 * 全部取消授权
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Action_Cauth_All extends Vs_Action_Abstract
{
    protected $_needAuth = true;

    /**
     * run
     * 执行
     *
     * @return void
     */
    public function run()
    {
        // 停止自动同步
        if ($this->auth['tencent'] && $this->auth['sina']) {
            $r = new Vs_Service_Auth;
            $r->stopSync(true);
        }

        // 清除腾讯授权
        $r = new Vs_Service_Tencent_Auth;
        $r->clearAuth();

        // 清除新浪授权
        $r = new Vs_Service_Sina_Auth;
        $r->clearAuth();

        // 跳转到首页
        $this->redirect(INDEX);
    }
}
