<?PHP
/**
 * Vs_Action_Main
 * 展示页面
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Action_Main extends Vs_Action_Abstract
{
	/**
	 * run
     * 执行
	 *
	 * @return void
	 */
	public function run()
	{
        try {
            /**
             * Tencent
             */
            if ($this->auth['tencent']) {
                $api = new Vs_Service_Tencent_Api;
                $info = $api->getUserInfo(); // 获取个人资料
                $isFans = $api->isFans(); // 检查你是不是大熊的粉丝

                $tencent['avator'] = $info['head'];
                $tencent['is_fans'] = $isFans;
                $this->response('tencent', $tencent);
            }

            /**
             * Sina
             */
            if ($this->auth['sina']) {
                $api = new Vs_Service_Sina_Api;
                $info = $api->getUserInfo();

                $sina['avator'] = $info['avatar_large'];
                $this->response('sina', $sina);
            }
        } catch (Exception $e) {
            $this->response('exc', $e->getMessage());
        }

        $this->response('auth', $this->auth);
        $this->tpl('index');
    }
}
