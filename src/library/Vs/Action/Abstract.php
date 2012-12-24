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

    public function outputJson($data = array(), $stat = true)
    {
        $params['stat'] = $stat;
        $params['data'] = $data;

        $this->response($params);
        $this->format('json');
    }

    private function _checkAuth()
    {
        $o = new Vs_Service_Tencent_Auth;
        $this->auth['tencent'] = $o->checkAuth();
        $o = new Vs_Service_Sina_Auth;
        $this->auth['sina'] = $o->checkAuth();
    }
}
