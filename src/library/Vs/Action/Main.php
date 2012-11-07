<?PHP
class Vs_Action_Main extends Vs_Action_Abstract
{
	public function run()
	{
        session_start();

        /**
         * Sina OAuth
         */

        // 清除授权
        if (0) {
            $oauth = new Vs_Service_Sina_Oauth;
            $oauth->clearOauthInfo();
        }

        // 若取得授权
        if (isset($_SESSION['s_access_token'])) {
            echo "U got it.";
        } else {
            echo '<a href="?do=Oauth.Sina">围脖登录</a>';
        }

        /**
         * Tencent OAuth
         */
        /*

        // 清除授权
        if (0) {
            $oauth = new Vs_Service_Tencent_Oauth;
            $oauth->clearOauthInfo();
        }

        $api = new Vs_Service_Tencent_Api;
        // 若取得授权
        if (isset($_SESSION['t_access_token'])) {
            // 检查你是不是大熊的粉丝
            $r = $api->call('isIdol');
            if ( ! $r['ret'] && current($r['data'])) {
                // 获取个人资料
                $info = $api->call('getUserInfo');
                if ( ! $info['ret']) {
                    echo "<img src='{$info['data']['head']}/100' width=100 />";
                } else {
                    echo '你隐身了么~<br />';
                }
            } else {
                echo '朋友俺不认识你～<br />';
            }
        } else {
            echo '<a href="?do=Oauth.Tencent">球球登录</a>';
        }
        */
    }
}
