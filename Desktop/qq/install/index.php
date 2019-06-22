<?php
/**
 * DOUCO TEAM
 * ============================================================================
 * COPYRIGHT DOUCO 2014-2015.
 * http://www.dou.co;
 * ----------------------------------------------------------------------------
 * Author:DouCo
 * Release Date: 2014-06-05
 */
define('IN_DOUCO', true);

require (dirname(__FILE__) . '/include/init.php');

// rec操作项的初始化
$step = $install->is_rec($_REQUEST['step']) ? $_REQUEST['step'] : 'default';

/* 判断是否安装过 */
if (file_exists(ROOT_PATH . 'data/install.lock')) {
    $title = $_LANG['douphp'] . " &rsaquo; " . $_LANG['lock'];
    
    $smarty->assign('title', $title);
    $smarty->display('install_lock.htm');
    exit();
}

/**
 * +----------------------------------------------------------
 * 欢迎页面
 * +----------------------------------------------------------
 */
if ($step == 'default') {
    $title = $_LANG['welcome'];
    
    $smarty->assign('title', $title);
    $smarty->display('index.htm');
} 

/**
 * +----------------------------------------------------------
 * 目录和服务器权限检测
 * +----------------------------------------------------------
 */
elseif ($step == 'check') {
    $title = $_LANG['douphp'] . " &rsaquo; " . $_LANG['check'];
    
    /* 系统信息 */
    $sys_info['os'] = PHP_OS;
    $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
    $sys_info['php_ver'] = PHP_VERSION;
    $sys_info['mysql_ver'] = extension_loaded('mysql') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['zlib'] = function_exists('gzclose') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : $_LANG['no_timezone'];
    $sys_info['socket'] = function_exists('fsockopen') ? $_LANG['yes'] : $_LANG['no'];
    $sys_info['gd'] = extension_loaded("gd") ? $_LANG['yes'] : $_LANG['no'];
    
    /* 检查目录 */
    $check_dirs = array (
            'cache',
            'cache/admin',
            'cache/m',
            'data',
            'data/slide',
            'data/backup',
            'images/article',
            'images/product',
            'images/upload' 
    );
    
    foreach ($check_dirs as $dir) {
        $full_dir = ROOT_PATH . $dir;
        
        // 如果目录不存在则建立
        if (!file_exists($full_dir))
            mkdir($full_dir, 0777);
        
        $check_writeable = $install->check_writeable($full_dir);
        if ($check_writeable == '1') {
            $if_write = "<b class='write'>" . $_LANG['write'] . "</b>";
        } elseif ($check_writeable == '0') {
            $if_write = "<b class='noWrite'>" . $_LANG['no_write'] . "</b>";
            $no_write = true;
        } elseif ($check_writeable == '2') {
            $if_write = "<b class='noWrite'>" . $_LANG['not_exist'] . "</b>";
            $no_write = true;
        }
        
        $writeable[] = array (
                "dir" => $dir,
                "if_write" => $if_write 
        );
    }
    
    // 根据 Web 服务器 信息配置伪静态文件
    if (strpos($sys_info['web_server'], 'Apache') !== false) {
        $rewrite_file = ".htaccess.txt";
    } elseif (strpos($sys_info['web_server'], 'nginx') !== false) {
        $rewrite_file = "nginx.txt";
    } elseif (strpos($sys_info['web_server'], 'IIS') !== false) {
        $iis_exp = explode("/", $sys_info['web_server']);
        $iis_ver = $iis_exp['1'];
        
        if ($iis_ver >= 7.0) {
            $rewrite_file = "web.config.txt";
        } else {
            $rewrite_file = "httpd.ini.txt";
        }
    }
    
    // 复制rewrite文件到站点根目录
    if ($rewrite_file) {
        $source = ROOT_PATH . "install/data/rewrite/" . $rewrite_file;
        $destination = ROOT_PATH . $rewrite_file;
        $m_destination = ROOT_PATH . 'm/' . $rewrite_file;
        @copy($source, $destination);
        if (strpos($sys_info['web_server'], 'nginx') === false)
            @copy($source, $m_destination);
    }
    
    $smarty->assign('title', $title);
    $smarty->assign('sys_info', $sys_info);
    $smarty->assign('writeable', $writeable);
    $smarty->assign('no_write', $no_write);
    $smarty->display('check.htm');
} 

