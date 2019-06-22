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

// 载入语言文件
require (dirname(__FILE__) . '/include/init.php');

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 定义缓存路径.卸载模块使用
$cache_dir = ROOT_PATH . 'cache/';

$smarty->assign('rec', $rec);
$smarty->assign('cur', 'module');

/**
 * +----------------------------------------------------------
 * 扩展列表
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['module']);

    $smarty->assign('get', urlencode(serialize($_GET)));
    $smarty->assign('localsite', $dou->dou_localsite('module'));

    $smarty->display('module.htm');
} 

/**
 * +----------------------------------------------------------
 * 安装本地模块
 * +----------------------------------------------------------
 */
if ($rec == 'install_local') {
    $smarty->assign('ur_here', $_LANG['module']);
    
    // 载入待删除模块
    $zipfile_list = glob($cache_dir . '*.zip');
    if (is_array($zipfile_list)) {
        foreach ($zipfile_list as $zipfile) {
            $install_list[] = preg_replace('/.zip/i', '', basename($zipfile));
        }
    }

    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    $smarty->assign('install_list', $install_list);

    $smarty->display('module.htm');
}

/**
 * +----------------------------------------------------------
 * 模板卸载页面
 * +----------------------------------------------------------
 */
if ($rec == 'uninstall') {
    $smarty->assign('ur_here', $_LANG['module']);
    
    // 载入待删除模块
    $zipfile_list = glob($cache_dir . '*.zip');
    if (is_array($zipfile_list)) {
        foreach ($zipfile_list as $zipfile) {
            $uninstall_list[] = preg_replace('/.zip/i', '', basename($zipfile));
        }
    }

    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());

    $smarty->assign('uninstall_list', $uninstall_list);
    $smarty->display('module.htm');
} 

/**
 * +----------------------------------------------------------
 * 卸载模块
 * +----------------------------------------------------------
 */
if ($rec == 'del') {
    // 载入扩展功能
    include_once (ROOT_PATH . ADMIN_PATH . '/include/cloud.class.php');
    $dou_cloud = new Cloud('cache');

    // CSRF防御令牌验证
    $firewall->check_token($_GET['token']);
    
    if ($check->is_extend_id($extend_id = $_REQUEST['extend_id'])) {
        $module_zip = $cache_dir . $extend_id . '.zip'; // 模块压缩包
        $module_dir = $cache_dir . $extend_id; // 模块目录
        
        if ($dou_cloud->file_unzip($module_zip, $module_dir)) {
            $dou_cloud->dirname_synchronization($module_dir); // 将安装包里的目录名同步为系统当前设定的目录名（如模板、手机版目录、后台目录、小程序目录）
            $dou_cloud->clear_module($extend_id);
            $dou_cloud->change_updatedate('module', $extend_id, true); // 删除更新时间记录
            $dou->del_dir($module_dir);
            @unlink($module_zip);
            $dou->create_admin_log($_LANG['module_uninstall_success'] . ': ' . $extend_id);
            
            $dou->dou_header('module.php?rec=uninstall');
        } else {
            $dou->dou_msg($_LANG['module_unzip_wrong'], 'module.php?rec=uninstall');
        }
    } else {
        $dou->dou_msg($_LANG['module_uninstall_wrong'], 'module.php?rec=uninstall');
    }
}

?>