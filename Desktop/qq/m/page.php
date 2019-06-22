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

// 验证并获取合法的ID，如果不合法将其设定为-1
$id = $firewall->get_legal_id('page', $_REQUEST['id'], $_REQUEST['unique_id']);
if ($id == -1)
    $dou->dou_msg($GLOBALS['_LANG']['page_wrong'], M_URL);
    
    // 获取单页面信息
$page = get_page_info($id);
$top_id = $page['parent_id'] == 0 ? $id : $page['parent_id'];

// 赋值给模板-meta和title信息
$smarty->assign('page_title', $dou->page_title('page', '', $page['page_name']));
$smarty->assign('keywords', $page['keywords']);
$smarty->assign('description', $page['description']);

// 赋值给模板-导航栏
$smarty->assign('nav_list', $dou->get_nav_mobile('mobile', '0', 'page', $id));

// 赋值给模板-数据
$smarty->assign('head', $page['page_name']);
$smarty->assign('page_list', $dou->get_page_list($top_id, $id));
$smarty->assign('top', get_page_info($top_id));
$smarty->assign('page', $page);
if ($top_id == $id)
    $smarty->assign("top_cur", 'top_cur');

if (file_exists(ROOT_PATH . "theme/$_CFG[site_theme]/" . $page['unique_id'] . '.dwt')) {
    // 自定义单页模板
    $smarty->display($page['unique_id'] . '.dwt');
} else {
    $smarty->display('page.dwt');
}

/**
 * +----------------------------------------------------------
 * 获取单页面信息
 * +----------------------------------------------------------
 */
function get_page_info($id = 0) {
    $page = $GLOBALS['dou']->get_row('page', '*', "id = '$id'");
    
    if ($page) {
        $page['url'] = $GLOBALS['dou']->rewrite_url('page', $page['id']);
    }
    
    return $page;
}
?>