/**
 * +----------------------------------------------------------
 * 安装配置
 * +----------------------------------------------------------
 */
elseif ($step == 'setting') {
    $title = $_LANG['douphp'] . " &rsaquo; " . $_LANG['setting'];
    
    $smarty->assign('title', $title);
    $smarty->display('setting.htm');
} 

/**
 * +----------------------------------------------------------
 * 安装配置
 * +----------------------------------------------------------
 */
elseif ($step == 'install') {
    // 生成config文件内容
    $config_str = "<?php\r\n";
    $config_str .= "/**\r\n";
    $config_str .= " * DouPHP\r\n";
    $config_str .= " * --------------------------------------------------------------------------------------------------\r\n";
    $config_str .= " * 版权所有 2014-2015 漳州豆壳网络科技有限公司，并保留所有权利。\r\n";
    $config_str .= " * 网站地址: http://www.dou.co\r\n";
    $config_str .= " * --------------------------------------------------------------------------------------------------\r\n";
    $config_str .= " * 这不是一个自由软件！您只能在遵守授权协议前提下对程序代码进行修改和使用；不允许对程序代码以任何形式任何目的的再发布。\r\n";
    $config_str .= " * 授权协议：http://www.dou.co/license.html\r\n";
    $config_str .= " * --------------------------------------------------------------------------------------------------\r\n";
    $config_str .= " * Author: DouCo\r\n";
    $config_str .= " * Release Date: 2015-06-10\r\n";
    $config_str .= " */\r\n\r\n";
    
    $config_str .= "// database host\r\n";
    $config_str .= '$dbhost   = "' . $_POST['dbhost'] . '";' . "\r\n\r\n";
    
    $config_str .= "// database name\r\n";
    $config_str .= '$dbname   = "' . $_POST['dbname'] . '";' . "\r\n\r\n";
    
    $config_str .= "// database username\r\n";
    $config_str .= '$dbuser   = "' . $_POST['dbuser'] . '";' . "\r\n\r\n";
    
    $config_str .= "// database password\r\n";
    $config_str .= '$dbpass   = "' . $_POST['dbpass'] . '";' . "\r\n\r\n";
    
    $config_str .= "// table prefix\r\n";
    $config_str .= '$prefix   = "' . $_POST['prefix'] . '";' . "\r\n\r\n";
    
    $config_str .= "// charset\r\n";
    $config_str .= "define('DOU_CHARSET', 'utf-8');" . "\r\n\r\n";
    
    $config_str .= "// administrator path\r\n";
    $config_str .= "define('ADMIN_PATH', " . 'isset($admining) ? $admining' . " : 'admin');\r\n\r\n";
    
    $config_str .= "// mobile path\r\n";
    $config_str .= "define('M_PATH', 'm');\r\n\r\n";
    
    $config_str .= "// miniprogram path\r\n";
    $config_str .= "define('MINIPROGRAM_PATH', 'miniprogram');\r\n\r\n";
    
    $config_str .= "?>";
    
    // 生成config.php文件
    $douphp_config = ROOT_PATH . 'data/config.php';
    file_put_contents($douphp_config, $config_str);
    
    // 嵌入config配置文件
    include_once ($douphp_config);
    
    // 检查表单
    if (!$link = @mysqli_connect($dbhost, $dbuser, $dbpass)) { // 检查数据库连接情况
        $cue = $_LANG['cue_connect'];
    } elseif (!$_POST['username']) {
        $cue = $_LANG['cue_username_empty'];
    } elseif (!$install->is_username($_POST['username'])) {
        $cue = $_LANG['cue_username_wrong'];
    } elseif (!$_POST['password']) {
        $cue = $_LANG['cue_password_empty'];
    } elseif (!$install->is_password($_POST['password'])) {
        $cue = $_LANG['cue_password_wrong'];
    } elseif (!$_POST['password_confirm']) {
        $cue = $_LANG['cue_password_confirm_empty'];
    } elseif ($_POST['password'] != $_POST['password_confirm']) {
        $cue = $_LANG['cue_password_confirm_wrong'];
    }

    // AJAX验证表单
    if ($_REQUEST['do'] == 'callback') {
        if ($cue) {
            echo '<p class="cue"><strong>' . $_LANG['wrong'] . '：</strong>' . $cue . '</p>';
        }
								exit;
    }
    
    // 无错误信息，完成安装
    if (!$cue) {
        // 如果数据库不存在，则创建
        mysqli_query($link, "CREATE DATABASE IF NOT EXISTS `$dbname` default charset utf8 COLLATE utf8_general_ci");
        mysqli_select_db($link, $dbname);
     
        // 数据库信息输入正确后建立连接
        $dou = new Common($dbhost, $dbuser, $dbpass, $dbname, $prefix, DOU_CHARSET);
        
        // 读取SQL文件到一个字符串中
        $sql = file_get_contents(ROOT_PATH . I_PATH . '/data/backup/douphp.sql');
        
        // 进行安装的常规替换
        $sql = preg_replace('/dou_/Ums', "$prefix", $sql);
        
        // 进行安装的常规替换
        $sql_head = "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\r\n";
        $sql_head .= "SET time_zone = '+00:00';\r\n\r\n\r\n";
        
        $sql_head .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n";
        $sql_head .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n";
        $sql_head .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n";
        $sql_head .= "/*!40101 SET NAMES utf8 */;\r\n\r\n";
        
        $sql = $sql_head . $sql;
        
        // 生成管理员
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $add_time = time();
        
        // 导入数据
        $dou->fn_execute($sql);
        
        /* 写入 hash_code，做为网站唯一性密钥 */
        $hash_code = md5(md5(time()) . md5(md5(ROOT_URL . $dbhost . $dbname . $dbuser . $dbpass)));
        
        // 初始化数据
        $table_admin = $prefix . "admin";
        $table_config = $prefix . "config";
        $build_date = time();
        $douphp_version = $dou->get_one("SELECT value FROM " . $table_config . " WHERE name = 'douphp_version'");
        $date = substr(trim($douphp_version), -8);
        $update_date = 'a:2:{s:6:"system";a:2:{s:6:"update";i:' . $date . ';s:5:"patch";i:' . $date . ';}s:6:"module";a:2:{s:7:"article";i:' . $date . ';s:7:"product";i:' . $date . ';}}';
        
        // 初始化管理员账号
        $dou->fn_execute("UPDATE $table_admin SET user_name = '$username', password = '$password', email = '', add_time = '$add_time' WHERE user_id = '1'");
        
        // 初始化系统信息
        $dou->fn_execute("UPDATE $table_config SET value = '0' WHERE name = 'rewrite'");
        $dou->fn_execute("UPDATE $table_config SET value = '$build_date' WHERE name = 'build_date'");
        $dou->fn_execute("UPDATE $table_config SET value = '$hash_code' WHERE name = 'hash_code'");
        $dou->fn_execute("UPDATE $table_config SET value = '$update_date' WHERE name = 'update_date'");
        $dou->fn_execute("UPDATE $table_config SET value = '' WHERE name = 'cloud_account'");
        
        $_SESSION['username'] = $_POST['username'];
        
        header("Location: index.php?step=finish");
								exit;
    }
} 

/**
 * +----------------------------------------------------------
 * 完成安装
 * +----------------------------------------------------------
 */
elseif ($step == 'finish') {
    // 生成system.php文件
    $system_file = ROOT_PATH . 'data/system.php';
    $content = '<?php' . "\r\n";
    $content .= "'\r\n";
    $content .= "column_module:product,article\r\n";
    $content .= "single_module:\r\n\r\n";
    $content .= "'\r\n";
    $content .= '?>';
    file_put_contents($system_file, $content);
 
    // 生成install.lock文件
    $install_lock = fopen(ROOT_PATH . 'data/install.lock', "w+");
    fwrite($install_lock, "DOUPHP INSTALLED");
    
    $title = $_LANG['douphp'] . " &rsaquo; " . $_LANG['finish'];
    
    $smarty->assign('title', $title);
    $smarty->assign('username', $_SESSION['username']);
    $smarty->display('finish.htm');
}
?>