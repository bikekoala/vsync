<?PHP
include dirname(__FILE__) . '/conf/common.php';
ini_set('display_errors', $conf['debug']);
Su_Facade::startup($conf);
