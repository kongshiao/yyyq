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

// 验证并获取合法的REQUEST
$module = $check->is_letter($_REQUEST['module']) ? $_REQUEST['module'] : '';

// 图片上传
include_once (ROOT_PATH . 'include/file.class.php');
$file = new File('images/' . $module . '/'); // 实例化类文件(文件上传路径，结尾加斜杠)

// 赋值给模板
$smarty->assign('rec', $rec);
$smarty->assign('cur', 'file');

/**
 * +----------------------------------------------------------
 * 文件上传盒子
 * +----------------------------------------------------------
 */
if ($rec == 'box') {
    // 验证并获取合法的REQUEST
    $item_id = $check->is_number($_REQUEST['item_id']) ? $_REQUEST['item_id'] : '';
    $type = $check->is_letter($_REQUEST['type']) ? $_REQUEST['type'] : '';
    $thumb_width = $check->is_number($_REQUEST['thumb_width']) ? $_REQUEST['thumb_width'] : '';
    $thumb_height = $check->is_number($_REQUEST['thumb_height']) ? $_REQUEST['thumb_height'] : '';

    // 文件上传盒子
    $custom_filename = $item_id . '_' . $type . '_' . $dou->create_rand_string('number', 6, time());
    $image = $file->box($module, $item_id, 'filebox_file', $type, $custom_filename, $thumb_width, $thumb_height);
    $if_thumb = $thumb_width || $thumb_height ? true : false;
    $file_url = $dou->dou_file($image, $if_thumb);
 
    if ($type == 'content') {
        $html = '<img src="' . $file_url. '" data-file="' . $image . '" />';
    } else {
        $html = $file->img_list_html($module, $item_id, $type);
    }
    
    echo $html;
}

/**
 * +----------------------------------------------------------
 * 文件删除
 * +----------------------------------------------------------
 */
elseif ($rec == 'del') {
    // 验证并获取合法的REQUEST
    $number = preg_match("/^[a-z0-9.]+$/", $_REQUEST['number']) ? $_REQUEST['number'] : '';
    $file_info = $dou->get_row('file', 'module, item_id, type', "number = '$number'");
    
    // 删除文件
    $dou->del_file($number);
 
    // 显示已经上传的文件列表
    $html = $file->img_list_html($file_info['module'], $file_info['item_id'], $file_info['type']);
    
    echo $html;
}

?>