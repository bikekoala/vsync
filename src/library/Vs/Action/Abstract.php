<?PHP
abstract class Vs_Action_Abstract extends Su_Ctrl_Action
{
    protected $_needAuth = true;

    public $auth = array();

	abstract public function run();

	public function execute()
	{
        session_start();

        // 验证授权
        $this->_needAuth && $this->_checkAuth();

        // 执行请求
		$this->run();
	}

    private function _checkAuth()
    {
        $o = new Vs_Service_Tencent_Oauth;
        $this->auth['tencent'] = $o->checkAuth();
        $o = new Vs_Service_Sina_Oauth;
        $this->auth['sina'] = $o->checkAuth();
    }
}
