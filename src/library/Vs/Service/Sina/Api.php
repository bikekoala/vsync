<?PHP
/**
 * Vs_Service_Sina_Api
 * 新浪围脖api
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Sina_Api extends Vs_Service_Abstract
{
    const API = 'https://api.weibo.com/2/'; // 接口url

    /**
     * getUserInfo
     * 获取用户信息
     *
     * @return array
     */
    public function getUserInfo()
    {
        $params['uid'] = $this->getInfo('s_uid');
        return $this->_api('users/show', $params);
    }

    /**
     * getLastTweet
     * 获取最后一条围脖
     *
     * @return array
     */
    public function getLastTweet()
    {
        $params['count'] = 1;
        $data = $this->_api('statuses/user_timeline', $params);
        return reset($data['statuses']);
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
        //$params['lat'] = $data['latitude']; // 纬度
        //$params['long'] = $data['longitude']; // 经度
        //$params['status'] = $text;
        //$params['url'] = $url . '/460';
        //return $this->_api('statuses/upload_url_text', $params, 'post');

        // todo:高级授权
        return $this->sentTextTweet($text, $data);
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
        $params['lat'] = $data['latitude']; // 纬度
        $params['long'] = $data['longitude']; // 经度
        $params['status'] = $text;
        return $this->_api('statuses/update', $params, 'post');
    }

    /**
     * sendNotification
     * 发送一条私信通知
     *
     * @param string $text
     * @return void
     */
    public function sendNotification($text)
    {
        // todo: 高级接口（需要授权）
    }

    /**
     * commentTweet
     * 评论一条围脖
     *
     * @param string $tid
     * @param string $text
     * @return void
     */
    public function commentTweet($tid, $text)
    {
        $params['comment'] = $text;
        $params['id'] = $tid;
        $params['comment_ori'] = 0;
        $this->_api('comments/create', $params, 'post');
    }

    /**
     * 发起一个新浪API请求
     * @param string $command 接口名称 如：users/show
     * @param array $params 接口参数  array('content'=>'test');
     * @param string $method 请求方式 post|get
     * @return string
     */
    private function _api($command, $params = array(), $method = 'get')
    {
        // 鉴权参数
        $params['access_token'] = $this->getInfo('s_access_token');
        if ('post' === $method) {
            $params = http_build_query($params);
        }

        // 请求接口
        $url = self::API . trim($command, '/') . '.json';
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

        // 报错,模糊处理~
        if (isset($data['error_code'])) {
            $msg = "code:{$data['error_code']},msg:{$data['error']}.请尝试刷新页面并重新登录～";
            throw new Exception($msg);
        }
    }
}
