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
define('NO_CHECK', true);

require (dirname(__FILE__) . '/include/init.php');

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 赋值给模板
$smarty->assign('rec', $rec);

/**
 * +----------------------------------------------------------
 * 登录页
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    // 赋值给模板
    $smarty->assign('page_title', $_LANG['login']);
    $smarty->display('login.htm');
} 

/**
 * +----------------------------------------------------------
 * 登录验证
 * +----------------------------------------------------------
 */
elseif ($rec == 'login') {
    if ($_CFG['captcha']) { // 判断是否有开启验证码功能
        $_POST['captcha'] = $check->is_captcha(trim($_POST['captcha'])) ? strtoupper(trim($_POST['captcha'])) : ''; // 判断验证码格式是否正确
        if (md5($_POST['captcha'] . DOU_SHELL) != $_SESSION['captcha']) // 判断验证码是否正确
            $dou->dou_msg($_LANG['login_captcha_wrong'], 'login.php', 'out');
    }
    
    // 验证管理员用户名输入
    if (!$check->is_username(trim($_POST['user_name']))) {
        $dou->dou_msg($_LANG['login_input_wrong'], 'login.php', 'out');
    } else {
        $user_name = trim($_POST['user_name']);
    }
    
    // 验证用户存在和密码
    $user = $dou->get_row('admin', '*', "user_name = '$user_name'");
    if (!is_array($user)) { // 用户不存在
        $dou->create_admin_log($_LANG['login_action'] . ': ' . $user_name . " ( " . $_LANG['login_user_name_wrong'] . " ) ");
        $dou->dou_msg($_LANG['login_input_wrong'], 'login.php', 'out');
    } elseif (md5($_POST['password']) != $user['password']) { // 密码不正确（包含有输入密码和密码为空）
        if ($_POST['password']) // 有输入密码
            $dou->create_admin_log($_LANG['login_action'] . ': ' . $user_name . " ( " . $_LANG['login_password_wrong'] . " ) ");

        $dou->dou_msg($_LANG['login_input_wrong'], 'login.php', 'out');
    }

    // 全局防御令牌生成
    $firewall->set_admin_token();
    
    // 写入登录成功信息
    $_SESSION[DOU_ID]['user_id'] = $user['user_id'];
    $_SESSION[DOU_ID]['shell'] = md5($user['user_name'] . $user['password'] . DOU_SHELL); // 管理员登录验证标记
    $_SESSION[DOU_ID]['ontime'] = time(); // 登录时间
    
    // 更新登录信息
    $last_login = time();
    $last_ip = $dou->get_ip();
    $sql = "UPDATE " . $dou->table('admin') . " SET last_login = '$last_login', last_ip = '$last_ip' WHERE user_id = " . $user['user_id'];
    $dou->query($sql);
    
    $dou->create_admin_log($_LANG['login_action'] . ': ' . $_LANG['login_success']);
    $dou->dou_header(ROOT_URL . ADMIN_PATH . '/index.php');
} 

/**
 * +----------------------------------------------------------
 * 退出登录
 * +----------------------------------------------------------
 */
elseif ($rec == 'logout') {
    unset($_SESSION[DOU_ID]); // 清空登录信息
    $dou->dou_header(ROOT_URL . ADMIN_PATH . '/login.php');
}

/**
 * +----------------------------------------------------------
 * 找回密码、密码重置
 * +----------------------------------------------------------
 */
elseif ($rec == 'password_reset') {
    // 从密码重置链接中获取会员ID和安全码
    $user_id = $check->is_number($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
    $code = preg_match("/^[a-zA-Z0-9]+$/", $_REQUEST['code']) ? $_REQUEST['code'] : '';

    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->set_token('password_reset'));
    
    // 找回密码、密码重置
    if ($user_id && $code) { // 密码重置页面
        if (!$dou->check_password_reset($user_id, $code))
            $dou->dou_msg($_LANG['login_password_reset_fail'], ROOT_URL . ADMIN_PATH . '/login.php？rec=password_reset', 'out');
        
        $smarty->assign('user_id', $user_id);
        $smarty->assign('code', $code);
        $smarty->assign('action', 'reset'); // 设定表单提交处理为密码重置操作
    } else { // 找回密码提交页
        $smarty->assign('action', 'default'); // 设定表单提交处理为密码找回提交操作
    }
    
    // 赋值给模板
    $smarty->assign('page_title', $_LANG['login_password_reset']);
    $smarty->display('login.htm');
}

