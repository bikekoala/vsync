<?PHP
class Vs_Action_Main extends Vs_Action_Abstract
{
	public function run()
	{
        session_start();

        // 清除授权
        if (0) {
            $oauth = new Vs_Service_Oauth_Tencent;
            $oauth->clearOAuthInfo();
            exit;
        }

        // Tencent OAuth
        $api = new Vs_Service_Api_Tencent;
        if (isset($_SESSION['t_access_token'])) {
            // 检查你是不是大熊的粉丝
            if ($api->isIdol()) {
                // 获取个人资料
                if ($info = $api->getUserInfo()) {
                    echo "<img src='{$info['head']}/100' width=100 />";
                } else {
                    echo '你隐身了么~<br />';
                }
            } else {
                echo '朋友俺不认识你～<br />';
            }
        } else {
            echo '<a href="?do=Oauth.Tencent">球球登录</a>';
        }
    }
}
