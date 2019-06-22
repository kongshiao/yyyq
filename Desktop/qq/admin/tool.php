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
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : '';

// 赋值给模板
$smarty->assign('rec', $rec);

/**
 * +----------------------------------------------------------
 * 目录权限检测
 * +----------------------------------------------------------
 */
if ($rec == 'directory_check') {
    $smarty->assign('ur_here', $_LANG['tool_directory_check']);
    
    // 需要检查的目录列表
    $check_dirs[] = array (
            "note" => '缓存目录，需要“读写权限”，如果权限不足将造成网站无法运行',
            "dir" => 'cache'
    );
    $check_dirs[] = array (
            "note" => '缓存目录.后台',
            "dir" => 'cache/admin'
    );
    if (file_exists(ROOT_PATH . M_PATH)) {
        $check_dirs[] = array (
                "note" => '缓存目录.手机版',
                "dir" => 'cache/' . M_PATH
        );
    }
    if (file_exists(ROOT_PATH . MINIPROGRAM_PATH)) {
        $check_dirs[] = array (
                "note" => '缓存目录.小程序',
                "dir" => 'cache/' . MINIPROGRAM_PATH
        );
    }
    $check_dirs[] = array (
            "note" => '数据目录，需要“读写权限”，如果缺少写入权限，将无法上传幻灯、备份数据、在线升级等操作',
            "dir" => 'data'
    );
    $check_dirs[] = array (
            "note" => '数据目录.首页幻灯广告',
            "dir" => 'data/slide'
    );
    $check_dirs[] = array (
            "note" => '数据目录.数据备份',
            "dir" => 'data/backup'
    );
    $check_dirs[] = array (
            "note" => '文件目录，需要“读写权限”，如果缺少写入权限，将无法上传产品图片、文章图片以及其他扩展模块图片上传（其它扩展模块不 会在这里做出提示，但如果出现问题，以此类推排除目录问题）',
            "dir" => 'images'
    );
    $check_dirs[] = array (
            "note" => '文件目录.文章',
            "dir" => 'images/article'
    );
    $check_dirs[] = array (
            "note" => '文件目录.产品',
            "dir" => 'images/product'
    );
    $check_dirs[] = array (
            "note" => '模板目录，需要“读写权限”，如果缺少写入权限，将无法在线下载模板',
            "dir" => 'theme'
    );
    if (file_exists(ROOT_PATH . M_PATH)) {
        $check_dirs[] = array (
                "note" => '模板目录.手机版',
                "dir" => M_PATH . '/theme'
        );
    }
    
    foreach ($check_dirs as $row) {
        $full_dir = ROOT_PATH . $row['dir'];
        
        $check_writeable = $dou->check_read_write($full_dir);
        if ($check_writeable == 'write') {
            $status_text = $_LANG['write'];
            $class = 'write';
        } elseif ($check_writeable == 'no_write') {
            $status_text = $_LANG['no_write'];
            $class = 'noWrite';
        } elseif ($check_writeable == 'no_exist') {
            $status_text = $_LANG['not_exist'];
            $class = 'noWrite';
        }
        
        $writeable_list[] = array (
                "note" => $row['note'],
                "dir" => $row['dir'],
                "status_text" => $status_text,
                "class" => $class
        );
    }
    
    // 赋值给模板
    $smarty->assign('writeable_list', $writeable_list);
    
    $smarty->display('tool.htm');
}

/**
 * +----------------------------------------------------------
 * 修改后台路径
 * +----------------------------------------------------------
 */
if ($rec == 'custom_admin_path') {
    $smarty->assign('ur_here', $_LANG['tool_custom_admin_path']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['system_developer'],
            'href' => 'system.php?dou' 
    ));
    
    $session_key = $dou->create_rand_string('letter', 6);
    $session_value = $dou->create_rand_string('number', 6);
    $_SESSION[$session_key] = $session_value;
 
    file_put_contents(ROOT_PATH . "cache/custom_admin_path.candel.php", custom_admin_path_code($session_key, $session_value));
    // 赋值给模板
    $smarty->assign('session_key', $session_key);
    $smarty->assign('session_value', $session_value);
    $smarty->assign('admin_path', ADMIN_PATH);
    $smarty->assign('developer_mode', true);
    
    $smarty->display('tool.htm');
}

/**
 * +----------------------------------------------------------
 * 自定义后台目录名
 * +----------------------------------------------------------
 */
function custom_admin_path_code($session_key, $session_value) {
    // 这里的PHP代码作为文本输出
    $text = '<?php
    session_start();
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    header(\'Content-type: text/html; charset=' . DOU_CHARSET . '\');
    $site_path = str_replace(\'cache/custom_admin_path.candel.php\', \'\', str_replace(\'\\\\\', \'/\', __FILE__));
    $root_url = str_replace(\'cache\', \'\', dirname(\'http://\' . $_SERVER[\'HTTP_HOST\'] . $_SERVER[\'PHP_SELF\']));
    $old_path = preg_match("/^[a-z0-9.]+$/", $_REQUEST[\'old_path\']) ? $_REQUEST[\'old_path\'] : "";
    $prefix = preg_match("/^.{2}$/", $_REQUEST[\'prefix\']) ? $_REQUEST[\'prefix\'] : "";
    $new_path = preg_match("/^[a-z0-9]+$/", $_REQUEST[\'new_path\']) ? $prefix . $_REQUEST[\'new_path\'] : "";

    if (isset($_SESSION[\'' . $session_key . '\']) && isset($_REQUEST[\'' . $session_key . '\'])) {
        if ($_SESSION[\'' . $session_key . '\'] != $_REQUEST[\'' . $session_key . '\']) {
            header("Location: " . $root_url);
            exit;
        }
    } else {
        header("Location: " . $root_url);
        exit;
    }
    
    // 重命名后台目录
    if ($old_path && $new_path && @rename($site_path . $old_path, $site_path . $new_path)) {
        @rename($site_path . \'cache/\' . $old_path, $site_path . \'cache/\' . $new_path); // 重命名缓存目录
        echo "修改成功 3 秒后跳转到新后台地址……";
        file_put_contents($site_path . "data/..php", \'<?php $admining = \' . "\'" . $new_path . "\'" . \' ?>\');
        $path = $new_path;
    } else {
        echo "修改失败 3 秒后跳转回原后台地址……";
        $path = $old_path;
    }
    unset($_SESSION[\'' . $session_key . '\']);
    @unlink($site_path . \'cache/custom_admin_path.candel.php\');
    header("refresh:3; url=" . $root_url . $path);
    exit;
    ?>';
    return $text;
}

?>