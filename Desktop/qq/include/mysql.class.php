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
class DbMysql {
    private $dbhost; // 数据库主机
    private $dbuser; // 数据库用户名
    private $dbpass; // 数据库用户名密码
    private $dbname; // 数据库名
    private $dou_link; // 数据库连接标识
    private $prefix; // 数据库前缀
    private $charset; // 数据库编码，GBK,UTF8,gb2312
    private $pconnect; // 持久链接,1为开启,0为关闭
    private $sql; // sql执行语句
    private $result; // 执行query命令的结果资源标识
    private $error_msg; // 数据库错误提示
                        
    // 构造函数
    function __construct($dbhost, $dbuser, $dbpass, $dbname = '', $prefix, $charset = 'utf8', $pconnect = 0) {
        $this->dbhost = $dbhost;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
        $this->dbname = $dbname;
        $this->prefix = $prefix;
        $this->charset = strtolower(str_replace('-', '', $charset));
        $this->pconnect = $pconnect;
        $this->connect();
    }
 
    // 构造函数.兼容PHP4
    function DbMysql($dbhost, $dbuser, $dbpass, $dbname = '', $prefix, $charset = 'utf8', $pconnect = 0) {
        $this->__construct($dbhost, $dbuser, $dbpass, $dbname, $prefix, $charset, $pconnect);
    }
    
    // 数据库连接
    function connect() {
        if ($this->pconnect) {
            if (!$this->dou_link = @mysqli_pconnect($this->dbhost, $this->dbuser, $this->dbpass)) {
                $this->error('Can not pconnect to mysql server');
                return false;
            }
        } else {
            list($dbhost, $dbport) = explode(':', $this->dbhost);
            if (!$this->dou_link = @mysqli_connect($dbhost, $this->dbuser, $this->dbpass, null, $dbport)) {
                $this->error('Can not connect to mysql server');
                return false;
            }
        }
        
        if ($this->version() > '4.1') {
            if ($this->charset) {
                $this->query("SET character_set_connection=" . $this->charset . ", character_set_results=" . $this->charset . ", character_set_client=binary");
            }
            
            if ($this->version() > '5.0.1') {
                $this->query("SET sql_mode=''");
            }
        }
        
        if (mysqli_select_db($this->dou_link, $this->dbname) === false ) {
            $this->error("NO THIS DBNAME:" . $this->dbname);
            return false;
        }
    }
    
    // 数据库执行语句，可执行查询添加修改删除等任何sql语句
    function query($sql) {
        $this->sql = $sql;
        $query = mysqli_query($this->dou_link, $this->sql);
        return $query;
    }
    
    // 取得前一次 MySQL 操作所影响的记录行数
    function affected_rows() {
        return mysqli_affected_rows($this->dou_link);
    }
    
    // 返回结果集中一个字段的值
    function result($query, $row) {
        return @mysqli_result($query, $row);
    }
    
    // 返回结果集中行的数目
    function num_rows($query) {
        return mysqli_num_rows($query);
    }
    
    // 返回结果集中字段的数
    function num_fields($query) {
        return mysqli_num_fields($query);
    }
    
    // 释放结果内存
    function free_result($query) {
        return mysqli_free_result($query);
    }
    
    // 返回上一步 INSERT 操作产生的 ID
    function insert_id() {
        return mysqli_insert_id($this->dou_link);
    }
    
    // 从结果集中取得一行作为数字数组
    function fetch_row($query) {
        return mysqli_fetch_row($query);
    }
    
    // 从结果集中取得一行作为关联数组
    function fetch_assoc($query) {
        return mysqli_fetch_assoc($query);
    }
    
    // 从结果集中取得一行作为关联数组也可以得到索引数组，也可以二者都有
    function fetch_array($query, $resulttype = MYSQLI_BOTH) {
        return mysqli_fetch_array($query, $resulttype);
    }
    
    // 获取下一个自增(id)值
    function auto_id($table) {
        return $this->get_one("SELECT auto_increment FROM information_schema.`TABLES` WHERE  TABLE_SCHEMA='" . $this->dbname . "' AND TABLE_NAME = '" . trim($this->table($table), '`') . "'");
    }
    
