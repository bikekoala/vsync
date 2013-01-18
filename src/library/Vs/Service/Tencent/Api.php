<?PHP
/**
 * Vs_Service_Tencent_Api
 * 腾讯围脖api
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Tencent_Api extends Vs_Service_Abstract
{
    const API = 'https://open.t.qq.com/api/'; // 接口url

    /**
     * getUserInfo
     * 获取用户信息
     *
     * @return array
     */
    public function getUserInfo()
    {
        $data = $this->_api('user/info');
        return $data['data'];
    }

    /**
     * isFans
     * 看看你是不是我的粉丝
     *
     * @return bool
     */
    public function isFans()
    {
        $params['names'] = $this->conf['tencent']['account'];
        $params['flat'] = 1; // 检测收听的人
        $r = $this->_api('friends/check', $params);
        return ! (bool) $r['ret'];
    }

    /**
     * getLastTweet
     * 获取最后一条围脖
     *
     * @return array
     */
    public function getLastTweet()
    {
        $data = $this->getUserInfo();
        return reset($data['tweetinfo']);
    }

    /**
     * sentImageTweet
     * 发送一条图片围脖
     *
     * @param string $text
     * @param string $url
     * @param array $data
     * @return array
     */
    public function sentImageTweet($text, $url, $data)
    {
        if (isset($data['geo'])) {
            $params['longitude'] = $data['geo']['longitude']; // 纬度
            $params['latitude'] = $data['geo']['latitude']; // 经度
        }
        $params['content'] = $text;
        $params['pic_url'] = $url; // 经度
        return $this->_api('t/add_pic_url', $params, 'post');
    }

    /**
     * sentTextTweet
     * 发送一条普通文本围脖
     *
     * @param string $text
     * @param array $data
     * @return array
     */
    public function sentTextTweet($text, $data)
    {
        if (isset($data['geo'])) {
            $params['longitude'] = $data['geo']['longitude']; // 纬度
            $params['latitude'] = $data['geo']['latitude']; // 经度
        }
        $params['content'] = $text;
        return $this->_api('t/add', $params, 'post');
    }

    /**
     * uplodeImage
     * 上传一张图片(新浪图片转换为腾讯图片)
     *
     * @param string $url
     * @return string
     */
    public function uplodeImage($url)
    {
        $params['pic_url'] = $url;
        $data = $this->_api('t/upload_pic', $params, 'post');
        return $data['data']['imgurl'];
    }

    /**
     * 发起一个腾讯API请求
     * @param $command 接口名称 如：t/add
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 post|get
     * @return string
     */
    private function _api($command, $params = array(), $method = 'get')
    {
        // 鉴权参数
        $params['access_token'] = $this->getInfo('t_access_token');
        $params['openid'] = $this->getInfo('t_openid');
        $params['oauth_consumer_key'] = $this->conf['tencent']['app_key'];
        $params['oauth_version'] = '2.a';
        $params['clientip'] = Su_Func::ip();
        $params['scope'] = 'all';
        $params['appfrom'] = 'php-sdk2.0beta';
        $params['seqid'] = time();
        $params['serverip'] = $_SERVER['SERVER_ADDR'];
        $params['format'] = 'json';
        $url = self::API . trim($command, '/');

        // 请求接口
        $curl = new Su_Curl($url);
        $data = $curl->rest($params, $method);
        // 检查错误
        $this->_checkErr($data);

        return $data;
    }

    /**
     * _checkErr
     * 检查接口返回数据是否有错误
     *
     * @param mixed $data
     * @return void
     */
    private function _checkErr($data)
    {
        // 链接中断
        if (empty($data)) {
            throw new Exception('连接中断～');
        }

        // 鉴权失败
        if (3 == $data['ret']) {
            switch ($data['errcode']) {
                // accesstoken过期
                case 37 :
                    $msg = '腾讯围脖授权过期，请刷新页面重新登录～';
                    Vs_Service_Tencent_Auth::clearAuthInfo();
                    break;
                // 冷不丁的会出现未知错误，再次刷新即可
                case 41 :
                    $msg = '腾讯围脖接口出现未知的鉴权错误，请刷新页面～';
                    break;
                default :
                    $msg = '腾讯围脖接口读取失败，请刷新页面重新登录～';
                    Vs_Service_Tencent_Auth::clearAuthInfo();
            }
            throw new Exception($msg);
        }
    }
}
