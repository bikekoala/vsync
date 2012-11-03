<?PHP
class Vs_Action_Main extends Vs_Action_Abstract
{
	public function run()
	{
      if (isset($_SESSION['t_access_token'])) {
            echo 'ok.';
       } else {
           echo '<a href="?do=Oauth.Tencent">球球登录</a>';
       }
	}
}