/**
 * +----------------------------------------------------------
 * 找回密码提交、重置密码提交
 * +----------------------------------------------------------
 */
elseif ($rec == 'password_reset_post') {
    // Action操作项的初始化
    $action = $check->is_rec($_POST['action']) ? $_POST['action'] : 'default';
    
    // 验证管理员用户名
    if (!$check->is_username(trim($_POST['user_name']))) {
        $dou->dou_msg($_LANG['login_password_reset_fail'], ROOT_URL . ADMIN_PATH . '/login.php？rec=password_reset', 'out');
    } else {
        $user_name = trim($_POST['user_name']);
    }
    
    // 验证对应的管理员邮箱
    if (!$check->is_email(trim($_POST['email']))) {
        $dou->dou_msg($_LANG['login_password_reset_fail'], ROOT_URL . ADMIN_PATH . '/login.php？rec=password_reset', 'out');
    } else {
        $email = trim($_POST['email']);
    }
    
    // 找回密码提交、重置密码提交
    if ($action == 'default') { // 密码找回提交操作
        // 根据用户名和邮箱获取对应的用户信息
        $user = $dou->get_row('admin', '*', "user_name = '$user_name' AND email = '$email'");
        
        // 对应的用户信息不存在
        if (!$user)
            $dou->dou_msg($_LANG['login_password_reset_wrong'], ROOT_URL . ADMIN_PATH . '/login.php?rec=password_reset', 'out');
    
        // CSRF防御令牌验证
        $firewall->check_token($_POST['token'], 'password_reset');
        
        // 生成包含找回密码链接的邮件正文
        $time = time();
        $code = substr(md5($user['user_name'] . $user['email'] . $user['password'] . $time . $user['last_login'] . DOU_SHELL) , 0 , 16) . $time;
        $site_url = rtrim(ROOT_URL, '/');
        $body = $user['user_name'] . $_LANG['login_password_reset_body_0'] . ROOT_URL . ADMIN_PATH . '/login.php?rec=password_reset' . '&uid=' . $user['user_id'] . '&code=' . $code . $_LANG['login_password_reset_body_1'] . $_CFG['site_name'] . '. ' . $site_url;
        
        // 发送找回密码邮件
        if ($dou->send_mail($user['email'], $_LANG['login_password_reset'], $body)) {
            $dou->dou_msg($_LANG['login_password_mail_success'] . $user['email'], ROOT_URL . ADMIN_PATH . '/login.php', 'out', '30');
        } else {
            $dou->dou_msg($_LANG['mail_send_fail'], ROOT_URL . ADMIN_PATH . '/login.php?rec=password_reset', 'out', '30');
        }
    } elseif ($action == 'reset') { // 密码重置操作
        // 获取会员ID和安全码
        $user_id = $check->is_number($_POST['user_id']) ? $_POST['user_id'] : '';
        $code = preg_match("/^[a-zA-Z0-9]+$/", $_POST['code']) ? $_POST['code'] : '';
        
        // 验证密码
        if (!$check->is_password($_POST['password'])) {
            $dou->dou_msg($_LANG['manager_password_cue'], '', 'out');
        } elseif (($_POST['password_confirm'] !== $_POST['password'])) {
            $dou->dou_msg($_LANG['manager_password_confirm_cue'], '', 'out');
        }

        // 找回密码操作
        if ($dou->check_password_reset($user_id, $code)) {
            // 重置密码
            $sql = "UPDATE " . $dou->table('admin') . " SET password = '" . md5($_POST['password']) . "' WHERE user_id = '$user_id'";
            $dou->query($sql);
            $dou->dou_msg($_LANG['login_password_reset_success'], ROOT_URL . ADMIN_PATH . '/login.php', 'out', '15');
        } else {
            $dou->dou_msg($_LANG['login_password_reset_fail'], ROOT_URL . ADMIN_PATH . '/login.php', 'out', '15');
        }
    }
}

?>