    // 返回 MySQL 服务器的信息
    function version() {
        if (empty($this->version)) {
            $this->version = mysqli_get_server_info($this->dou_link);
        }
        return $this->version;
    }
    
    // 关闭 MySQL 连接
    function close() {
        return mysqli_close($this->dou_link);
    }
    
    // 将指定的表名加上前缀后返回
    function table($str) {
        return '`' . $this->prefix . $str . '`';
    }
    
    // 条件查询
    function select($table, $field = "*", $where = '', $debug = '') {
        $where = $where ? ' WHERE ' . $where : NULL;
        if ($debug) {
            echo "SELECT $field FROM " . $this->table($table) . " $where";
        } else {
            $query = $this->query("SELECT $field FROM " . $this->table($table) . " $where");
            return $query;
        }
    }
    
    // 查询全部
    function select_all($table) {
        return $this->query("SELECT * FROM " . $this->table($table));
    }
    
    // 判断表是否存在
    function table_exist($table) {
        if($this->num_rows($this->query("SHOW TABLES LIKE '" . trim($this->table($table), '`') . "'")) == 1)
            return true;
    }
    
    // 判断字段是否存在
    function field_exist($table, $field) {
        $sql = "SHOW COLUMNS FROM " . $this->table($table);
        $query = $this->query($sql);
        if ($query !== false) {
            while($row = $this->fetch_array($query))   {
                $array[] = $row['Field'];
            }

            if (in_array($field, $array)) return true;
        }
    }
    
    // 验证信息是否已经存在
    function value_exist($table, $field, $value, $where = '') {
        $sql = "SELECT $field FROM " . $this->table($table) . " WHERE $field = '$value'" . $where;
        $number = $this->num_rows($this->query($sql));
        
        if ($number > 0)
            return true;
    }
 
    // 读取一条数据值
    function get_row($table, $field, $where = '') {
        $sql = "SELECT $field FROM " . $this->table($table) . " WHERE " . $where;
        $query = $this->query($sql);
        if ($query !== false) {
            return $this->fetch_assoc($query);
        } else {
            return false;
        }
    }
    
    // 删除数据
    function delete($table, $condition, $url = '') {
        if ($this->query("DELETE FROM " . $this->table($table) . " WHERE $condition")) {
            if (!empty($url)) {
                $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['del_succes'], $url);
            }
        }
    }

    // 读取一条数据一个值
    function get_one($sql, $limited = false) {
        if ($limited == true) {
            $sql = trim($sql . ' LIMIT 1');
        }
        
        $query = $this->query($sql);
        if ($query !== false) {
            $row = $this->fetch_row($query);
            
            if ($row !== false) {
                return $row[0];
            } else {
                return '';
            }
        } else {
            return false;
        }
    }
    
    // 转义特殊字符
    function escape_string($string) {
        if (PHP_VERSION >= '4.3') {
            return mysqli_real_escape_string($this->dou_link, $string);
        } else {
            return mysqli_escape_string($this->dou_link, $string);
        }
    }
    
    // 返回错误信息
    function error($msg = '') {
        $msg = $msg ? "DouPHP Error: $msg" : '<b>MySQL server error report</b><br>' . $this->error_msg;
        exit($msg);
    }
    
    // 循环读取结果集并储存至数组
    function fn_query($sql) {
        $query = $this->query($sql);
        if ($query !== false) {
            while ($row = $this->fetch_assoc($query)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    
    // 数据库导入
    function fn_execute($sql) {
        $sqls = $this->fn_split($sql);
        if (is_array($sqls)) {
            foreach ($sqls as $sql) {
                if (trim($sql) != '')
                    $this->query($sql);
            }
        } else {
            $this->query($sqls);
        }
        return true;
    }
 
    // 数据分离
    function fn_split($sql) {
        if ($this->version() > '4.1' && $this->charset)
            $sql = preg_replace("/TYPE=(InnoDB|MyISAM)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=" . $this->charset, $sql);
        
        $sql = str_replace("\r", "\n", $sql);
        $ret = array ();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return ($ret);
    }
}

?>