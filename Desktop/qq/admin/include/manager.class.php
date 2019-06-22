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
class Manager extends Common {
    /**
     * +----------------------------------------------------------
     * 用户权限判断
     * +----------------------------------------------------------
     * $user_id 管理员ID
     * $shell 登录标识
     * $action_list 管理员权限
     * +----------------------------------------------------------
     */
    function admin_check($user_id, $shell, $action_list = 'ALL') {
        if (!defined('NO_CHECK')) { // 有定义NO_CHECK常量的页面无需管理员权限
            if ($row = $this->admin_info($user_id, $shell)) {
                $this->admin_ontime(); // 登录超时默认为3小时(10800秒)
                $admin_info = array ( // 全局管理员信息
                        'user_id' => $row['user_id'],
                        'user_name' => $row['user_name'],
                        'email' => $row['email'],
                        'action_list' => $row['action_list'] 
                );
                
                return $admin_info;
            } else {
                $this->dou_header(ROOT_URL . ADMIN_PATH . '/login.php');
            }
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 管理员信息
     * +----------------------------------------------------------
     * $user_id 管理员ID
     * $shell 登录标识
     * +----------------------------------------------------------
     */
    function admin_info($user_id, $shell) {
        // 获取对应ID的会员信息
        $user = $this->get_row('admin', '*', "user_id = '$user_id'");
        
        // 如果存在$user且$shell吻合，则返回会员信息，否则返回空
        if (is_array($user) && $shell == md5($user['user_name'] . $user['password'] . DOU_SHELL)) {
            return $user;
        } else {
            return null;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 登录超时默认为3小时(10800秒)
     * +----------------------------------------------------------
     * $timeout 登录超时时间
     * +----------------------------------------------------------
     */
    function admin_ontime($timeout = '10800') {
        $ontime = $_SESSION[DOU_ID]['ontime']; // 登录时间
        $cur_time = time(); // 当前时间
        if ($cur_time - $ontime > $timeout) {
            unset($_SESSION[DOU_ID]); // 在线时间如果大于超时时间则清除登录缓存
        } else {
            $_SESSION[DOU_ID]['ontime'] = time(); // 如果未登录超时则重新设定登录时间
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 找回密码验证
     * +----------------------------------------------------------
     * $user_id 会员ID
     * $code 密码找回码
     * $timeout 默认为24小时(86400秒)
     * +----------------------------------------------------------
     */
    function check_password_reset($user_id, $code, $timeout = 86400) {
        if ($this->value_exist('admin', 'user_id', $user_id)) {
            $user = $this->get_row('admin', '*', "user_id = '$user_id'");
            
            // 初始化
            $get_code = substr($code , 0 , 16);
            $get_time = substr($code , 16 , 26);
            $code = substr(md5($user['user_name'] . $user['email'] . $user['password'] . $get_time . $user['last_login'] . DOU_SHELL) , 0 , 16);
            
            // 验证链接有效性
            if (time() - $get_time < $timeout && $code == $get_code) return true;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 获取管理员日志
     * +----------------------------------------------------------
     * $action 管理员操作的内容
     * +----------------------------------------------------------
     */
    function create_admin_log($action) {
        $create_time = time();
        $ip = $this->get_ip();
        $action = $GLOBALS['firewall']->dou_foreground($action);
        
        $sql = "INSERT INTO " . $this->table('admin_log') . " (id, create_time, user_id, action ,ip)" . " VALUES (NULL, '$create_time', " . $_SESSION[DOU_ID]['user_id'] . ", '$action', '$ip')";
        $this->query($sql);
    }
    
    /**
     * +----------------------------------------------------------
     * 获取管理员日志
     * +----------------------------------------------------------
     * $user_id 管理员ID
     * $num 调用日志数量
     * +----------------------------------------------------------
     */
    function get_admin_log($user_id = '', $num = '') {
        if ($user_id) {
            $where = " WHERE user_id = '$user_id'";
        }
        if ($num) {
            $limit = " LIMIT $num";
        }
        
        $sql = "SELECT * FROM " . $this->table('admin_log') . $where . " ORDER BY id DESC" . $limit;
        $query = $this->query($sql);
        while ($row = $this->fetch_array($query)) {
            $create_time = date("Y-m-d H:i:s", $row['create_time']);
            $user_name = $this->get_one("SELECT user_name FROM " . $this->table('admin') . " WHERE user_id = " . $row['user_id']);
            
            $log_list[] = array (
                    "id" => $row['id'],
                    "create_time" => $create_time,
                    "user_name" => $user_name,
                    "action" => $row['action'],
                    "ip" => $row['ip'] 
            );
        }
        
        return $log_list;
    }
}

?>