<?PHP
//系统目录                     
define('SYS_PATH', realpath(dirname(__FILE__) . '/../../../'));
//webRoot目录
define('WWW_PATH', SYS_PATH . '/www');
//docs目录
define('DOCS_PATH', SYS_PATH . '/docs');
//log目录
define('LOG_PATH', SYS_PATH . '/log');
//temp目录
define('TEMP_PATH', SYS_PATH . '/tmp');
//当前host
define('HOST', $_SERVER['HTTP_HOST']);
//当前url
define('URL', "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}");
//基础配置
include dirname(__FILE__) . '/conf.php';
ini_set('display_errors', $conf['debug']);
//设置包含路径
set_include_path(get_include_path() . ':' . $conf['include_path']); 
//引入su内核
include 'Su/Facade.php';
