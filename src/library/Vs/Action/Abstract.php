<?PHP
/**
 * Vs_Action_Abstract
 * action抽象类,提供共用方法和配置
 *
 * @author popfeng <popfeng@yeah.net>
 */
abstract class Vs_Action_Abstract extends Su_Ctrl_Action
{
    protected $_needAuth = true; // 请求是否需要授权

    public $auth = array(); // 存放应用授权状态

	/**
	 * run
     * 执行，子类需实现
	 *
	 * @return void
	 */
	abstract public function run();

	/**
	 * execute
     * 执行
	 *
	 * @return void
	 */
	public function execute()
	{
        session_start();

        // 验证授权
        $this->_needAuth && $this->_getAuth();

        // 执行请求
		$this->run();
	}

    /**
     * outputJson
     * 以json方式输出
     *
     * @param data $data
     * @param bool $stat
     * @return string
     */
    public function outputJson($data, $stat = true)
    {
        $params['stat'] = $stat;
        if (is_array($data)) {
            $params = array_merge($params, $data);
        } else {
            $params['msg'] = $data;
        }

        exit(json_encode($params));
    }

    /**
     * _getAuth
     * 获取应用授权状态
     *
     * @return void
     */
    private function _getAuth()
    {
        $o = new Vs_Service_Abstract;
        $info = $o->getInfo();

        $this->auth['tencent'] = ! empty($info['t_access_token']);
        $this->auth['sina'] = ! empty($info['s_access_token']);
    }
}
