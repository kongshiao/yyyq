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
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板
$smarty->assign('rec', $rec);

/**
 * +----------------------------------------------------------
 * 系统信息
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    // 获取服务器和DouPHP系统信息
    $sys_info['os'] = PHP_OS;
    $sys_info['ip'] = $_SERVER['SERVER_ADDR'];
    $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
    $sys_info['php_ver'] = PHP_VERSION;
    $sys_info['mysql_ver'] = $dou->version();
    $sys_info['zlib'] = function_exists('gzclose') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['safe_mode'] = (boolean) ini_get('safe_mode') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['safe_mode_gid'] = (boolean) ini_get('safe_mode_gid') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : $_LANG['no_timezone'];
    $sys_info['socket'] = function_exists('fsockopen') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['gd'] = extension_loaded("gd") ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['charset'] = strtoupper(DOU_CHARSET);
    $sys_info['build_date'] = date("Y-m-d", $_CFG['build_date']);
    $update_date = unserialize($_CFG['update_date']);
    $sys_info['update'] = $update_date['system']['update'];
    $sys_info['patch'] = $update_date['system']['patch'];
    $sys_info['logo'] = ROOT_URL . 'theme/' . $_CFG['site_theme'] . '/images/' . $_CFG['site_logo'];
    $sys_info['max_filesize'] = ini_get('upload_max_filesize');
    $sys_info['num_page'] = $dou->num_rows($dou->query("SELECT * FROM " . $dou->table('page')));
    $sys_info['num_product'] = $dou->num_rows($dou->query("SELECT * FROM " . $dou->table('product')));
    $sys_info['num_article'] = $dou->num_rows($dou->query("SELECT * FROM " . $dou->table('article')));
    
    // 提示应该被删除的目录未被删除
    if ($dou->check_read_write(ROOT_PATH . 'install') != 'no_exist') $warning[] = $_LANG['warning_install_exists'];
    if ($dou->check_read_write(ROOT_PATH . 'upgrade') != 'no_exist') $warning[] = $_LANG['warning_upgrade_exists'];
    if (file_exists($c_a_p = ROOT_PATH . "cache/custom_admin_path.candel.php")) @unlink($c_a_p);
    
    // 写入目录监测信息
    $sys_info['folder_exists'] = $warning;
 
    // 如果不是超级管理员就只显示当前管理员的日志
    if ($_USER['action_list'] != 'ALL')
        $filter_user_id = $_SESSION[DOU_ID]['user_id'];
     
    // 检索备份文件
    $sqlfiles = glob(ROOT_PATH . 'data/backup/*.sql');
    
    // 赋值给模板
    $smarty->assign('cur', 'index');
    $smarty->assign('page_list', $dou->get_page_nolevel());
    $smarty->assign('sys_info', $sys_info);
    $smarty->assign("log_list", $dou->get_admin_log($filter_user_id, 4));
    $smarty->assign('backup', $dou->get_first_end_file($sqlfiles, '.sql'));
    $smarty->assign('dou_api', $dou->dou_api());
    $smarty->assign('localsite', $dou->dou_localsite());
    $smarty->assign('quick_start', $dou->read_quick_start());
    
    $smarty->display('index.htm');
} 

/**
 * +----------------------------------------------------------
 * 清除缓存及已编译模板
 * +----------------------------------------------------------
 */
elseif ($rec == 'clear_cache') {
    $dou->dou_clear_cache(ROOT_PATH . 'cache');
    $dou->dou_msg($_LANG['clear_cache_success']);
}

/**
 * +----------------------------------------------------------
 * 关闭快速入门
 * +----------------------------------------------------------
 */
elseif ($rec == 'close_quick_start') {
    @unlink(ROOT_PATH . 'data/quick.start.dou');
    $dou->dou_header('index.php');
}

?>