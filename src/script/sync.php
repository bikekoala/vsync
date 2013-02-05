<?PHP
/*
// check the php env-mode
if (substr(php_sapi_name(), 0, 3) !== 'cli') {
    exit('This Programe can only be run in CLI mode');
}
*/
// set up the environment
ini_set('display_errors', false);
ini_set('max_execution_time', 1800);
// send mail when death
include __DIR__ . '/PHPMailer/class.phpmailer.php';
$status = false;
register_shutdown_function('sendmail');
// init
include realpath(__DIR__ . '/../') . '/www/conf/common.php';
Su_Facade::init($conf);

// Process
// 验证参数
if (! isset($argv[1])) {
    $msg = '需要传递一个参数: “a,b”，a是任务总数，b是当前任务序号。';
    exit($msg);
}
$params = explode(',', $argv[1]);
if ((int) $params[0] == 0 || (int) $params[1] == 0) {
    $msg = '参数必须为非零正整数。';
    exit($msg);
}
if ($params[0] < $params[1]) {
    $msg = '当前任务序号不可大于任务总数。';
    exit($msg);
}
$total = (int) $params[0];
$cur = (int) $params[1];

// 分配任务，同步开始
$sync = new Vs_Service_Sync_Run;
$list = Vs_Entity_Sync::single()->getList();
for ($i=$cur-1, $l=count($list); $i<$l; $i+=$total) {
    try {
        $sync->zouni($list[$i]);
    } catch (Exception $e) {
        $msg = $e->getMessage();
        sendmail();
    }
}

$status = true;

// sendmail func
function sendmail() {
    global $status;
    global $msg;
    if (! $status) {
        $title = '老板你好～';
        $msg .= '

            -------------------------------------------------------------------
            正面看 我是穷光蛋
            背面看 我是流浪汉
            我享受孤独总人在旅途 我女朋友说我没前途
            我不主动不拒绝不要脸
            我艳遇多的可以写本书
            我是最牛B的背包客
            我走过墨脱爬过K2
            我想自由自我自娱自乐自唱自歌 纵然跌倒我不服输
            我向来只爱陌生人
            我从来不走寻常路';
        $mail = new PHPMailer();
        $mail->CharSet = 'utf-8';
        $mail->SetFrom('pureage@sukai.me', '白纸年华长工');
        $mail->AddAddress('popfeng@yeah.net', '树袋大熊');
        $mail->Subject = $title;
        $mail->MsgHTML(nl2br($msg));
        $mail->Send();
    }
}
