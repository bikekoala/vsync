<?PHP
class Vs_Action_Main extends Vs_Action_Abstract
{
	public function run()
	{
        /**
         * Sina
         */
        // 若取得授权
        if ($this->auth['sina']) {
            $api = new Vs_Service_Sina_Tweet_Api;
            $info = $api->getUserInfo();
            //if成功
            echo "<img src='{$info['avatar_large']}' width=100 />";
        } else {
            echo '<a href="?do=Oauth.Sina">围脖登录</a>';
        }

        /**
         * Tencent
         */
        // 若取得授权
        if ($this->auth['tencent']) {
            $api = new Vs_Service_Tencent_Tweet_Api;
            // 检查你是不是大熊的粉丝
            if ($api->isFans()) {
                // 获取个人资料
                $info = $api->getUserInfo();
                echo "<img src='{$info['data']['head']}/100' width=100 />";
            } else {
                echo '朋友俺不认识你～<br />';
            }
        } else {
            echo '<a href="?do=Oauth.Tencent">球球登录</a>';
        }
    }
}
