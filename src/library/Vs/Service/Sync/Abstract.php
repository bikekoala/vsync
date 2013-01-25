<?PHP
/**
 * Vs_Service_Sync_Abstract
 * 同步共用方法
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Vs_Service_Sync_Abstract extends Vs_Service_Abstract
{
    private $_ttid; // 最近一条腾讯围脖id

    private $_stid; // 最近一条新浪围脖id

    /**
     * sync
     * 执行同步
     *
     * @param int $type
     * @return void
     */
    public function sync($type)
    {
        // 初始化授权信息
        $id = $this->getSyncId();
        $rec = Vs_Entity_Sync::single()->get($id);
        $this->setInfo($rec);

        // 调用
        $class = array_flip($this->conf['sync']);
        $class = '_' . $class[$type];
        $this->$class();
    }

    /**
     * notify
     * 发送私信，提醒注意重新授权
     *
     * @param int $days 提前几天？
     * @return void
     */
    public function notify($days = 1)
    {
        // 检查有没提醒过
        if (! $info['is_notify']) {
            $walkTime = time() - $info['time']; // 授权的进行时长
            $notifyTime = $this->getExpireTime() - $days*24*60*60; // 提醒时长

            // 逾期
            if ($walkTime >= $notifyTime) {
                // 私信通知
                $info = $this->getInfo();
                $url = INDEX . '?do=cauth.all';
                if ($this->_ttid) {
                    $msg = sprintf($this->conf->notification_text, $this->conf->tencent->account, $url);
                    $api = new Vs_Service_Tencent_Api($info);
                    $api->commentTweet($this->_ttid, $msg);
                }
                if ($this->_stid) {
                    $msg = sprintf($this->conf->notification_text, $this->conf->sina->account, $url);
                    $api = new Vs_Service_Sina_Api($info);
                    $api->commentTweet($this->_stid, $msg);
                }

                // 标记已通知
                $params['is_notify'] = 1;
                Vs_Entity_Sync::single()->update($id, $params);
            }
        }
    }

    /**
     * duplex
     * 双向各同步一条围脖
     *
     * @return void
     */
    private function _duplex()
    {
        $info = $this->getInfo();
        // 获得最新一条腾讯围脖
        $api = new Vs_Service_Tencent_Api($info);
        $tTweet = $api->getLastTweet();
        // 获得最新一条新浪围脖
        $api = new Vs_Service_Sina_Api($info);
        $sTweet = $api->getLastTweet();

        $this->_s2t($sTweet); // 新浪->腾讯
        $this->_t2s($tTweet); // 腾讯->新浪
    }

    private function _close()
    {
        $id = $this->getSyncId();
        $params['type'] = 0;
        $params['time'] = time();
        Vs_Entity_Sync::single()->update($id, $params);
    }

    /**
     * t2s
     * 腾讯->新浪，单向同步一条围脖
     *
     * @param array $tweet
     * @return void
     */
    private function _t2s($tweet = array())
    {
        $info = $this->getInfo();
        // 获得最新一条腾讯围脖
        if (empty($tweet)) {
            $api = new Vs_Service_Tencent_Api($info);
            $tweet = $api->getLastTweet();
        }
        $this->_ttid = $tweet['id'];

        // 避免重复发送
        if ($info['t_tweet_id'] == $tweet['id']) return;

        // 发送一条新浪围脖
        $api = new Vs_Service_Sina_Api($info);
        if (isset($tweet['image'])) {
            $api->sentImageTweet($tweet['text'], reset($tweet['image']), $tweet);
        } else {
            $api->sentTextTweet($tweet['text'], $tweet);
        }

        // 更新腾讯围脖记录
        $params['t_tweet_id'] = $tweet['id'];
        $params['counter'] = $info['counter'] == 0 ? 1 : ++$info['counter'];
        $params['time'] = time();
        Vs_Entity_Sync::single()->update($id, $params);
    }

    /**
     * s2t
     * 新浪->腾讯，单向同步一条围脖
     *
     * @param array $tweet
     * @return void
     */
    private function _s2t($tweet = array())
    {
        $info = $this->getInfo();
        // 获得最新一条新浪围脖
        if (empty($tweet)) {
            $api = new Vs_Service_Sina_Api($info);
            $tweet = $api->getLastTweet();
        }
        $this->_stid = $tweet['idstr'];

        // 避免重复发送
        if ($info['s_tweet_id'] == $tweet['idstr']) return;

        // 发送一条腾讯围脖
        $api = new Vs_Service_Tencent_Api($info);
        if (isset($tweet['original_pic'])) {
            $imgurl = $api->uplodeImage($tweet['original_pic']);
            $api->sentImageTweet($tweet['text'], $imgurl, $tweet);
        } else {
            $api->sentTextTweet($tweet['text'], $tweet);
        }

        // 更新新浪围脖记录
        $params['s_tweet_id'] = $tweet['idstr'];
        $params['counter'] = $info['counter'] == 0 ? 1 : ++$info['counter'];
        $params['time'] = time();
        Vs_Entity_Sync::single()->update($id, $params);
    }
}
