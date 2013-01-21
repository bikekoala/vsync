<?PHP
// 命令行模式时
if (substr(php_sapi_name(), 0, 3) === 'cli') {
	define('CLI', true);
} else {
	define('CLI', false);
}
//系统目录                     
define('SYS_PATH', realpath(__DIR__ . '/../../../'));
//webRoot目录
define('WWW_PATH', SYS_PATH . '/www');
//docs目录
define('DOCS_PATH', SYS_PATH . '/docs');
//log目录
define('LOG_PATH', SYS_PATH . '/log');
//temp目录
define('TEMP_PATH', SYS_PATH . '/tmp');
//当前首页
if (CLI) {
	$_SERVER['REMOTE_ADDR'] = '216.12.210.106';
    define('INDEX', "http://sukai.me/pureage/");
} else {
    define('INDEX', "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}");
}
//基础配置
include __DIR__ . '/conf.php';
ini_set('display_errors', $conf['debug']);
//设置包含路径
set_include_path(get_include_path() . ':' . $conf['include_path']); 
//引入su内核
include 'Su/Facade.php';
