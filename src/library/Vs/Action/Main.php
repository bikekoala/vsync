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

                $tencent['avator'] = $info['head'] ? $info['head'].'/100' : '';
                $this->response('tencent', $tencent);

                // 检查你是不是大熊的粉丝
                if (! $api->isFans()) {
                    $this->response('exc', '你还不是我球球粉丝呢~');
                }
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

        // 同步类型
        if ($this->auth['tencent'] && $this->auth['sina']) {
            $r = new Vs_Service_Auth;
            $type = $r->getType();
        } else {
            $type = false;
        }
        $this->response('type', $type);
        // 授权信息
        $this->response('auth', $this->auth);
        $this->tpl('index');
    }
}
