<?php
/**
 * DouPHP
 * --------------------------------------------------------------------------------------------------
 * 版权所有 2013-2019 漳州豆壳网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.dou.co
 * --------------------------------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在遵守授权协议前提下对程序代码进行修改和使用；不允许对程序代码以任何形式任何目的的再发布。
 * 授权协议：http://www.dou.co/license.html
 * --------------------------------------------------------------------------------------------------
 * Author: DouCo
 * Release Date: 2019-01-08
 */
if (!defined('IN_DOUCO')) {
    die('Hacking attempt');
}

// 开启SESSION
session_start();

// 显示除了E_NOTICE(提示)和E_WARNING(警告)外的所有错误
error_reporting(E_ALL ^ E_NOTICE);

// 关闭 set_magic_quotes_runtime
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
   set_magic_quotes_runtime(0);
} else {
   ini_set('magic_quotes_runtime', 0);
}

// 调整时区
if (version_compare(PHP_VERSION, '5.1', '>=')) date_default_timezone_set('PRC'); 

// 网站后台标记
define('IS_ADMIN', true);

// 后台目录定义文件
if (file_exists($custom_file = '../data/..php')) include_once ($custom_file);

// 载入配置文件
include_once ('../data/config.php');

// 判断传输协议
define('HTTP', ((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')) ? 'https://' : 'http://');

// 定义DouPHP基础常量
define('ROOT_PATH', str_replace(ADMIN_PATH . '/include/init.php', '', str_replace('\\', '/', __FILE__)));
define('ROOT_URL', preg_replace('/' . ADMIN_PATH . '\//Ums', '', dirname(HTTP . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) . "/"));
define('M_URL', ROOT_URL . M_PATH . '/');

if (!file_exists(ROOT_PATH . "data/install.lock")) {
    header("Location: ../install/index.php\n");
    exit();
}

// 载入DouPHP核心文件
require (ROOT_PATH . 'include/smarty.class.php');
require (ROOT_PATH . 'include/mysql.class.php');
require (ROOT_PATH . 'include/common.class.php');
require (ROOT_PATH . ADMIN_PATH . '/include/manager.class.php');
require (ROOT_PATH . ADMIN_PATH . '/include/action.class.php');
require (ROOT_PATH . 'include/check.class.php');
require (ROOT_PATH . 'include/firewall.class.php');

// 实例化DouPHP核心类
$dou = new Action($dbhost, $dbuser, $dbpass, $dbname, $prefix, DOU_CHARSET);
$check = new Check();
$firewall = new Firewall();

// 定义系统标识
define('DOU_SHELL', $dou->get_one("SELECT value FROM " . $dou->table('config') . " WHERE name = 'hash_code'"));
define('DOU_ID', 'admin_' . substr(md5(DOU_SHELL . 'admin'), 0, 5));

// 豆壳防火墙
$firewall->dou_firewall();

// 设置页面缓存和编码
header('content-type: text/html; charset=' . DOU_CHARSET);
header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// 开启缓冲区
ob_start();

// SMARTY配置
$smarty = new smarty();
$smarty->template_dir = ROOT_PATH . ADMIN_PATH . '/templates'; // 模板存放目录
$smarty->compile_dir = ROOT_PATH . 'cache/' . ADMIN_PATH; // 编译目录
$smarty->left_delimiter = '{'; // 左定界符
$smarty->right_delimiter = '}'; // 右定界符
                                
// 如果编译和缓存目录不存在则建立
if (!file_exists($smarty->compile_dir))
    mkdir($smarty->compile_dir, 0777);

// 验证管理员
$smarty->assign("user", $_USER = $dou->admin_check($_SESSION[DOU_ID]['user_id'], $_SESSION[DOU_ID]['shell']));

// 读取站点信息
$smarty->assign("site", $_CFG = $dou->get_config());

// 系统模块
$_MODULE = $dou->dou_module();

// 载入语言文件
foreach ($_MODULE['lang'] as $lang_file) {
    require ($lang_file); // 载入系统语言文件
}

// 如果存在自定义类则载入
if (file_exists($other_class_file = ROOT_PATH . 'include/other.class.php')) {
      require ($other_class_file);
      $other = new Other();
}

// 工作空间
$smarty->assign("workspace", $dou->dou_workspace());

// 通用信息调用
$smarty->assign("lang", $_LANG);
$smarty->assign("open", $_OPEN = $_MODULE['open']); // 模块开启状态
$_DISPLAY = unserialize($_CFG['display']); // 显示设置
$_DEFINED = unserialize($_CFG['defined']); // 自定义属性

// Smarty 过滤器
function remove_html_comments($source, & $smarty) {
    return $source = preg_replace('/<!--.*{(.*)}.*-->/U', '{$1}', $source);
}
$smarty->register_prefilter('remove_html_comments');

?>