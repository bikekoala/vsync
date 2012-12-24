<?PHP
/**
 * 取消授权
 */
class Vs_Action_Cauth_Sina extends Vs_Action_Abstract
{
    protected $_needAuth = false;

    public function run()
    {
        $auth = new Vs_Service_Sina_Auth;
        $auth->clearAuthInfo();

        $this->outputJson();
    }
}
