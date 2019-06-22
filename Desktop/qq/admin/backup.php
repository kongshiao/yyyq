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
require (ROOT_PATH . ADMIN_PATH . '/include/backup.class.php');

// rec操作项的初始化
$rec = $check->is_rec($_REQUEST['rec']) ? $_REQUEST['rec'] : 'default';

// 初始化
$backup = new Backup();
@ set_time_limit(0);

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'backup');

/**
 * +----------------------------------------------------------
 * 数据备份
 * +----------------------------------------------------------
 */
if ($rec == 'default') {
    $smarty->assign('ur_here', $_LANG['backup']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['backup_restore'],
            'href' => 'backup.php?rec=restore' 
    ));
    
    $query = $dou->query("SHOW TABLE STATUS LIKE '" . $prefix . "%'");
    while ($table = $dou->fetch_array($query)) {
        $table['checked'] = $table['Engine'] == 'MyISAM' ? ' ' : 'disabled';
        $totalsize += $table['Data_length'] + $table['Index_length'];
        
        if ($table['Data_length'] > 10240) {
            $table['Data_length'] = ceil($table['Data_length'] / 1024) . "KB";
        }
        $tables[] = $table;
    }
    $totalsize = ceil($totalsize / 1024);
    
    // 根据时间生成备份文件名
    $filename = 'D' . date('Ymd') . 'T' . date('His');
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    // 初始化数据
    $smarty->assign('tables', $tables);
    $smarty->assign('totalsize', $totalsize);
    $smarty->assign('filename', $filename);
    
    $smarty->display('backup.htm');
}

/**
 * +----------------------------------------------------------
 * 将备份写入SQL文件
 * +----------------------------------------------------------
 */
if ($rec == 'backup') {
    $fileid = isset($_REQUEST['fileid']) ? $_REQUEST['fileid'] : 1;
    $tables = $_REQUEST['tables'];
    $vol_size = $_REQUEST['vol_size']; // 每个分卷文件大小
    $totalsize = $_REQUEST['totalsize']; // 所选数据库大小
    $filename = $_REQUEST['filename']; // 备份文件名
    
    // 判断备份文件名是否规范
    if (!$backup->is_backup_file($filename . '.sql'))
        $dou->dou_msg($_LANG['backup_filename_not_valid'], 'backup.php');
        
    // CSRF防御令牌验证
    $firewall->check_token($_REQUEST['token']);
    
    // 根据分卷执行操作（没有分卷$fileid默认值为1,有分卷则$fileid代表分卷1）
    if ($fileid == 1 && $tables) { // 分卷为1或者没有分卷要验证从FORM也没是否有提交数据表列表信息过来，然后将其写入缓存文件
        if (!isset($tables) || !is_array($tables)) {
            $dou->dou_msg($_LANG['backup_no_select'], 'backup.php');
        }
        $cache_file = ROOT_PATH . 'data/backup/tables.php';
        $content = "<?php\r\n";
        $content .= "\$data = " . var_export($tables, true) . ";\r\n";
        $content .= "?>";
        file_put_contents($cache_file, $content, LOCK_EX);
    } else { // 遍历到分卷2开始就是从tables.php文件中获取数据表列表信息
        include ROOT_PATH . 'data/backup/tables.php';
        $tables = $data;
        if (!$tables) {
            $dou->dou_msg($_LANG['backup_no_select'], 'backup.php');
        }
    }
    
    if ($dou->version() > '4.1' && $dou->charset) {
        $dou->query("SET NAMES '" . $dou->charset . "';\n\n");
    }
    
    $sqldump = '';
    $tableid = isset($_REQUEST['tableid']) ? $_REQUEST['tableid'] - 1 : 0;
    $startfrom = isset($_REQUEST['startfrom']) ? intval($_REQUEST['startfrom']) : 0;
    $tablenumber = count($tables);
    
    for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $vol_size * 1024; $i++) {
        $sqldump .= $backup->sql_dumptable($tables[$i], $vol_size, $startfrom, strlen($sqldump));
        $startfrom = 0;
    }
    
    if (trim($sqldump)) {
        $sqldump = "-- DouPHP v1.x SQL Dump Program\n" . "-- " . ROOT_URL . "\n" . "-- \n" . "-- DATE : " . date('Y-m-d H:i:s') . "\n" . "-- MYSQL SERVER VERSION : " . $dou->version() . "\n" . "-- PHP VERSION : " . PHP_VERSION . "\n" . "-- DouPHP VERSION : " . $_CFG['douphp_version'] . "\n\n" . $sqldump;
        
        $tableid = $i;
        
        if ($vol_size > $totalsize) {
            $sql_filename = $filename . '.sql';
        } else {
            $sql_filename = $filename . '_' . $fileid . '.sql';
        }
        
        $fileid++;
        
        $bakfile = ROOT_PATH . '/data/backup/' . $sql_filename;
        
        if (!is_writable(ROOT_PATH . '/data/backup/'))
            $dou->dou_msg($_LANG['backup_no_save'], 'backup.php');
        
        file_put_contents($bakfile, $sqldump);
        @chmod($bakfile, 0777);
        
        $dou->create_admin_log($_LANG['backup'] . ': ' . $sql_filename);
        
        $_LANG['backup_file_success'] = preg_replace('/d%/Ums', $sql_filename, $_LANG['backup_file_success']);
        $dou->dou_msg($_LANG['backup_file_success'], 'backup.php?rec=backup&vol_size=' . $vol_size . '&totalsize=' . $totalsize . '&filename=' . $filename . '&token=' . $_REQUEST['token'] . '&tableid=' . $tableid . '&fileid=' . $fileid . '&startfrom=' . $startrow, '', 1);
    } else {
        @unlink(ROOT_PATH . 'data/backup/tables.php');
        $dou->dou_msg($_LANG['backup_success'], 'backup.php?rec=restore');
    }
}

