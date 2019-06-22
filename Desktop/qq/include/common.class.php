<?php
/**
 * yyyq
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
class Common extends DbMysql {
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
    function get_nav($type = 'middle', $parent_id = 0, $current_module = '', $current_id = '', $current_parent_id = '') {
        $nav = array ();
        $data = $this->fn_query("SELECT * FROM " . $this->table('nav') . " ORDER BY sort ASC");
        foreach ((array) $data as $value) {
            // 根据$parent_id和$type筛选父级导航
            if ($value['parent_id'] == $parent_id && $value['type'] == $type) {
                // 如果是自定义链接则$value['guide']值链接地址，如果是内部导航则值是栏目ID
                if ($value['module'] == 'nav') {
                    if (strpos($value['guide'], 'http://') === 0 || strpos($value['guide'], 'https://') === 0) {
                        $value['url'] = $value['guide'];
                        // 自定义导航如果包含http则在新窗口打开
                        $value['target'] = true;
                    } else {
                        $value['url'] = ROOT_URL . $value['guide'];
                        // 系统会比对自定义链接是否包含在当前URL里，如果包含则高亮菜单，如果不需要此功能，请注释掉下面那行代码
                        $value['cur'] = strpos($_SERVER['REQUEST_URI'], $value['guide']);
                    }
                } else {
                    $value['url'] = $this->rewrite_url($value['module'], $value['guide']);
                    $value['cur'] = $this->dou_current($value['module'], $value['guide'], $current_module, $current_id, $current_parent_id);
                }
                
                foreach ($data as $child) {
                    // 筛选下级导航
                    if ($child['parent_id'] == $value['id']) {
                        $value['child'] = $this->get_nav($type, $value['id']);
                        break;
                    }
                }
                $nav[] = $value;
            }
        }
        
        return $nav;
    }
    
    /**
     * +----------------------------------------------------------
     * 高亮当前菜单
     * +----------------------------------------------------------
     * $module 模块名称
     * $id 当前要判断的ID
     * $current_module 当前模块名称，例如在获取导航菜单时就会涉及到不同的模块
     * $current_id 当前的ID
     * +----------------------------------------------------------
     */
    function dou_current($module, $id, $current_module, $current_id = '', $current_parent_id = '') {
        if (($id == $current_id || $id == $current_parent_id) && $module == $current_module) {
            return true;
        } elseif (!$id && $module == $current_module) {
            return true;
        }
    }

    /**
     * +----------------------------------------------------------
     * 获取网站信息
     * +----------------------------------------------------------
     */
    function get_config() {
        $query = $this->select_all('config');
        while ($row = $this->fetch_array($query)) {
            $config[$row['name']] = $row['value'];
        }
        if ($config['qq'] && !defined('IS_ADMIN')) {
            $config['qq'] = $this->dou_qq($config['qq']);
        }
     
        // 额外定义
        $config['root_url'] = ROOT_URL;
        $config['m_url'] = M_URL;
        $config['theme_url'] = ROOT_URL . 'theme/' . $config['site_theme'] . '/';
        $config['m_theme_url'] = M_URL . 'theme/' . $config['mobile_theme'] . '/';
        
        return $config;
    }
    
    /**
     * +----------------------------------------------------------
     * 重写 URL 地址
     * +----------------------------------------------------------
     * $module 模块
     * $value 根据是数字或字符来判断传递的是ID还是参数
     * +----------------------------------------------------------
     */
    function rewrite_url($module, $value = '', $type = '') {
        if (is_numeric($value)) {
            $id = $value; // 详细页和分类页会传的id和分类cat_id
        } else {
            $rec = $value; // 单模块会传递操作项值
        }
        
        if ($GLOBALS['_CFG']['rewrite']) {
            $filename = $module != 'page' ? '/' . $id : '';
            $item = (!strpos($module, 'category') && $id) ? $filename . '.html' : '';
            $url = $this->get_unique($module, $id) . $item . ($rec ? '/' . $rec : '');
        } else {
            $req = $rec ? '?rec=' . $rec : ($id ? '?id=' . $id : '');
            $url = $module . '.php' . $req;
        }
        
        if ($module == 'mobile') {
            return ROOT_URL . M_PATH; // 手机版链接
        } else {
            return ((defined('IS_MOBILE') || $type == 'mobile') ? M_URL : ROOT_URL) . $url; // 移动版和PC版的根网址不同
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 系统模块
     * +----------------------------------------------------------
     */
    function dou_module() {
        // 读取系统文件
        $setting = $this->read_system();
        $module['column'] = $setting['column_module'] ? array_filter($setting['column_module']) : array(); // 去除空值
        $module['single'] = $setting['single_module'] ? array_filter($setting['single_module']) : array();
        $module['link_user_center'] = $setting['link_user_center'] ? array_filter($setting['link_user_center']) : '';
        $module['all'] = array_merge($module['column'], $module['single']); 
        
        // 读取模块语言文件
        if (defined('IS_ADMIN')) {
            $lang_admin = $GLOBALS['_CFG']['language'] . '/admin';
            $lang_path = file_exists(ROOT_PATH . 'languages/' . $lang_admin) ? $lang_admin : 'zh_cn/admin';
        } else {
            $lang_path = $GLOBALS['_CFG']['language'];
        }
     
        $lang_list = glob(ROOT_PATH . 'languages/' . $lang_path . '/' . '*.lang.php');
        if (is_array($lang_list)) {
            foreach ($lang_list as $lang) {
                $module['lang'][] = $lang;
            }
        }

        // 读取模块初始化文件
        $init_list = glob(ROOT_PATH . 'include/' . '*.init.php');
        if (is_array($init_list)) {
            foreach ($init_list as $init) {
                $module['init'][] = $init;
            }
        }
        
        // 模块开启状态
        foreach ((array) $module['all'] as $module_id) {
            $_OPEN[$module_id] = true;
        }
        $module['open'] = $_OPEN;
        
        return $module;
    }
     
    /**
     * +----------------------------------------------------------
     * 将系统文件转换为数组
     * +----------------------------------------------------------
     */
    function read_system() {
        $content = file(ROOT_PATH . 'data/system.php'); // 不当成PHP文件，主要是为了防止手动修改此文件时产生BOM头
        foreach ((array) $content as $line) {
            $line = trim($line);
            if (strpos($line, ':') !== false) {
                $arr = explode(':', $line);
                $setting[$arr[0]] = explode(',', $arr[1]);
            }
        }
        
        return $setting;
    }
    
    /**
     * +----------------------------------------------------------
     * 所有模块URL和当前模块URL生成
     * +----------------------------------------------------------
     */
    function dou_url() {
        // 所有模块URL
        $module = $this->dou_module();
        foreach ((array) $module['column'] as $module_id) {
            $url[$module_id] = $this->rewrite_url($module_id . '_category');
        }
        foreach ((array) $module['single'] as $module_id) {
            $url[$module_id] = $this->rewrite_url($module_id);
        }

        // 购物车URL
        $url['cart'] = $this->rewrite_url('order', 'cart');

        // 会员模块常用URL
        foreach (explode('|', 'login|register|logout|order|order_list') as $value)
            $url[$value] = $this->rewrite_url('user', $value);

        // 当前模块子栏目URL
        if ($GLOBALS['subbox']['sub']) { // 判断模块页面的column值
            foreach (explode('|', $GLOBALS['subbox']['sub']) as $value) {
                $url[$value] = $this->rewrite_url($GLOBALS['subbox']['module'], $value);
            }
        }
        
        return $url;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取别名
     * +----------------------------------------------------------
     * $module 模块
     * $id 项目ID
     * +----------------------------------------------------------
     */
    function get_unique($module, $id) {
        $filed = $module == 'page' ? 'id' : 'cat_id';
        $table_module = $module;
        
        // 非单页面和分类模型下获取分类ID
        if (!strpos($module, 'category') && $module != 'page') {
            $id = $this->get_one("SELECT cat_id FROM " . $this->table($module) . " WHERE id = " . $id);
            $table_module = $module . '_category';
        }
        
        $unique_id = $this->get_one("SELECT unique_id FROM " . $this->table($table_module) . " WHERE " . $filed . " = " . $id);
        
        // 把分类页和列表的别名统一
        $module = preg_replace("/\_category/", '', $module);
        
        // 伪静态时使用的完整别名
        if ($module == 'page') {
            $unique = $unique_id;
        } elseif ($module == 'article') {
            $unique = $unique_id ? '/' . $unique_id : $unique_id;
            $unique = 'news' . $unique;
        } else {
            $unique = $unique_id ? '/' . $unique_id : $unique_id;
            $unique = $module . $unique;
        }
        
        return $unique;
    }
    
    /**
     * +----------------------------------------------------------
     * 格式化商品价格
     * +----------------------------------------------------------
     * $price 需要格式化的价格
     * +----------------------------------------------------------
     */
    function price_format($price = '') {
        $price = number_format($price, $GLOBALS['_CFG']['price_decimal']);
        $price_format = preg_replace('/d%/Ums', $price, $GLOBALS['_LANG']['price_format']);
        
        return $price_format;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取当前分类下所有子分类
     * +----------------------------------------------------------
     * $table 数据表名
     * $parent_id 父类ID
     * $child 子类ID零时存储器
     * +----------------------------------------------------------
     */
    function dou_child_id($table, $parent_id = '0', &$child_id = '') {
        $data = $this->fn_query("SELECT * FROM " . $this->table($table) . " ORDER BY sort ASC, cat_id ASC");
        foreach ((array) $data as $value) {
            if ($value['parent_id'] == $parent_id) {
                $child_id .= ',' . $value['cat_id'];
                $this->dou_child_id($table, $value['cat_id'], $child_id);
            }
        }

        return $child_id;
    }
    
    /**
     * +----------------------------------------------------------
     * 向客户端发送原始的 HTTP 报头
     * +----------------------------------------------------------
     * $url 跳转网址
     * +----------------------------------------------------------
     */
    function dou_header($url) {
        header("Location: " . $url);
        exit;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取无层次商品分类，将所有分类存至同一级数组，用$mark作为标记区分
     * +----------------------------------------------------------
     * $table 数据表名
     * $parent_id 默认获取一级导航
     * $level 无限极分类层次
     * $current_id 当前页面栏目ID
     * $category_nolevel 储存分类信息的数组
     * $mark 无限极分类标记
     * +----------------------------------------------------------
     */
    function get_category_nolevel($table, $parent_id = 0, $level = 0, $current_id = '', &$category_nolevel = array(), $mark = '-') {
        $data = $this->fn_query("SELECT * FROM " . $this->table($table) . " ORDER BY sort ASC, cat_id ASC");
        foreach ((array) $data as $value) {
            if ($value['parent_id'] == $parent_id && $value['cat_id'] != $current_id) {
                $value['url'] = $this->rewrite_url($table, $value['cat_id']);
                $value['mark'] = str_repeat($mark, $level);
                $category_nolevel[] = $value;
                $this->get_category_nolevel($table, $value['cat_id'], $level + 1, $current_id, $category_nolevel);
            }
        }
        
        return $category_nolevel;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取无层次单页面列表
     * +----------------------------------------------------------
     * $parent_id 调用该ID下的所有单页面，为空时则调用所有
     * $level 无限极分类层次
     * $current_id 当前页面栏目ID
     * $mark 无限极分类标记
     * +----------------------------------------------------------
     */
    function get_page_nolevel($parent_id = 0, $level = 0, $current_id = '', &$page_nolevel = array(), $mark = '-') {
        $data = $this->fn_query("SELECT * FROM " . $this->table('page'));
        foreach ((array) $data as $value) {
            if ($value['parent_id'] == $parent_id && $value['id'] != $current_id) {
                $value['url'] = $this->rewrite_url('page', $value['id']);
                $value['mark'] = str_repeat($mark, $level);
                $value['level'] = $level;
                $page_nolevel[] = $value;
                $this->get_page_nolevel($value['id'], $level + 1, $current_id, $page_nolevel);
            }
        }
        return $page_nolevel;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取幻灯图片列表
     * +----------------------------------------------------------
     */
    function get_show_list($type = 'pc') {
        if ($type) {
            $where = " WHERE type = '$type'";
        }
        
        $sql = "SELECT * FROM " . $this->table('show') . $where . " ORDER BY sort ASC, id ASC";
        $query = $this->query($sql);
        while ($row = $this->fetch_array($query)) {
            $show_list[] = array (
                    "id" => $row['id'],
                    "show_name" => $row['show_name'],
                    "show_link" => $row['show_link'],
                    "show_img" => $this->dou_file($row['show_img']),
                    "sort" => $row['sort'] 
            );
        }
        
        return $show_list;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取列表
     * +----------------------------------------------------------
     * $module 模块
     * $cat_id 分类ID
     * $num 调用数量
     * $sort 排序
     * +----------------------------------------------------------
     */
    function get_list($module, $cat_id = '', $num = '', $sort = '') {
        $where = $cat_id == 'ALL' ? '' : " WHERE cat_id IN (" . $cat_id . $this->dou_child_id($module . '_category', $cat_id) . ")";
        $sort = $sort ? $sort . ',' : '';
        $limit = $num ? ' LIMIT ' . $num : '';
        
        $sql = "SELECT * FROM " . $this->table($module) . $where . " ORDER BY " . $sort . "id DESC" . $limit;
        $query = $this->query($sql);
        while ($row = $this->fetch_array($query)) {
            $item['id'] = $row['id'];
            if ($row['title']) $item['title'] = $row['title'];
            if ($row['name']) $item['name'] = $row['name'];
            if (!empty($row['price'])) $item['price'] = $row['price'] > 0 ? $this->price_format($row['price']) : $GLOBALS['_LANG']['price_discuss'];
            if ($row['click']) $item['click'] = $row['click'];

            $item['add_time'] = date("Y-m-d", $row['add_time']);
            $item['add_time_short'] = date("m-d", $row['add_time']);
            $item['description'] = $row['description'] ? $row['description'] : $this->dou_substr($row['content'], 320);
            $item['image'] = $this->dou_file($row['image']);
            $item['thumb'] = $this->dou_file($row['image'], true);
            $item['url'] = $this->rewrite_url($module, $row['id']);
            
            $list[] = $item;
        }
        
        return $list;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取有层次的栏目分类，有几层分类就创建几维数组
     * +----------------------------------------------------------
     * $table 数据表名
     * $parent_id 默认获取一级导航
     * $current_id 当前页面栏目ID
     * +----------------------------------------------------------
     */
    function get_category($table, $parent_id = 0, $current_id = '') {
        $category = array ();
        $data = $this->fn_query("SELECT * FROM " . $this->table($table) . " ORDER BY sort ASC, cat_id ASC");
        foreach ((array) $data as $value) {
            // $parent_id将在嵌套函数中随之变化
            if ($value['parent_id'] == $parent_id) {
                $value['url'] = $this->rewrite_url($table, $value['cat_id']);
                $value['cur'] = $value['cat_id'] == $current_id ? true : false;
                
                foreach ($data as $child) {
                    // 筛选下级导航
                    if ($child['parent_id'] == $value['cat_id']) {
                        // 嵌套函数获取子分类
                        $value['child'] = $this->get_category($table, $value['cat_id'], $current_id);
                        break;
                    }
                }
                $category[] = $value;
            }
        }
        
        return $category;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取指定分类单页面列表
     * +----------------------------------------------------------
     * $parent_id 调用该ID下的所有单页面，为空时则调用所有
     * $current_id 当前打开的单页面ID，高亮菜单使用
     * +----------------------------------------------------------
     */
    function get_page_list($parent_id = 0, $current_id = '') {
        $page_list = array ();
        $data = $this->fn_query("SELECT * FROM " . $this->table('page') . " ORDER BY id ASC");
        foreach ((array) $data as $value) {
            // $parent_id将在嵌套函数中随之变化
            if ($value['parent_id'] == $parent_id) {
                $value['url'] = $this->rewrite_url('page', $value['id']);
                $value['cur'] = $value['id'] == $current_id ? true : false;
                
                foreach ($data as $child) {
                    // 筛选下级单页面
                    if ($child['parent_id'] == $value['id']) {
                        // 嵌套函数获取子分类
                        $value['child'] = $this->get_page_list($value['id'], $current_id);
                        break;
                    }
                }
                $page_list[] = $value;
            }
        }
        
        return $page_list;
    }
 
    /**
     * +----------------------------------------------------------
     * 获取文件列表
     * +----------------------------------------------------------
     */
    function get_file_list($module, $item_id, $type) {
        $sql = "SELECT number, file FROM " . $this->table('file') . " WHERE module = '$module' AND item_id = '$item_id' AND type = '$type' ORDER BY id DESC";
        $query = $this->query($sql);
        while ($row = $this->fetch_array($query)) {
            $file_list[] = array (
                    "number" => $row['number'],
                    "file" => $row['file'] ? ROOT_URL . $row['file'] : ''
            );
        }

        return $file_list;
    }
    
    /**
     * +----------------------------------------------------------
     * 分页
     * +----------------------------------------------------------
     * $sql SQL查询条件
     * $page_size 每页显示数量
     * $page 当前页码
     * $page_url 地址栏中参数传递
     * $get 地址栏中参数传递
     * $close_rewrite 强制关闭伪静态
     * +----------------------------------------------------------
     */
    function pager($sql, $page_size = 10, $page, $page_url = '', $get = '', $close_rewrite = false) {
        $record_count = $this->num_rows($this->query($sql));
        
        // 调整分页链接样式
        if (!defined('IS_ADMIN') && $GLOBALS['_CFG']['rewrite'] && !$close_rewrite) {
            $get_page = '/o';
            
            // 如果$page_url包含参数，在系统设置伪静态开启和不开启两种情况下会出现url中包含和不包含问号的情况
            if ($get) {
                $get = preg_replace('/&/', '?', $get, 1);
                $get = '/' . $get; // 起始参数前加'/'
            }
        } else {
            $get_page = strpos($page_url, '?') !== false ? '&page=' : '?page=';
        }
        
        $page_count = ceil($record_count / $page_size);
        $page_count = $page_count > 0 ? $page_count : 1; 
        $first = $page_url . $get_page . '1' . $get;
        $previous = $page_url . $get_page . ($page > 1 ? $page - 1 : 1) . $get;
        $next = $page_url . $get_page . ($page_count > $page ? $page + 1 : $page_count) . $get;
        $last = $page_url . $get_page . $page_count . $get;
        
        // 页码循环显示
        for ($p = 1; $p <= $page_count; $p++) {
            $box[] = array (
                    "page" => $p,
                    "url" => $page_url . $get_page . $p . $get,
                    "cur" => $p == $page ? true : false
            );
        }
     
        $pager = array (
                "record_count" => $record_count,
                "page_size" => $page_size,
                "page" => $page,
                "page_count" => $page_count,
                "box" => $box,
                "previous" => $previous,
                "next" => $next,
                "first" => $first,
                "last" => $last 
        );
        
        $start = ($page - 1) * $page_size;
        $limit = " LIMIT $start, $page_size";
        
        $GLOBALS['smarty']->assign('pager', $pager);
        
        return $limit;
    }
    
    /**
     * +----------------------------------------------------------
     * 把第一个把第一个AND替换成WHERE
     * +----------------------------------------------------------
     */
    function where($where) {
        if (strpos($where, 'WHERE')) {
            return $where;
        } else {
            return preg_replace('/AND/', 'WHERE', $where, 1);
        }
    }
  
    /**
     * +----------------------------------------------------------
     * 把第一个把第一个&替换成?
     * +----------------------------------------------------------
     */
    function param($param) {
        if (strpos($param, '?')) {
            return $param;
        } else {
            return preg_replace('/&/', '?', $param, 1);
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 获取上一项下一项
     * +----------------------------------------------------------
     */
    function lift($module, $id, $cat_id) {
        $field = $this->field_exist($module, 'title') ? 'title' : 'name'; // 判断包含title字段还是name字段
        $screen = $cat_id ? " AND cat_id = $cat_id" : ''; // 判断是否有分类筛选
        
        // 上一项
        $lift['previous'] = $this->fetch_assoc($this->query("SELECT id, " . $field . " FROM " . $this->table($module) . " WHERE id > $id" . $screen . " ORDER BY id ASC"));
        if ($lift['previous']) $lift['previous']['url'] = $this->rewrite_url($module, $lift['previous']['id']);
        // 下一项
        $lift['next'] = $this->fetch_assoc($this->query("SELECT id, " . $field . " FROM " . $this->table($module) . " WHERE id < $id" . $screen . " ORDER BY id DESC"));
        if ($lift['next']) $lift['next']['url'] = $this->rewrite_url($module, $lift['next']['id']);
        
        return $lift;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取真实IP地址
     * +----------------------------------------------------------
     */
    function get_ip() {
        static $ip;
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } else {
                $ip = getenv("REMOTE_ADDR");
            }
        }
        
        if (preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $ip)) {
            return $ip;
        } else {
            return '127.0.0.1';
        }
    }

    /**
     * +----------------------------------------------------------
     * 获取第一条记录
     * +----------------------------------------------------------
     * $log 日志内容
     * $desc 是否倒序
     * +----------------------------------------------------------
     */
    function get_first_log($log, $desc = false) {
        $log_array = explode(',', $log);
        $log = $desc ? ($log_array[1] ? $log_array[1] : $log_array[0]) : $log_array[0];
        return $log;
    }

    /**
     * +----------------------------------------------------------
     * 获取插件配置信息
     * +----------------------------------------------------------
     * $unique_id 插件唯一ID
     * +----------------------------------------------------------
     */
    function get_plugin($unique_id) {
        $plugin = $this->get_row('plugin', '*', "unique_id = '$unique_id'");
        if ($plugin['config'])
            $plugin['config'] = unserialize($plugin['config']);
        
        return $plugin;
    }
 
    /**
     * +----------------------------------------------------------
     * 验证从模板引入的PHP文件
     * +----------------------------------------------------------
     * $file 需要验证码的PHP文件
     * +----------------------------------------------------------
     */
    function check_from_theme_php($file) {
        $content = file_get_contents($file);
        $illegal_word = array('insert', 'update', 'delete', 'create', 'truncate', 'drop', 'alter', 'into', 'load_file', 'outfile');
        $m = 0;
        for ($i=0; $i<count($illegal_word); $i++){
            if (stripos($content, $illegal_word[$i]) !== false) {
                $m++;
            }
        }
        if ($m == 0) return true;
    }
    
    /**
     * +----------------------------------------------------------
     * 判断 文件/目录 是否可写
     * +----------------------------------------------------------
     */
    function check_read_write($file) {
        if (file_exists($file)) {
            if (is_dir($file)) {
                $dir = $file;
                if ($fp = @fopen("$dir/test.txt", 'w')) {
                    @fclose($fp);
                    @unlink("$dir/test.txt");
                    $status = 'write';
                } else {
                    $status = 'no_write';
                }
            } else {
                if ($fp = @fopen($file, 'a+')) {
                    @fclose($fp);
                    $status = 'write';
                } else {
                    $status = 'no_write';
                }
            }
        } else {
            $status = 'no_exist';
        }
        
        return $status;
    }
    
    /**
     * +----------------------------------------------------------
     * 获取去除路径和扩展名的文件名
     * +----------------------------------------------------------
     * $file 图片地址
     * +----------------------------------------------------------
     */
    function get_file_name($file) {
        $basename = basename($file);
        return $file_name = substr($basename, 0, strrpos($basename, '.'));
    }

    /**
     * +----------------------------------------------------------
     * 邮件发送
     * +----------------------------------------------------------
     * $mailto 收件人地址
     * $title 邮件标题
     * $body 邮件正文
     * +----------------------------------------------------------
     */
    function send_mail($mailto, $subject = '', $body = '') {
        if ($GLOBALS['_CFG']['mail_service'] && file_exists(ROOT_PATH . 'include/mail.class.php')) {
            include_once (ROOT_PATH . 'include/mail.class.php');
            include_once (ROOT_PATH . 'include/smtp.class.php');

            $mail = new PHPMailer;                                // 实例化
            
            //$mail->SMTPDebug = 3;                               // 启用SMTP调试功能
             
            $mail->CharSet ="UTF-8";                              // 设定邮件编码
            $mail->isSMTP();                                      // 设定使用SMTP服务
            $mail->Host = $GLOBALS['_CFG']['mail_host'];          // SMTP服务器
            $mail->SMTPAuth = true;                               // 启用SMTP验证功能
            $mail->Username = $GLOBALS['_CFG']['mail_username'];  // SMTP服务器用户名
            $mail->Password = $GLOBALS['_CFG']['mail_password'];  // SMTP服务器密码
            if ($GLOBALS['_CFG']['mail_ssl'])
                $mail->SMTPSecure = 'ssl';                        // 安全协议，可以注释掉
            $mail->Port = $GLOBALS['_CFG']['mail_port'];          // SMTP服务器的端口号
            
            $mail->From = $GLOBALS['_CFG']['mail_username'];      // 发件人地址
            $mail->FromName = $GLOBALS['_CFG']['site_name'];      // 发件人姓名
            $mail->addAddress($mailto, '');                       // 收件地址，可选指定收件人姓名
            
            $mail->isHTML(true);                                  // 是否HTML格式邮件
            
            $mail->Subject = $subject;                            // 邮件标题
            $mail->Body    = $body;                               // 邮件内容
            
            // 邮件正文不支持HTML的备用显示
            $mail->AltBody = $GLOBALS['_LANG']['mail_altbody']; 
            
            if($mail->send()) {
                return true;
            }
        } else {
            $subject = "=?UTF-8?B?".base64_encode($subject)."?=";          // 解决邮件主题乱码问题，UTF8编码格式
            $header  = "From: ".$GLOBALS['_CFG']['site_name']." <".$GLOBALS['_CFG']['email'].">\n";
            $header .= "Return-Path: <".$GLOBALS['_CFG']['email'].">\n";   // 防止被当做垃圾邮件
            $header .= "MIME-Version: 1.0\n";
            $header .= "Content-type: text/html; charset=utf-8\n";         // 邮件内容为utf-8编码
            $header .= "Content-Transfer-Encoding: 8bit\r\n";              // 注意header的结尾，只有这个后面有\r
            ini_set('sendmail_from', $GLOBALS['_CFG']['email']);           // 解决mail的一个bug
            $body = wordwrap($body, 70);                                   // 每行最多70个字符,这个是mail方法的限制
            if (mail($mailto, $subject, $body, $header))
                return ture;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 生成在线客服QQ数组
     * +----------------------------------------------------------
     */
    function dou_qq($im) {
        $im_explode = explode(',', $im);
        foreach ((array) $im_explode as $value) {
            if (strpos($value, '/') !== false) {
                $arr = explode('/', $value);
                $list['number'] = $arr['0'];
                $list['nickname'] = $arr['1'];
                $im_list[] = $list;
            } else {
                $im_list[]['number'] = $value;
            }
        }
        
        return $im_list;
    }
    
    /**
     * +----------------------------------------------------------
     * 清除html,换行，空格类并且可以截取内容长度
     * +----------------------------------------------------------
     * $str 要处理的内容
     * $length 要保留的长度
     * $charset 要处理的内容的编码，一般情况无需设置
     * +----------------------------------------------------------
     */
    function dou_substr($str, $length, $clear_space = true, $charset = DOU_CHARSET) {
        $str = trim($str); // 清除字符串两边的空格
        $str = strip_tags($str, ""); // 利用php自带的函数清除html格式
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        // 判断是否清除空格
        if ($clear_space) {
            $str = preg_replace("/\t/", "", $str);
            $str = preg_replace("/ /", "", $str);
            $str = preg_replace("/&nbsp;/", "", $str); // 匹配html中的空格
        }
        $str = trim($str); // 清除字符串两边的空格
        
        if (function_exists("mb_substr")) {
            $substr = mb_substr($str, 0, $length, $charset);
        } else {
            $c['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $c['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            preg_match_all($c[$charset], $str, $match);
            $substr = join("", array_slice($match[0], 0, $length));
        }
        
        return $substr;
    }

    /**
     * +----------------------------------------------------------
     * 生成随机数
     * +----------------------------------------------------------
     * $type 随机字符类型
     * $length 长度
     * $prefix 前缀
     * +----------------------------------------------------------
     */
    function create_rand_string($type = 'number', $length = 6, $prefix = '', $custom_chars = '') {
        // 设置随机字符范围
        switch ($type) {
            case 'number' :
                $chars = '0123456789';
                break;
            case 'letter' :
                $chars = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 'letter.LETTER' :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'letter.LETTER.number' : // 去掉了容易混淆的字符oOLl和数字01
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                break;
            default : // 'letter.number'
                $chars = 'abcdefghijkmnpqrstuvwxyz23456789';
        }
        
        // 如果有自定义的字符则包含进去
        $chars = $chars . $custom_chars;
        
        $string = '';
        for($i = 0; $i < $length; $i++) {
            $string .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        
        return $prefix . $string;
    }
 
    /**
     * +----------------------------------------------------------
     * 生成随机数
     * +----------------------------------------------------------
     * $number 文件编号
     * $thumb 显示缩略图
     * +----------------------------------------------------------
     */
    function dou_file($number, $thumb = false) {
        $file = $this->get_one("SELECT file FROM " . $this->table('file') . " WHERE number = '$number'");
        if (empty($file)) return false;
     
        if ($thumb) {
            $image = explode(".", $file);
            return ROOT_URL . $image[0] . "_thumb." . $image[1];
        } else {
            return ROOT_URL . $file;
        }
    }
 
    /**
     * +----------------------------------------------------------
     * 读取子目录绑定配置文件
     * +----------------------------------------------------------
     */
    function read_subdir_binding() {
        if (file_exists($subdir_binding_file = ROOT_PATH . "data/subdir.binding")) {
            $content = file_get_contents($subdir_binding_file);
            return trim($content);
        } else {
            return null;
        }
    }
 
    /**
     * +----------------------------------------------------------
     * 获取字段中不重复的值
     * +----------------------------------------------------------
     * $module 模块名称
     * $field 字段
     * $current_value 当前值
     * +----------------------------------------------------------
     */
    function get_no_repeat_value($module, $field, $current_value = '') {
        $no_repeat_value_option = array();
        $sql = "SELECT `" . $field . "` FROM " . $GLOBALS['dou']->table($module);
        $query = $GLOBALS['dou']->query($sql);
        while ($row = $GLOBALS['dou']->fetch_array($query)) {
            $no_repeat_value_option[] = $row[$field];
        }
        $no_repeat_value_option = array_filter(array_unique($no_repeat_value_option)); // 过滤掉重复值并去掉空值

        foreach ($no_repeat_value_option as $value) {
            $no_repeat_value[] = array (
                    "value" => $value,
                    "cur" => $current_value ? ($current_value == $value ? true : false) : false // 如果当前属性POST传递值跟筛选值相同则高亮 
            );
        }

        return $no_repeat_value;
    }
	
	   /**
     * +----------------------------------------------------------
     * 字段值语言化
     * +----------------------------------------------------------
     * $prefix 语言文件前缀
     * $value_list 值列表
     * $cur 当前值
     * +----------------------------------------------------------
     */
    function data_list_lang_format($prefix, $value_list, $cur = '') {
        foreach (explode(',', $value_list) as $row) {
									   $row = trim($row);
            $value_lang[] = array (
                    "value" => $row,
                    "format" => $GLOBALS['_LANG'][$prefix . $row],
                    "cur" => $row == $cur ? true : false
            );
        }
        
        return $value_lang;
    }
	
	   /**
     * +----------------------------------------------------------
     * 字段值语言化
     * +----------------------------------------------------------
     * $prefix 语言文件前缀
     * $value 值
     * +----------------------------------------------------------
     */
    function data_lang_format($prefix, $value) {
								$lang = array (
																"value" => $value,
																"format" => $GLOBALS['_LANG'][$prefix . $value]
								);
        
        return $lang;
    }
 
}

?>