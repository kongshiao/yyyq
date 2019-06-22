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

// 图片上传
include_once (ROOT_PATH . 'include/file.class.php');
$file = new File('theme/' . $_CFG['site_theme'] . '/images/'); // 实例化类文件(文件上传路径，结尾加斜杠)

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板
$smarty->assign('cur', 'system');

/**
 * +----------------------------------------------------------
 * 系统设置
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['system']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['system_developer'],
            'href' => 'system.php?dou' 
    ));
 
    // 开发者模式开关
    if ($_REQUEST['dou'] == 'open') {
        $dou->query("UPDATE " . $dou->table('config') . " SET value = '1' WHERE name = 'developer'");
        $dou->dou_header('system.php');
    } elseif ($_REQUEST['dou'] == 'close') {
        $dou->query("UPDATE " . $dou->table('config') . " SET value = '0' WHERE name = 'developer'");
        $dou->dou_header('system.php');
    }
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    if (isset($_REQUEST['dou'])) {
        $tab_list = array('developer');
    } else {
        $tab_list = array('main', 'display', 'defined', 'mail');
    }
    
    // 生成设置项
    foreach ($tab_list as $tab) {
        $cfg[] = array (
                "name" => $tab,
                "lang" => $_LANG['system_' . $tab],
                "list" => $dou->get_cfg_list($tab)
        );
    }
 
    // 赋值给模板
    $smarty->assign('cfg', $cfg);
    $smarty->assign('developer_mode', isset($_REQUEST['dou']) ? true : false);
    
    $smarty->display('system.htm');
}

/**
 * +----------------------------------------------------------
 * 系统设置数据更新
 * +----------------------------------------------------------
 */
if ($rec == 'update') {
    // 验证系统语言选择
    if (!preg_match("/^[a-z_]+$/", $_POST['language']) && $_REQUEST['tab'] != 'developer')
        $dou->dou_msg($_LANG['language_wrong'], 'system.php');
    
    // 上传图片生成
    if ($_FILES['site_logo']['name'] != "") {
        $site_logo = $file->upload('site_logo', 'logo'); // 上传的文件域
        $_POST['site_logo'] = $site_logo;
    }
    
    // CSRF防御令牌验证
    $firewall->check_token($_POST['token']);
    
    foreach ($_POST as $name => $value) {
        if (is_array($value)) $value = serialize($value);
        $dou->query("UPDATE " . $dou->table('config') . " SET value = '$value' WHERE name = '$name'");
    }
    
    $dou->create_admin_log($_LANG['system'] . ': ' . $_LANG['edit_succes']);
    $dou->dou_msg($_LANG['edit_succes'], 'system.php');
}

?>