/**
 * +----------------------------------------------------------
 * 恢复备份列表
 * +----------------------------------------------------------
 */
if ($rec == 'restore') {
    $smarty->assign('ur_here', $_LANG['backup_restore']);
    $smarty->assign('action_link', array (
            'text' => $_LANG['backup'],
            'href' => 'backup.php' 
    ));
    
    $sqlfiles = glob(ROOT_PATH . 'data/backup/*.sql');
    
    // CSRF防御令牌生成
    $smarty->assign('token', $firewall->get_token());
    
    $smarty->assign('infos', $dou->get_filelist_by_time($sqlfiles, '.sql'));
    $smarty->display('backup.htm');
}

/**
 * +----------------------------------------------------------
 * 恢复备份
 * +----------------------------------------------------------
 */
if ($rec == 'import') {
    // 验证并获取合法的备份文件名
    $sql_filename = $backup->is_backup_file($_REQUEST['sql_filename']) ? $_REQUEST['sql_filename'] : $dou->dou_msg($_LANG['backup_filename_not_valid'], 'backup.php');
        
    // CSRF防御令牌验证
    $firewall->check_token($_REQUEST['token']);
    
    // 判断备份文件名是否是分卷的格式
    preg_match('/(.*)_([0-9])+\.sql$/', $sql_filename, $match);
    
    // 判断是否有分卷
    if ($match) {
        $fileid = $_REQUEST['fileid'] ? $_REQUEST['fileid'] : 1;
        $sql_filename = $match['1'] . "_" . $fileid . ".sql";
    }
    
    $restore_now = preg_replace('/d%/Ums', $sql_filename, $_LANG['backup_restore_now']);
    
    $file_path = ROOT_PATH . 'data/backup/' . $sql_filename;
    
    // 判断是否有分卷
    if ($match) {
        // 判断SQL文件是否存在
        if (file_exists($file_path)) {
            $sql = file_get_contents($file_path);
            $dou->fn_execute($sql);
            
            // 递增查找分卷直到无后续分卷，则结束查找并且将恢复操作设定为完成
            $fileid++;
            $dou->dou_msg($restore_now, 'backup.php?rec=' . $rec . '&sql_filename=' . $sql_filename . '&fileid=' . $fileid . '&token=' . $_REQUEST['token']);
        } else {
            $dou->create_admin_log($_LANG['backup_restore'] . ': ' . $sql_filename);
            $dou->dou_msg($_LANG['backup_restore_success'], 'backup.php?rec=restore');
        }
    } else {
        $sql = file_get_contents($file_path);
        $dou->fn_execute($sql);
        
        $dou->create_admin_log($_LANG['backup_restore'] . ': ' . $sql_filename);
        $dou->dou_msg($_LANG['backup_restore_success'], 'backup.php?rec=restore');
    }
}

/**
 * +----------------------------------------------------------
 * 备份删除
 * +----------------------------------------------------------
 */
if ($rec == 'del') {
    // 验证并获取合法的备份文件名
    $sql_filename = $backup->is_backup_file($_REQUEST['sql_filename']) ? $_REQUEST['sql_filename'] : $dou->dou_msg($_LANG['backup_filename_not_valid'], 'backup.php');
    
    if (isset($_POST['confirm'])) {
        if (file_exists(ROOT_PATH . 'data/backup/' . $sql_filename)) {
            @unlink(ROOT_PATH . 'data/backup/' . $sql_filename);
        }
        
        preg_match('/(.*)_([0-9])+\.sql$/', $sql_filename, $match);
        
        // 如果存在分卷则将分卷删除
        if ($match) {
            $sqlfiles = glob(ROOT_PATH . 'data/backup/' . $match['1'] . '_*.sql');
            
            $sql_filename .= ' ' . $_LANG['backup_vol_include'] . ' : ';
            
            foreach ($sqlfiles as $id => $sqlfile) {
                if (file_exists(ROOT_PATH . 'data/backup/' . basename($sqlfile))) {
                    @unlink(ROOT_PATH . 'data/backup/' . basename($sqlfile));
                }
                
                $sql_filename .= basename($sqlfile) . ',';
            }
        }
        
        $dou->create_admin_log($_LANG['backup_del'] . ': ' . $sql_filename);
        $dou->dou_msg(preg_replace('/d%/Ums', $sql_filename, $_LANG['backup_del_success']), 'backup.php?rec=restore');
    } else {
        $_LANG['del_check'] = preg_replace('/d%/Ums', $sql_filename, $_LANG['del_check']);
        $dou->dou_msg($_LANG['del_check'], 'backup.php?rec=restore', '', '30', "backup.php?rec=del&sql_filename=$sql_filename");
    }
}

/**
 * +----------------------------------------------------------
 * 备份下载
 * +----------------------------------------------------------
 */
if ($rec == 'down') {
    // 验证并获取合法的备份文件名
    $sql_filename = $backup->is_backup_file($_REQUEST['sql_filename']) ? $_REQUEST['sql_filename'] : $dou->dou_msg($_LANG['backup_filename_not_valid'], 'backup.php');
    
    ob_clean();
    if ($fp = @ fopen(ROOT_PATH . 'data/backup/' . $sql_filename, 'r')) {
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=" . $sql_filename);
        header("Accept-Ranges: bytes");
        header("Content-Length:" . filesize(ROOT_PATH . 'data/backup/' . $sql_filename));
        header('Content-transfer-encoding: binary');
        while (!@ feof($fp))
            echo fread($fp, 10240);
    }
}

?>