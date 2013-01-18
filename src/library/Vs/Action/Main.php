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
            // 若取得授权
            if ($this->auth['tencent']) {
                $api = new Vs_Service_Tencent_Api;
                // 检查你是不是大熊的粉丝
                if ($api->isFans()) {
                    // 获取个人资料
                    $info = $api->getUserInfo();
                    echo "<img src='{$info['head']}/100' width=100 />";
                } else {
                    echo '朋友俺不认识你～<br />';
                }
            } else {
                echo '<a href="?do=Oauth.Tencent">球球登录</a>';
            }

            /**
             * Sina
             */
            // 若取得授权
            if ($this->auth['sina']) {
                $api = new Vs_Service_Sina_Api;
                $info = $api->getUserInfo();
                //if成功
                echo "<img src='{$info['avatar_large']}' width=100 />";
            } else {
                echo '<a href="?do=Oauth.Sina">围脖登录</a>';
            }
        } catch (Exception $e) {
            $this->outputJson($e->getMessage(), false);
        }
    }
}
