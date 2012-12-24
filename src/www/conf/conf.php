<?PHP
/**
 *  常规 
 */
$conf['include_path'] = SYS_PATH . '/src/library:' . SYS_PATH . '/src/thirdparty:';
$conf['environment'] = 'production';
$conf['debug'] = true;

/**
 * 错误记录
 * level 0-7 EMERG, ALERT, CRIT, ERROR, WARN, NOTICE, INFO, DEBUG
 */
$conf['notice_log']['name'] = 'infolog';
$conf['notice_log']['write'] = SYS_LOG . '/log.douban';
$conf['notice_log']['level'] = 7;

/**
 *  控制器配置
 */
// 程序执行流
$conf['ctrl_front']['phase']['flow'] = array('INPUT', 'ADAPTER', 'DISPATCH', 'OUTPUT');
// 是否自动捕获action执行的异常
$conf['ctrl_front']['phase']['dispatch']['catch_error'] = true;
// 应用的请求空间
$conf['ctrl_front']['phase']['adapter']['prefix'] = 'Vs_Action_';
// 应用的请求默认Action
$conf['ctrl_front']['phase']['adapter']['default'] = 'Main';
// 默认的请求类型
$conf['ctrl_front']['phase']['adapter']['agent']['default'] = 'html';
// 允许指定输出格式
$conf['ctrl_front']['phase']['output']['allow_format'] = true;
// 默认输出类型
$conf['ctrl_front']['phase']['output']['format']['default'] = 'html';
// 默认输出类型
$conf['ctrl_front']['phase']['output']['format']['cli'] = 'text';


/**
 * 异常信息控制
 */
/*
$conf['fault']['class'] = 'Vs_Action_Fault';
$conf['fault']['tpl'] = 'fault';
*/

/**
 * 模板配置文件
 */
// 左限定
$conf['tpl']['left_delimiter'] = '<{';
// 右限定
$conf['tpl']['right_delimiter'] = '}>';
// 模板存放路径
$conf['tpl']['template_dir'] = SYS_PATH . '/src/template';
// 编译目录
// 注意：不同的应用应该使用不同的编译目录，否则会出现冲突
$conf['tpl']['compile_dir'] = TEMP_PATH; 
// 静态文件路径
$conf['tpl']['static'] = '/static/';
