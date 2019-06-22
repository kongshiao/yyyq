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
class Action extends Common {
    /**
     * +----------------------------------------------------------
     * 获取导航菜单
     * +----------------------------------------------------------
     * $type 导航类型
     * $parent_id 默认获取一级导航
     * $current_module 当前页面模型名称，用于高亮导航栏
     * $current_id 当前页面栏目ID
     * +----------------------------------------------------------
     */
    function get_nav_mobile($type = 'mobile', $parent_id = 0, $current_module = '', $current_id = '', $current_parent_id = '') {
        $nav_list = $this->get_nav($type, $parent_id, $current_module, $current_id, $current_parent_id);
        $nav_middle_list = $this->get_nav('middle', $parent_id, $current_module, $current_id, $current_parent_id);
        
        return is_array($nav_list) ? $nav_list : $nav_middle_list;
    }

    /**
     * +----------------------------------------------------------
     * 页头名称
     * +----------------------------------------------------------
     * $head 页头名称
     * +----------------------------------------------------------
     */
    function head($head) {
        return $head;
    }
    
    /**
     * +----------------------------------------------------------
     * 当前位置
     * +----------------------------------------------------------
     * $module 模块名称
     * $class 分类ID或模块子栏目
     * $title 信息标题
     * +----------------------------------------------------------
     */
    function ur_here($module = '', $class = '', $title = '') {
        if ($module == 'page') {
            // 如果是单页面，则只显示单页面名称
            $ur_here = $title;
        } elseif (!$class) {
            // 模块主页
            $ur_here = $GLOBALS['_LANG'][$module];
        } else {
            // 模块名称
            $main = '<a href=' . $this->rewrite_url($module) . '>' . $GLOBALS['_LANG'][$module] . '</a>';
            
            // 如果存在分类
            if ($class) {
                $cat_name = is_numeric($class) ? $this->get_one("SELECT cat_name FROM " . $this->table($module) . " WHERE cat_id = '$class'") : $GLOBALS['_LANG'][$class];
                
                // 如果存在标题
                if ($title) {
                    $category = '<b>></b><a href=' . $this->rewrite_url($module, $class) . '>' . $cat_name . '</a>';
                } else {
                    $category = "<b>></b>$cat_name";
                }
            }
            
            // 如果存在标题
            if ($title)
                $title = '<b>></b>' . $title;
            
            $ur_here = $main . $category . $title;
        }
        
        return $ur_here;
    }
    
    /**
     * +----------------------------------------------------------
     * 标题
     * +----------------------------------------------------------
     * $module 模块名称
     * $class 分类ID或模块子栏目
     * $title 信息标题
     * +----------------------------------------------------------
     */
    function page_title($module = '', $class = '', $title = '') {
        // 如果是单页面，则只执行这一句
        if ($module == 'page') {
            $titles = $title . ' | ';
        } elseif ($module) {
            // 模块名称
            $main = $GLOBALS['_LANG'][$module] . ' | ';
            
            // 如果存在分类
            if ($class) {
                $cat_name = is_numeric($class) ? $this->get_one("SELECT cat_name FROM " . $this->table($module) . " WHERE cat_id = '$class'") : $GLOBALS['_LANG'][$class];
                $cat_name = $cat_name . ' | ';
            }
            
            // 如果存在标题
            if ($title)
                $title = $title . ' | ';
            
            $titles = $title . $cat_name . $main;
        }
        
        $page_title = ($titles ? $titles . $GLOBALS['_CFG']['mobile_name'] : $GLOBALS['_CFG']['mobile_title']) . ' - Powered by DouPHP';
        
        return $page_title;
    }
    
    /**
     * +----------------------------------------------------------
     * 信息提示
     * +----------------------------------------------------------
     * $text 提示的内容
     * $url 提示后要跳转的网址
     * $time 多久时间跳转
     * +----------------------------------------------------------
     */
    function dou_msg($text = '', $url = '', $time = 3) {
        if (!$text) {
            $text = $GLOBALS['_LANG']['dou_msg_success'];
        }
        
        /* 获取meta和title信息 */
        $GLOBALS['smarty']->assign('page_title', $GLOBALS['_CFG']['mobile_title']);
        $GLOBALS['smarty']->assign('keywords', $GLOBALS['_CFG']['mobile_keywords']);
        $GLOBALS['smarty']->assign('description', $GLOBALS['_CFG']['mobile_description']);
        
        /* 初始化导航栏 */
        $GLOBALS['smarty']->assign('nav_top_list', $this->get_nav('top'));
        $GLOBALS['smarty']->assign('nav_middle_list', $this->get_nav('middle'));
        $GLOBALS['smarty']->assign('nav_bottom_list', $this->get_nav('bottom'));
        
        /* 初始化数据 */
        $GLOBALS['smarty']->assign('ur_here', $GLOBALS['_LANG']['dou_msg']);
        $GLOBALS['smarty']->assign('text', $text);
        $GLOBALS['smarty']->assign('url', $url);
        $GLOBALS['smarty']->assign('time', $time);
        
        // 根据跳转时间生成跳转提示
        $cue = preg_replace('/d%/Ums', $time, $GLOBALS['_LANG']['m_dou_msg_cue']);
        $GLOBALS['smarty']->assign('cue', $cue);
        
        $GLOBALS['smarty']->display('dou_msg.dwt');
        
        exit();
    }
}

?>