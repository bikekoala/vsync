<?PHP
/**
 * 取消授权
 */
class Vs_Action_Cauth_Tencent extends Vs_Action_Abstract
{
    protected $_needAuth = false;

    public function run()
    {
        $auth = new Vs_Service_Tencent_Auth;
        $auth->clearAuthInfo();

        $this->outputJson();
    }
}
