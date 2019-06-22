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

if (!defined('SMARTY_DIR')) {
    define('SMARTY_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

if (!defined('SMARTY_CORE_DIR')) {
    define('SMARTY_CORE_DIR', SMARTY_DIR . 'internals' . DIRECTORY_SEPARATOR);
}

class Smarty {
    var $template_dir = 'templates';
    var $compile_dir = 'templates_c';
    var $error_reporting = null;
    var $compile_check = true; // 设置为true时，PHP程序每次调用时，Smarty都会检查当前的模板从上次编译后是否已经被修改过（依据时间戳）， 如果已被修改，它会重新编译该模板。 如果当该模板是从未被编译过的，那么它会忽略此设置并进行编译（不管这变量是否设置成false）
    var $force_compile = false; // 强制让Smarty每次调用时，都（重新）编译模板文件。 该设置将重写 $compile_check值。 默认情况下是false。 这是开发和调试时比较方便的工具，但在实际生产环境中绝对不能使用。 如果$caching是开启的，那么缓存文件将每次都会被重新缓存。
    var $caching = 0;
    var $cache_dir = 'cache';
    var $cache_lifetime = 3600; // 定义模板缓存文件的有效时间（按秒）。 当缓存过期了，该缓存将被重新生成
    var $cache_modified_check = false; // 设置为true的时候，Smarty采用浏览器的头信息“If-Modified-Since”。如果上次访问后缓存文件的时间戳并没有发生修改，则会返回“304: Not Modified”给浏览器
    var $left_delimiter = '{';
    var $right_delimiter = '}';
    var $request_use_auto_globals = true;
    var $compile_id = null; // 永久的编译标识id
    var $use_sub_dirs = false; // 当设置成true的时候， Smarty将在编译目录 和 缓存目录下面创建子目录
    var $default_modifiers = array(); // 这是一个包含修饰器名称的数组，这些修饰器将默认地对每个模板变量进行修饰操作。 例如我们对每个变量都进行HTML编码，可以用array('escape:"htmlall"')。 如果某个变量要避免这种默认的修饰操作，则需要加上“nofilter”的属性，例如{$var nofilter}
    var $_tpl_vars = array(); // 模板变量
    var $_smarty_vars = null;
    var $_sections = array();
    var $_foreach = array();
    var $_tag_stack = array();
    var $_config = array(array('vars'  => array(), 'files' => array())); // 考虑删掉
    var $_smarty_md5 = 'f8d698aea36fcbead2b9d5359ffca76f';
    var $_version = '2.6.26';
    var $_inclusion_depth = 0;
    var $_cache_info = array();
    var $_file_perms = 0644;
    var $_dir_perms = 0771;
    var $_prefilter = null; // 前置过滤器.Dou
    var $_cache_serials = array();
    var $_cache_include = null;
    var $_cache_including = false; // 指示当前代码是否在编译中使用
 
    /* Smarty_Compiler ---------------------------------------------------------- */
    var $_folded_blocks = array();    // 保留包围的区域，如literal包围的区域不被当成Smarty语法
    var $_current_file = null;       // 当前正在编译的模板
    var $_current_line_no = 1;          // 错误消息的行号
    var $_capture_stack = array();    // keeps track of nested capture buffers
    var $_permitted_tokens = array('true','false','yes','no','on','off','null');
    
    // 在构造函数中设置 regexp（正则表达式）
    var $_db_qstr_regexp = null;        
    var $_si_qstr_regexp = null;
    var $_qstr_regexp = null;
    var $_func_regexp = null;
    var $_var_bracket_regexp = null;
    var $_num_const_regexp = null;
    var $_dvar_guts_regexp = null;
    var $_dvar_regexp = null;
    var $_cvar_regexp = null;
    var $_svar_regexp = null;
    var $_avar_regexp = null;
    var $_mod_regexp = null;
    var $_var_regexp = null;
    var $_parenth_param_regexp = null;
    var $_func_call_regexp = null;
    var $_obj_ext_regexp = null;
    var $_obj_start_regexp = null;
    var $_obj_params_regexp = null;
    var $_obj_call_regexp = null;
    var $_cache_serial = null;

    var $_strip_depth = 0;
    var $_additional_newline = "\n";
    
    /**
     * +----------------------------------------------------------
     * 构造函数
     * +----------------------------------------------------------
     */
    function __construct() {
        $this->assign('SCRIPT_NAME', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : @$GLOBALS['HTTP_SERVER_VARS']['SCRIPT_NAME']);
        
        /* Smarty_Compiler ---------------------------------------------------------- */
        $this->_db_qstr_regexp = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"'; // 匹配双引号字符串
        $this->_si_qstr_regexp = '\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\''; // 匹配单引用字符串
        $this->_qstr_regexp = '(?:' . $this->_db_qstr_regexp . '|' . $this->_si_qstr_regexp . ')'; // 匹配双引号或单引用字符串
        $this->_var_bracket_regexp = '\[\$?[\w\.]+\]'; // 匹配变量中的括号部分
        $this->_num_const_regexp = '(?:\-?\d+(?:\.\d+)?)'; // 匹配数字常量

        // 匹配变量 (not objects):
        $this->_dvar_math_regexp = '(?:[\+\*\/\%]|(?:-(?!>)))';
        $this->_dvar_math_var_regexp = '[\$\w\.\+\-\*\/\%\d\>\[\]]';
        $this->_dvar_guts_regexp = '\w+(?:' . $this->_var_bracket_regexp
                . ')*(?:\.\$?\w+(?:' . $this->_var_bracket_regexp . ')*)*(?:' . $this->_dvar_math_regexp . '(?:' . $this->_num_const_regexp . '|' . $this->_dvar_math_var_regexp . ')*)?';
        $this->_dvar_regexp = '\$' . $this->_dvar_guts_regexp;

        $this->_cvar_regexp = '\#\w+\#'; // 匹配预配置变量
        $this->_svar_regexp = '\%\w+\.\w+\%'; // 匹配section变量
        $this->_avar_regexp = '(?:' . $this->_dvar_regexp . '|' . $this->_cvar_regexp . '|' . $this->_svar_regexp . ')'; // 匹配所有有效变量（没有引号，没有修饰符）
        $this->_var_regexp = '(?:' . $this->_avar_regexp . '|' . $this->_qstr_regexp . ')'; // 匹配有效变量语法

        // 匹配有效对象调用（参数中允许的一个对象嵌套级别）
        $this->_obj_ext_regexp = '\->(?:\$?' . $this->_dvar_guts_regexp . ')';
        $this->_obj_restricted_param_regexp = '(?:'
                . '(?:' . $this->_var_regexp . '|' . $this->_num_const_regexp . ')(?:' . $this->_obj_ext_regexp . '(?:\((?:(?:' . $this->_var_regexp . '|' . $this->_num_const_regexp . ')'
                . '(?:\s*,\s*(?:' . $this->_var_regexp . '|' . $this->_num_const_regexp . '))*)?\))?)*)';
        $this->_obj_single_param_regexp = '(?:\w+|' . $this->_obj_restricted_param_regexp . '(?:\s*,\s*(?:(?:\w+|' . $this->_var_regexp . $this->_obj_restricted_param_regexp . ')))*)';
        $this->_obj_params_regexp = '\((?:' . $this->_obj_single_param_regexp . '(?:\s*,\s*' . $this->_obj_single_param_regexp . ')*)?\)';
        $this->_obj_start_regexp = '(?:' . $this->_dvar_regexp . '(?:' . $this->_obj_ext_regexp . ')+)';
        $this->_obj_call_regexp = '(?:' . $this->_obj_start_regexp . '(?:' . $this->_obj_params_regexp . ')?(?:' . $this->_dvar_math_regexp . '(?:' . $this->_num_const_regexp . '|' . $this->_dvar_math_var_regexp . ')*)?)';
        
        // 匹配有效修饰符语法:
        $this->_mod_regexp = '(?:\|@?\w+(?::(?:\w+|' . $this->_num_const_regexp . '|' . $this->_obj_call_regexp . '|' . $this->_avar_regexp . '|' . $this->_qstr_regexp .'))*)';

        // 匹配有效函数名
        $this->_func_regexp = '[a-zA-Z_]\w*';

        // 匹配有效参数值
        $this->_param_regexp = '(?:\s*(?:' . $this->_obj_call_regexp . '|' . $this->_var_regexp . '|' . $this->_num_const_regexp  . '|\w+)(?>' . $this->_mod_regexp . '*)\s*)';

        // 匹配有效括号函数参数:
        $this->_parenth_param_regexp = '(?:\((?:\w+|' . $this->_param_regexp . '(?:\s*,\s*(?:(?:\w+|' . $this->_param_regexp . ')))*)?\))';

        // 匹配有效函数调用:
        $this->_func_call_regexp = '(?:' . $this->_func_regexp . '\s*(?:' . $this->_parenth_param_regexp . '))';
    }
 
    function Smarty() {
        $this->__construct();
    }
 
    /**
     * +----------------------------------------------------------
     * 注册变量
     * +----------------------------------------------------------
     */
    function assign($tpl_var, $value = null) {
        if (is_array($tpl_var)){
            foreach ($tpl_var as $key => $val) {
                if ($key != '') $this->_tpl_vars[$key] = $val;
            }
        } else {
            if ($tpl_var != '') $this->_tpl_vars[$tpl_var] = $value;
        }
    }

    /**
     * +----------------------------------------------------------
     * 触发Smarty错误
     * +----------------------------------------------------------
     */
    function trigger_error($error_msg, $error_type = E_USER_WARNING) {
        trigger_error("Smarty error: $error_msg", $error_type);
    }


    /**
     * +----------------------------------------------------------
     * 页面显示
     * +----------------------------------------------------------
     * $resource_name 模板文件名
     * $cache_id 自定义缓存名称
     * $compile_id 自定义编译名称
     * +----------------------------------------------------------
     */
    function display($resource_name, $cache_id = null, $compile_id = null) {
        $this->fetch($resource_name, $cache_id, $compile_id, true);
    }


    /**
     * +----------------------------------------------------------
     * 模板文件处理
     * +----------------------------------------------------------
     * $display 是否显示处理后的源码
     * +----------------------------------------------------------
     */
    function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false) {
        static $_cache_info = array();

        $_smarty_old_error_level = error_reporting(isset($this->error_reporting)
               ? $this->error_reporting : error_reporting() & ~E_NOTICE);

        if (!isset($compile_id)) {
            $compile_id = $this->compile_id;
        }

        $this->_compile_id = $compile_id;
        $this->_inclusion_depth = 0;

        if ($this->caching) {
            // 将初始化的$this->_cache_info插入到$_cache_info中
            array_push($_cache_info, $this->_cache_info);
            $this->_cache_info = array();
            $_params = array(
                'tpl_file' => $resource_name,
                'cache_id' => $cache_id,
                'compile_id' => $compile_id,
                'results' => null
            );
            if ($this->smarty_core_read_cache_file($_params, $this)) {
                $_smarty_results = $_params['results'];
                if (!empty($this->_cache_info['cache_serials'])) {
                    $_params = array('results' => $_smarty_results);
                    $_smarty_results = $this->smarty_core_process_compiled_include($_params, $this);
                }

                if ($display) {
                    if ($this->cache_modified_check) {
                        $_server_vars = ($this->request_use_auto_globals) ? $_SERVER : $GLOBALS['HTTP_SERVER_VARS'];
                        $_last_modified_date = @substr($_server_vars['HTTP_IF_MODIFIED_SINCE'], 0, strpos($_server_vars['HTTP_IF_MODIFIED_SINCE'], 'GMT') + 3);
                        $_gmt_mtime = gmdate('D, d M Y H:i:s', $this->_cache_info['timestamp']).' GMT';
                        header('Last-Modified: '.$_gmt_mtime);
                        echo $_smarty_results;
                    } else {
                            echo $_smarty_results;
                    }
                    error_reporting($_smarty_old_error_level);
                    // restore initial cache_info
                    $this->_cache_info = array_pop($_cache_info);
                    return true;
                } else {
                    error_reporting($_smarty_old_error_level);
                    // restore initial cache_info
                    $this->_cache_info = array_pop($_cache_info);
                    return $_smarty_results;
                }
            } else {
                $this->_cache_info['template'][$resource_name] = true;
                if ($this->cache_modified_check && $display) {
                    header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
                }
            }
        }
        
        $_smarty_compile_path = $this->_get_compile_path($resource_name); // 编译文件的完整文件地址

        // if we just need to display the results, don't perform output
        // buffering - for speed
        $_cache_including = $this->_cache_including;
        $this->_cache_including = false;
        if ($display && !$this->caching) {
            if ($this->_is_compiled($resource_name, $_smarty_compile_path) || $this->_compile_resource($resource_name, $_smarty_compile_path)) {
                include($_smarty_compile_path);
            }
        } else {
            ob_start();
            if ($this->_is_compiled($resource_name, $_smarty_compile_path) || $this->_compile_resource($resource_name, $_smarty_compile_path)) {
                include($_smarty_compile_path);
            }
            $_smarty_results = ob_get_contents();
            ob_end_clean();
        }

        if ($this->caching) {
            $_params = array('tpl_file' => $resource_name,
                        'cache_id' => $cache_id,
                        'compile_id' => $compile_id,
                        'results' => $_smarty_results);
            $this->smarty_core_write_cache_file($_params, $this);

            if ($this->_cache_serials) {
                // strip nocache-tags from output
                $_smarty_results = preg_replace('!(\{/?nocache\:[0-9a-f]{32}#\d+\})!s', '', $_smarty_results);
            }
            // restore initial cache_info
            $this->_cache_info = array_pop($_cache_info);
        }
        $this->_cache_including = $_cache_including;

        if ($display) {
            if (isset($_smarty_results)) { echo $_smarty_results; }
            error_reporting($_smarty_old_error_level);
            return;
        } else {
            error_reporting($_smarty_old_error_level);
            if (isset($_smarty_results)) { return $_smarty_results; }
        }
    }

    /**
     * +----------------------------------------------------------
     * 判断页面是否已编译
     * +----------------------------------------------------------
     */
    function _is_compiled($resource_name, $compile_path) {
        if (!$this->force_compile && file_exists($compile_path)) {
            if (!$this->compile_check) {
                // no need to check compiled file
                return true;
            } else {
                // get file source and timestamp
                $_params = array('resource_name' => $resource_name, 'get_source'=>false);
                if (!$this->_fetch_resource_info($_params)) {
                    return false;
                }
                if ($_params['resource_timestamp'] <= filemtime($compile_path)) {
                    // template not expired, no recompile
                    return true;
                } else {
                    // compile template
                    return false;
                }
            }
        } else {
            // compiled template does not exist, or forced compile
            return false;
        }
    }

    /**
     * +----------------------------------------------------------
     * 编译模板
     * +----------------------------------------------------------
     */
    function _compile_resource($resource_name, $compile_path) {
        $_params = array('resource_name' => $resource_name);
        if (!$this->_fetch_resource_info($_params)) {
            return false;
        }

        $_source_content = $_params['source_content'];
        $_cache_include    = substr($compile_path, 0, -4).'.inc';

        if ($this->_compile_source($resource_name, $_source_content, $_compiled_content, $_cache_include)) {
            // if a _cache_serial was set, we also have to write an include-file:
            if ($this->_cache_include_info) {
                $this->smarty_core_write_compiled_include(array_merge($this->_cache_include_info, array('compiled_content'=>$_compiled_content, 'resource_name'=>$resource_name)),  $this);
            }

            $_params = array('compile_path'=>$compile_path, 'compiled_content' => $_compiled_content);
            $this->smarty_core_write_compiled_resource($_params, $this);

            return true;
        } else {
            return false;
        }

    }

    /**
     * +----------------------------------------------------------
     * 编译指定的源码
     * +----------------------------------------------------------
     */
    function _compile_source($resource_name, &$source_content, &$compiled_content, $cache_include_path=null) {
        if (isset($cache_include_path) && isset($this->_cache_serials[$cache_include_path])) {
            $this->_cache_serial = $this->_cache_serials[$cache_include_path];
        }
        $this->_cache_include = $cache_include_path;


        $_results = $this->_compile_file($resource_name, $source_content, $compiled_content);

        if ($this->_cache_serial) {
            $this->_cache_include_info = array(
                'cache_serial'=>$this->_cache_serial
                ,'include_file_path' => $cache_include_path);

        } else {
            $this->_cache_include_info = null;

        }

        return $_results;
    }

    /**
     * 获取编译文件的完整文件地址
     *
     * @param string $resource_name
     * @return string results of {@link _get_auto_filename()}
     */
    function _get_compile_path($resource_name) {
        return $this->_get_auto_filename($this->compile_dir, $resource_name, $this->_compile_id) . '.php';
    }

    /**
     * fetch the template info. Gets timestamp, and source
     * if get_source is true
     *
     * sets $source_content to the source of the template, and
     * $resource_timestamp to its time stamp
     * @param string $resource_name
     * @param string $source_content
     * @param integer $resource_timestamp
     * @param boolean $get_source
     * @param boolean $quiet
     * @return boolean
     */

    function _fetch_resource_info(&$params) {
        if(!isset($params['get_source'])) { $params['get_source'] = true; }
        if(!isset($params['quiet'])) { $params['quiet'] = false; }

        $_return = false;
        $_params = array('resource_name' => $params['resource_name']) ;

        if ($this->_parse_resource_name($_params)) {
            $_resource_name = $_params['resource_name'];
            if ($params['get_source']) {
                $params['source_content'] = $this->_read_file($_resource_name);
            }
            $params['resource_timestamp'] = filemtime($_resource_name);
            $_return = is_file($_resource_name) && is_readable($_resource_name);
        }

        if (!$_return) {
            if (!$params['quiet']) {
                $this->trigger_error('unable to read resource: "' . $params['resource_name'] . '"');
            }
        } else if ($_return) {
            if (!$this->smarty_core_is_secure($_params)) {
                if (!$params['quiet'])
                    $this->trigger_error('(secure mode) accessing "' . $params['resource_name'] . '" is not allowed');
                $params['source_content'] = null;
                $params['resource_timestamp'] = null;
                return false;
            }
        }
        return $_return;
    }

    /**
     * 验证根据$smarty->display获取的文件名是否合法
     *
     * @param string $resource_name
     * @param string $resource_name
     * @return boolean
     */

    function _parse_resource_name(&$params) {
        if (!preg_match('/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/', $params['resource_name'])) {
            // relative pathname to template_dir
            // use the first directory where the file is found
            foreach ((array)$this->template_dir as $_curr_path) { // (array)的作用是如果是一个值而不是数组时强制转为数组
                $_fullpath = $_curr_path . DIRECTORY_SEPARATOR . $params['resource_name'];
                if (file_exists($_fullpath) && is_file($_fullpath)) {
                    $params['resource_name'] = $_fullpath;
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Remove starting and ending quotes from the string
     *
     * @param string $string
     * @return string
     */
    function _dequote($string) {
        if ((substr($string, 0, 1) == "'" || substr($string, 0, 1) == '"') &&
            substr($string, -1) == substr($string, 0, 1))
            return substr($string, 1, -1);
        else
            return $string;
    }


    /**
     * read in a file
     *
     * @param string $filename
     * @return string
     */
    function _read_file($filename) {
        if ( file_exists($filename) && is_readable($filename) && ($fd = @fopen($filename, 'rb')) ) {
            $contents = '';
            while (!feof($fd)) {
                $contents .= fread($fd, 8192);
            }
            fclose($fd);
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * get a concrete filename for automagically created content
     *
     * @param string $auto_base
     * @param string $auto_source
     * @param string $auto_id
     * @return string
     * @staticvar string|null
     * @staticvar string|null
     */
    function _get_auto_filename($auto_base, $auto_source = null, $auto_id = null) {
        $_compile_dir_sep =  $this->use_sub_dirs ? DIRECTORY_SEPARATOR : '^';
        $_return = $auto_base . DIRECTORY_SEPARATOR;

        if(isset($auto_id)) {
            // make auto_id safe for directory names
            $auto_id = str_replace('%7C',$_compile_dir_sep,(urlencode($auto_id)));
            // split into separate directories
            $_return .= $auto_id . $_compile_dir_sep;
        }

        if(isset($auto_source)) {
            // make source name safe for filename
            $_filename = urlencode(basename($auto_source));
            $_crc32 = sprintf('%08X', crc32($auto_source));
            // prepend %% to avoid name conflicts with
            // with $params['auto_id'] names
            $_crc32 = substr($_crc32, 0, 2) . $_compile_dir_sep .
                      substr($_crc32, 0, 3) . $_compile_dir_sep . $_crc32;
            $_return .= $_filename;
        }

        return $_return;
    }

    /**
     * returns an auto_id for auto-file-functions
     *
     * @param string $cache_id
     * @param string $compile_id
     * @return string|null
     */
    function _get_auto_id($cache_id=null, $compile_id=null) {
        if (isset($cache_id)) {
            return (isset($compile_id)) ? $cache_id . '|' . $compile_id  : $cache_id;
        } elseif (isset($compile_id)) {
            return $compile_id;
        } else {
            return null;
        }
    }

    /**
     * trigger Smarty plugin error
     *
     * @param string $error_msg
     * @param string $tpl_file
     * @param integer $tpl_line
     * @param string $file
     * @param integer $line
     * @param integer $error_type
     */
    function _trigger_fatal_error($error_msg, $tpl_file = null, $tpl_line = null, $file = null, $line = null, $error_type = E_USER_ERROR) {
        if(isset($file) && isset($line)) {
            $info = ' ('.basename($file).", line $line)";
        } else {
            $info = '';
        }
        if (isset($tpl_line) && isset($tpl_file)) {
            $this->trigger_error('[in ' . $tpl_file . ' line ' . $tpl_line . "]: $error_msg$info", $error_type);
        } else {
            $this->trigger_error($error_msg . $info, $error_type);
        }
    }


    /**
     * callback function for preg_replace, to call a non-cacheable block
     * @return string
     */
    function _process_compiled_include_callback($match) {
        $_func = '_smarty_tplfunc_'.$match[2].'_'.$match[3];
        ob_start();
        $_func($this);
        $_ret = ob_get_contents();
        ob_end_clean();
        return $_ret;
    }


    /**
     * called for included templates
     *
     * @param string $_smarty_include_tpl_file
     * @param string $_smarty_include_vars
     */

    // $_smarty_include_tpl_file, $_smarty_include_vars

    function _smarty_include($params) {
        $this->_tpl_vars = array_merge($this->_tpl_vars, $params['smarty_include_vars']);

        // config vars are treated as local, so push a copy of the
        // current ones onto the front of the stack
        array_unshift($this->_config, $this->_config[0]);

        $_smarty_compile_path = $this->_get_compile_path($params['smarty_include_tpl_file']);


        if ($this->_is_compiled($params['smarty_include_tpl_file'], $_smarty_compile_path)
            || $this->_compile_resource($params['smarty_include_tpl_file'], $_smarty_compile_path))
        {
            include($_smarty_compile_path);
        }

        // pop the local vars off the front of the stack
        array_shift($this->_config);

        $this->_inclusion_depth--;

        if ($this->caching) {
            $this->_cache_info['template'][$params['smarty_include_tpl_file']] = true;
        }
    }
 
    /**
     * 注册前置过滤器
     */
    function register_prefilter($function) {
        $this->_prefilter = $function;
    }
 
    /**
     * --------------------------------------------------------------------------------------------------
     * 编译程序 Smarty Compiler
     * --------------------------------------------------------------------------------------------------
    /**
    /**
     * +----------------------------------------------------------
     * 编译模板文件
     * +----------------------------------------------------------
     * $resource_name 模板文件名
     * $source_content 源内容
     * $compiled_content 编译后的内容
     * +----------------------------------------------------------
     */
    function _compile_file($resource_name, $source_content, &$compiled_content) {
        $this->_current_file = $resource_name;
        $this->_current_line_no = 1;
        $ldq = preg_quote($this->left_delimiter, '~');
        $rdq = preg_quote($this->right_delimiter, '~');

        // 前置过滤器.编译前
        if ($this->_prefilter) {
            if (is_callable($this->_prefilter)) { // 检查方法是否存在
                $source_content = call_user_func_array($this->_prefilter, array($source_content, &$this));
            } else {
                $this->_trigger_fatal_error("prefilter '" . $this->_prefilter . "' is not implemented");
            }
        }

        /* fetch all special blocks */
        $search = "~{$ldq}\*(.*?)\*{$rdq}|{$ldq}\s*literal\s*{$rdq}(.*?){$ldq}\s*/literal\s*{$rdq}|{$ldq}\s*php\s*{$rdq}(.*?){$ldq}\s*/php\s*{$rdq}~s";

        preg_match_all($search, $source_content, $match,  PREG_SET_ORDER);
        $this->_folded_blocks = $match;
        reset($this->_folded_blocks);

        /* 将特殊标签用 "{php}" 替代，最终以<?php echo ''?>这样的形式显示在编译文件中比如literal标签 */
        /* douco modify for DouPHP 修改原因：php5.5以后的preg_replace不再支持e模式修饰符 */
        $source_content = preg_replace_callback($search, array("self", "callback_source"), $source_content);

        /* Gather all template tags. */
        preg_match_all("~{$ldq}\s*(.*?)\s*{$rdq}~s", $source_content, $_match);
        $template_tags = $_match[1];
        /* Split content by template tags to obtain non-template content. */
        $text_blocks = preg_split("~{$ldq}.*?{$rdq}~s", $source_content);

        /* loop through text blocks */
        for ($curr_tb = 0, $for_max = count($text_blocks); $curr_tb < $for_max; $curr_tb++) {
            /* match anything resembling php tags */
            if (preg_match_all('~(<\?(?:\w+|=)?|\?>|language\s*=\s*[\"\']?\s*php\s*[\"\']?)~is', $text_blocks[$curr_tb], $sp_match)) {
                /* replace tags with placeholders to prevent recursive replacements */
                $sp_match[1] = array_unique($sp_match[1]);
                usort($sp_match[1], '_smarty_sort_length');
                for ($curr_sp = 0, $for_max2 = count($sp_match[1]); $curr_sp < $for_max2; $curr_sp++) {
                    $text_blocks[$curr_tb] = str_replace($sp_match[1][$curr_sp],'%%%SMARTYSP'.$curr_sp.'%%%',$text_blocks[$curr_tb]);
                }
                /* process each one */
                for ($curr_sp = 0, $for_max2 = count($sp_match[1]); $curr_sp < $for_max2; $curr_sp++) {
                    $text_blocks[$curr_tb] = str_replace('%%%SMARTYSP'.$curr_sp.'%%%', '<?php echo \''.str_replace("'", "\'", $sp_match[1][$curr_sp]).'\'; ?>'."\n", $text_blocks[$curr_tb]);
                }
            }
        }
        
        /* Compile the template tags into PHP code. */
        $compiled_tags = array();
        for ($i = 0, $for_max = count($template_tags); $i < $for_max; $i++) {
            $this->_current_line_no += substr_count($text_blocks[$i], "\n");
            $compiled_tags[] = $this->_compile_tag($template_tags[$i]);
            $this->_current_line_no += substr_count($template_tags[$i], "\n");
        }
        if (count($this->_tag_stack)>0) {
            list($_open_tag, $_line_no) = end($this->_tag_stack);
            $this->_syntax_error("unclosed tag \{$_open_tag} (opened line $_line_no).", E_USER_ERROR, __FILE__, __LINE__);
            return;
        }

        /* Reformat $text_blocks between 'strip' and '/strip' tags,
           removing spaces, tabs and newlines. */
        $strip = false;
        for ($i = 0, $for_max = count($compiled_tags); $i < $for_max; $i++) {
            if ($compiled_tags[$i] == '{strip}') {
                $compiled_tags[$i] = '';
                $strip = true;
                /* remove leading whitespaces */
                $text_blocks[$i + 1] = ltrim($text_blocks[$i + 1]);
            }
            if ($strip) {
                /* strip all $text_blocks before the next '/strip' */
                for ($j = $i + 1; $j < $for_max; $j++) {
                    /* remove leading and trailing whitespaces of each line */
                    $text_blocks[$j] = preg_replace('![\t ]*[\r\n]+[\t ]*!', '', $text_blocks[$j]);
                    if ($compiled_tags[$j] == '{/strip}') {                       
                        /* remove trailing whitespaces from the last text_block */
                        $text_blocks[$j] = rtrim($text_blocks[$j]);
                    }
                    $text_blocks[$j] = "<?php echo '" . strtr($text_blocks[$j], array("'"=>"\'", "\\"=>"\\\\")) . "'; ?>";
                    if ($compiled_tags[$j] == '{/strip}') {
                        $compiled_tags[$j] = "\n"; /* slurped by php, but necessary
                                    if a newline is following the closing strip-tag */
                        $strip = false;
                        $i = $j;
                        break;
                    }
                }
            }
        }
        $compiled_content = '';
        
        $tag_guard = '%%%SMARTYOTG' . md5(uniqid(rand(), true)) . '%%%';
        
        /* Interleave the compiled contents and text blocks to get the final result. */
        for ($i = 0, $for_max = count($compiled_tags); $i < $for_max; $i++) {
            if ($compiled_tags[$i] == '') {
                // tag result empty, remove first newline from following text block
                $text_blocks[$i+1] = preg_replace('~^(\r\n|\r|\n)~', '', $text_blocks[$i+1]);
            }
            // replace legit PHP tags with placeholder
            $text_blocks[$i] = str_replace('<?', $tag_guard, $text_blocks[$i]);
            $compiled_tags[$i] = str_replace('<?', $tag_guard, $compiled_tags[$i]);
            
            $compiled_content .= $text_blocks[$i] . $compiled_tags[$i];
        }
        $compiled_content .= str_replace('<?', $tag_guard, $text_blocks[$i]);

        // escape php tags created by interleaving
        $compiled_content = str_replace('<?', "<?php echo '<?' ?>\n", $compiled_content);
        $compiled_content = preg_replace("~(?<!')language\s*=\s*[\"\']?\s*php\s*[\"\']?~", "<?php echo 'language=php' ?>\n", $compiled_content);

        // recover legit tags
        $compiled_content = str_replace($tag_guard, '<?', $compiled_content); 
        
        // remove \n from the end of the file, if any
        if (strlen($compiled_content) && (substr($compiled_content, -1) == "\n") ) {
            $compiled_content = substr($compiled_content, 0, -1);
        }

        if (!empty($this->_cache_serial)) {
            $compiled_content = "<?php \$this->_cache_serials['".$this->_cache_include."'] = '".$this->_cache_serial."'; ?>" . $compiled_content;
        }

        // put header at the top of the compiled template
        $template_header = "<?php /* Smarty version ".$this->_version.", created on ".strftime("%Y-%m-%d %H:%M:%S")."\n";
        $template_header .= "         compiled from ".strtr(urlencode($resource_name), array('%2F'=>'/', '%3A'=>':'))." */ ?>\n";

        $compiled_content = $template_header . $compiled_content;
        return true;
    }

    /**
     * +----------------------------------------------------------
     * 编译Smarty模板标签
     * +----------------------------------------------------------
     * $template_tag Smarty模板标签
     * +----------------------------------------------------------
     */
    function _compile_tag($template_tag) {
        /* Matched comment. */
        if (substr($template_tag, 0, 1) == '*' && substr($template_tag, -1) == '*')
            return '';
        
        /* Split tag into two three parts: command, command modifiers and the arguments. */
        if(! preg_match('~^(?:(' . $this->_num_const_regexp . '|' . $this->_obj_call_regexp . '|' . $this->_var_regexp
                . '|\/?' . $this->_func_regexp . ')(' . $this->_mod_regexp . '*))
                      (?:\s+(.*))?$
                    ~xs', $template_tag, $match)) {
            $this->_syntax_error("unrecognized tag: $template_tag", E_USER_ERROR, __FILE__, __LINE__);
        }
        
        $tag_command = $match[1];
        $tag_modifier = isset($match[2]) ? $match[2] : null; // 修饰器名称（如：truncate）
        $tag_args = isset($match[3]) ? $match[3] : null;

        if (preg_match('~^' . $this->_num_const_regexp . '|' . $this->_obj_call_regexp . '|' . $this->_var_regexp . '$~', $tag_command)) {
            /* tag name is a variable or object */
            $_return = $this->_parse_var_props($tag_command . $tag_modifier);
            return "<?php echo $_return; ?>" . $this->_additional_newline;
        }

        switch ($tag_command) {
            case 'include':
                return $this->_compile_include_tag($tag_args);

            case 'if':
                $this->_push_tag('if');
                return $this->_compile_if_tag($tag_args);

            case 'else':
                list($_open_tag) = end($this->_tag_stack);
                if ($_open_tag != 'if' && $_open_tag != 'elseif')
                    $this->_syntax_error('unexpected {else}', E_USER_ERROR, __FILE__, __LINE__);
                else
                    $this->_push_tag('else');
                return '<?php else: ?>';

            case 'elseif':
                list($_open_tag) = end($this->_tag_stack);
                if ($_open_tag != 'if' && $_open_tag != 'elseif')
                    $this->_syntax_error('unexpected {elseif}', E_USER_ERROR, __FILE__, __LINE__);
                if ($_open_tag == 'if')
                    $this->_push_tag('elseif');
                return $this->_compile_if_tag($tag_args, true);

            case '/if':
                $this->_pop_tag('if');
                return '<?php endif; ?>';

            case 'ldelim':
                return $this->left_delimiter;

            case 'rdelim':
                return $this->right_delimiter;

            case 'section':
                $this->_push_tag('section');
                return $this->_compile_section_start($tag_args);

            case 'sectionelse':
                $this->_push_tag('sectionelse');
                return "<?php endfor; else: ?>";
                break;

            case '/section':
                $_open_tag = $this->_pop_tag('section');
                if ($_open_tag == 'sectionelse')
                    return "<?php endif; ?>";
                else
                    return "<?php endfor; endif; ?>";

            case 'foreach':
                $this->_push_tag('foreach');
                return $this->_compile_foreach_start($tag_args);
                break;

            case 'foreachelse':
                $this->_push_tag('foreachelse');
                return "<?php endforeach; else: ?>";

            case '/foreach':
                $_open_tag = $this->_pop_tag('foreach');
                if ($_open_tag == 'foreachelse')
                    return "<?php endif; unset(\$_from); ?>";
                else
                    return "<?php endforeach; endif; unset(\$_from); ?>";
                break;

            case 'strip':
            case '/strip':
                if (substr($tag_command, 0, 1)=='/') {
                    $this->_pop_tag('strip');
                    if (--$this->_strip_depth==0) { /* outermost closing {/strip} */
                        $this->_additional_newline = "\n";
                        return '{' . $tag_command . '}';
                    }
                } else {
                    $this->_push_tag('strip');
                    if ($this->_strip_depth++==0) { /* outermost opening {strip} */
                        $this->_additional_newline = "";
                        return '{' . $tag_command . '}';
                    }
                }
                return '';
            
            case 'php':
                /* 替换闭合标签 by {php} */
                list(, $block) = $this->_each($this->_folded_blocks);
                $this->_current_line_no += substr_count($block[0], "\n");
                /* the number of matched elements in the regexp in _compile_file()
                   determins the type of folded tag that was found */
                switch (count($block)) {
                    case 2: /* comment */
                        return '';

                    case 3: /* literal */
                        return "<?php echo '" . strtr($block[2], array("'"=>"\'", "\\"=>"\\\\")) . "'; ?>" . $this->_additional_newline;

                    case 4: /* php */
                        $this->_syntax_error("(secure mode) php tags not permitted", E_USER_WARNING, __FILE__, __LINE__);
                        return;
                }
                break;

            default:
                $this->_syntax_error("unrecognized tag '$tag_command'", E_USER_ERROR, __FILE__, __LINE__);

        }
    }

    /**
     * Compile {include ...} tag
     *
     * @param string $tag_args
     * @return string
     */
    function _compile_include_tag($tag_args) {
        $attrs = $this->_parse_attrs($tag_args);
        $arg_list = array();

        if (empty($attrs['file'])) {
            $this->_syntax_error("missing 'file' attribute in include tag", E_USER_ERROR, __FILE__, __LINE__);
        }

        foreach ($attrs as $arg_name => $arg_value) {
            if ($arg_name == 'file') {
                $include_file = $arg_value;
                continue;
            } else if ($arg_name == 'assign') {
                $assign_var = $arg_value;
                continue;
            }
            if (is_bool($arg_value))
                $arg_value = $arg_value ? 'true' : 'false';
            $arg_list[] = "'$arg_name' => $arg_value";
        }

        $output = '<?php ';

        if (isset($assign_var)) {
            $output .= "ob_start();\n";
        }

        $output .=
            "\$_smarty_tpl_vars = \$this->_tpl_vars;\n";


        $_params = "array('smarty_include_tpl_file' => " . $include_file . ", 'smarty_include_vars' => array(".implode(',', (array)$arg_list)."))";
        $output .= "\$this->_smarty_include($_params);\n" .
        "\$this->_tpl_vars = \$_smarty_tpl_vars;\n" .
        "unset(\$_smarty_tpl_vars);\n";

        if (isset($assign_var)) {
            $output .= "\$this->assign(" . $assign_var . ", ob_get_contents()); ob_end_clean();\n";
        }

        $output .= ' ?>';

        return $output;

    }

    /**
     * Compile {section ...} tag
     *
     * @param string $tag_args
     * @return string
     */
    function _compile_section_start($tag_args) {
        $attrs = $this->_parse_attrs($tag_args);
        $arg_list = array();

        $output = '<?php ';
        $section_name = $attrs['name'];
        if (empty($section_name)) {
            $this->_syntax_error("missing section name", E_USER_ERROR, __FILE__, __LINE__);
        }

        $output .= "unset(\$this->_sections[$section_name]);\n";
        $section_props = "\$this->_sections[$section_name]";

        foreach ($attrs as $attr_name => $attr_value) {
            switch ($attr_name) {
                case 'loop':
                    $output .= "{$section_props}['loop'] = is_array(\$_loop=$attr_value) ? count(\$_loop) : max(0, (int)\$_loop); unset(\$_loop);\n";
                    break;

                case 'show':
                    if (is_bool($attr_value))
                        $show_attr_value = $attr_value ? 'true' : 'false';
                    else
                        $show_attr_value = "(bool)$attr_value";
                    $output .= "{$section_props}['show'] = $show_attr_value;\n";
                    break;

                case 'name':
                    $output .= "{$section_props}['$attr_name'] = $attr_value;\n";
                    break;

                case 'max':
                case 'start':
                    $output .= "{$section_props}['$attr_name'] = (int)$attr_value;\n";
                    break;

                case 'step':
                    $output .= "{$section_props}['$attr_name'] = ((int)$attr_value) == 0 ? 1 : (int)$attr_value;\n";
                    break;

                default:
                    $this->_syntax_error("unknown section attribute - '$attr_name'", E_USER_ERROR, __FILE__, __LINE__);
                    break;
            }
        }

        if (!isset($attrs['show']))
            $output .= "{$section_props}['show'] = true;\n";

        if (!isset($attrs['loop']))
            $output .= "{$section_props}['loop'] = 1;\n";

        if (!isset($attrs['max']))
            $output .= "{$section_props}['max'] = {$section_props}['loop'];\n";
        else
            $output .= "if ({$section_props}['max'] < 0)\n" .
                       "    {$section_props}['max'] = {$section_props}['loop'];\n";

        if (!isset($attrs['step']))
            $output .= "{$section_props}['step'] = 1;\n";

        if (!isset($attrs['start']))
            $output .= "{$section_props}['start'] = {$section_props}['step'] > 0 ? 0 : {$section_props}['loop']-1;\n";
        else {
            $output .= "if ({$section_props}['start'] < 0)\n" .
                       "    {$section_props}['start'] = max({$section_props}['step'] > 0 ? 0 : -1, {$section_props}['loop'] + {$section_props}['start']);\n" .
                       "else\n" .
                       "    {$section_props}['start'] = min({$section_props}['start'], {$section_props}['step'] > 0 ? {$section_props}['loop'] : {$section_props}['loop']-1);\n";
        }

        $output .= "if ({$section_props}['show']) {\n";
        if (!isset($attrs['start']) && !isset($attrs['step']) && !isset($attrs['max'])) {
            $output .= "    {$section_props}['total'] = {$section_props}['loop'];\n";
        } else {
            $output .= "    {$section_props}['total'] = min(ceil(({$section_props}['step'] > 0 ? {$section_props}['loop'] - {$section_props}['start'] : {$section_props}['start']+1)/abs({$section_props}['step'])), {$section_props}['max']);\n";
        }
        $output .= "    if ({$section_props}['total'] == 0)\n" .
                   "        {$section_props}['show'] = false;\n" .
                   "} else\n" .
                   "    {$section_props}['total'] = 0;\n";

        $output .= "if ({$section_props}['show']):\n";
        $output .= "
            for ({$section_props}['index'] = {$section_props}['start'], {$section_props}['iteration'] = 1;
                 {$section_props}['iteration'] <= {$section_props}['total'];
                 {$section_props}['index'] += {$section_props}['step'], {$section_props}['iteration']++):\n";
        $output .= "{$section_props}['rownum'] = {$section_props}['iteration'];\n";
        $output .= "{$section_props}['index_prev'] = {$section_props}['index'] - {$section_props}['step'];\n";
        $output .= "{$section_props}['index_next'] = {$section_props}['index'] + {$section_props}['step'];\n";
        $output .= "{$section_props}['first']      = ({$section_props}['iteration'] == 1);\n";
        $output .= "{$section_props}['last']       = ({$section_props}['iteration'] == {$section_props}['total']);\n";

        $output .= "?>";

        return $output;
    }


    /**
     * Compile {foreach ...} tag.
     *
     * @param string $tag_args
     * @return string
     */
    function _compile_foreach_start($tag_args) {
        $attrs = $this->_parse_attrs($tag_args);
        $arg_list = array();

        if (empty($attrs['from'])) {
            return $this->_syntax_error("foreach: missing 'from' attribute", E_USER_ERROR, __FILE__, __LINE__);
        }
        $from = $attrs['from'];

        if (empty($attrs['item'])) {
            return $this->_syntax_error("foreach: missing 'item' attribute", E_USER_ERROR, __FILE__, __LINE__);
        }
        $item = $this->_dequote($attrs['item']);
        if (!preg_match('~^\w+$~', $item)) {
            return $this->_syntax_error("foreach: 'item' must be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
        }

        if (isset($attrs['key'])) {
            $key  = $this->_dequote($attrs['key']);
            if (!preg_match('~^\w+$~', $key)) {
                return $this->_syntax_error("foreach: 'key' must to be a variable name (literal string)", E_USER_ERROR, __FILE__, __LINE__);
            }
            $key_part = "\$this->_tpl_vars['$key'] => ";
        } else {
            $key = null;
            $key_part = '';
        }

        if (isset($attrs['name'])) {
            $name = $attrs['name'];
        } else {
            $name = null;
        }

        $output = '<?php ';
        $output .= "\$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array'); }";
        if (isset($name)) {
            $foreach_props = "\$this->_foreach[$name]";
            $output .= "{$foreach_props} = array('total' => count(\$_from), 'iteration' => 0);\n";
            $output .= "if ({$foreach_props}['total'] > 0):\n";
            $output .= "    foreach (\$_from as $key_part\$this->_tpl_vars['$item']):\n";
            $output .= "        {$foreach_props}['iteration']++;\n";
        } else {
            $output .= "if (count(\$_from)):\n";
            $output .= "    foreach (\$_from as $key_part\$this->_tpl_vars['$item']):\n";
        }
        $output .= '?>';

        return $output;
    }

    /**
     * Compile {if ...} tag
     *
     * @param string $tag_args
     * @param boolean $elseif if true, uses elseif instead of if
     * @return string
     */
    function _compile_if_tag($tag_args, $elseif = false) {

        /* Tokenize args for 'if' tag. */
        preg_match_all('~(?>
                ' . $this->_obj_call_regexp . '(?:' . $this->_mod_regexp . '*)? | # valid object call
                ' . $this->_var_regexp . '(?:' . $this->_mod_regexp . '*)?    | # var or quoted string
                \-?0[xX][0-9a-fA-F]+|\-?\d+(?:\.\d+)?|\.\d+|!==|===|==|!=|<>|<<|>>|<=|>=|\&\&|\|\||\(|\)|,|\!|\^|=|\&|\~|<|>|\||\%|\+|\-|\/|\*|\@    | # valid non-word token
                \b\w+\b                                                        | # valid word token
                \S+                                                           # anything else
                )~x', $tag_args, $match);

        $tokens = $match[0];

        if(empty($tokens)) {
            $_error_msg = $elseif ? "'elseif'" : "'if'";
            $_error_msg .= ' statement requires arguments'; 
            $this->_syntax_error($_error_msg, E_USER_ERROR, __FILE__, __LINE__);
        }
            
                
        // make sure we have balanced parenthesis
        $token_count = array_count_values($tokens);
        if(isset($token_count['(']) && $token_count['('] != $token_count[')']) {
            $this->_syntax_error("unbalanced parenthesis in if statement", E_USER_ERROR, __FILE__, __LINE__);
        }

        $is_arg_stack = array();

        for ($i = 0; $i < count($tokens); $i++) {

            $token = &$tokens[$i];

            switch (strtolower($token)) {
                case '!':
                case '%':
                case '!==':
                case '==':
                case '===':
                case '>':
                case '<':
                case '!=':
                case '<>':
                case '<<':
                case '>>':
                case '<=':
                case '>=':
                case '&&':
                case '||':
                case '|':
                case '^':
                case '&':
                case '~':
                case ')':
                case ',':
                case '+':
                case '-':
                case '*':
                case '/':
                case '@':
                    break;

                case 'eq':
                    $token = '==';
                    break;

                case 'ne':
                case 'neq':
                    $token = '!=';
                    break;

                case 'lt':
                    $token = '<';
                    break;

                case 'le':
                case 'lte':
                    $token = '<=';
                    break;

                case 'gt':
                    $token = '>';
                    break;

                case 'ge':
                case 'gte':
                    $token = '>=';
                    break;

                case 'and':
                    $token = '&&';
                    break;

                case 'or':
                    $token = '||';
                    break;

                case 'not':
                    $token = '!';
                    break;

                case 'mod':
                    $token = '%';
                    break;

                case '(':
                    array_push($is_arg_stack, $i);
                    break;

                case 'is':
                    /* If last token was a ')', we operate on the parenthesized
                       expression. The start of the expression is on the stack.
                       Otherwise, we operate on the last encountered token. */
                    if ($tokens[$i-1] == ')') {
                        $is_arg_start = array_pop($is_arg_stack);
                        if ($is_arg_start != 0) {
                            if (preg_match('~^' . $this->_func_regexp . '$~', $tokens[$is_arg_start-1])) {
                                $is_arg_start--;
                            } 
                        } 
                    } else
                        $is_arg_start = $i-1;
                    /* Construct the argument for 'is' expression, so it knows
                       what to operate on. */
                    $is_arg = implode(' ', array_slice($tokens, $is_arg_start, $i - $is_arg_start));

                    /* Pass all tokens from next one until the end to the
                       'is' expression parsing function. The function will
                       return modified tokens, where the first one is the result
                       of the 'is' expression and the rest are the tokens it
                       didn't touch. */
                    $new_tokens = $this->_parse_is_expr($is_arg, array_slice($tokens, $i+1));

                    /* Replace the old tokens with the new ones. */
                    array_splice($tokens, $is_arg_start, count($tokens), $new_tokens);

                    /* Adjust argument start so that it won't change from the
                       current position for the next iteration. */
                    $i = $is_arg_start;
                    break;

                default:
                    if(preg_match('~^' . $this->_func_regexp . '$~', $token) ) {
                            $this->_syntax_error("(secure mode) '$token' not allowed in if statement", E_USER_ERROR, __FILE__, __LINE__);
                    } elseif(preg_match('~^' . $this->_var_regexp . '$~', $token) && (strpos('+-*/^%&|', substr($token, -1)) === false) && isset($tokens[$i+1]) && $tokens[$i+1] == '(') {
                        // variable function call
                        $this->_syntax_error("variable function call '$token' not allowed in if statement", E_USER_ERROR, __FILE__, __LINE__);                      
                    } elseif(preg_match('~^' . $this->_obj_call_regexp . '|' . $this->_var_regexp . '(?:' . $this->_mod_regexp . '*)$~', $token)) {
                        // object or variable
                        $token = $this->_parse_var_props($token);
                    } elseif(is_numeric($token)) {
                        // number, skip it
                    } else {
                        $this->_syntax_error("unidentified token '$token'", E_USER_ERROR, __FILE__, __LINE__);
                    }
                    break;
            }
        }

        if ($elseif)
            return '<?php elseif ('.implode(' ', $tokens).'): ?>';
        else
            return '<?php if ('.implode(' ', $tokens).'): ?>';
    }

    /**
     * Parse is expression
     *
     * @param string $is_arg
     * @param array $tokens
     * @return array
     */
    function _parse_is_expr($is_arg, $tokens) {
        $expr_end = 0;
        $negate_expr = false;

        if (($first_token = array_shift($tokens)) == 'not') {
            $negate_expr = true;
            $expr_type = array_shift($tokens);
        } else
            $expr_type = $first_token;

        switch ($expr_type) {
            case 'even':
                if (isset($tokens[$expr_end]) && $tokens[$expr_end] == 'by') {
                    $expr_end++;
                    $expr_arg = $tokens[$expr_end++];
                    $expr = "!(1 & ($is_arg / " . $this->_parse_var_props($expr_arg) . "))";
                } else
                    $expr = "!(1 & $is_arg)";
                break;

            case 'odd':
                if (isset($tokens[$expr_end]) && $tokens[$expr_end] == 'by') {
                    $expr_end++;
                    $expr_arg = $tokens[$expr_end++];
                    $expr = "(1 & ($is_arg / " . $this->_parse_var_props($expr_arg) . "))";
                } else
                    $expr = "(1 & $is_arg)";
                break;

            case 'div':
                if (@$tokens[$expr_end] == 'by') {
                    $expr_end++;
                    $expr_arg = $tokens[$expr_end++];
                    $expr = "!($is_arg % " . $this->_parse_var_props($expr_arg) . ")";
                } else {
                    $this->_syntax_error("expecting 'by' after 'div'", E_USER_ERROR, __FILE__, __LINE__);
                }
                break;

            default:
                $this->_syntax_error("unknown 'is' expression - '$expr_type'", E_USER_ERROR, __FILE__, __LINE__);
                break;
        }

        if ($negate_expr) {
            $expr = "!($expr)";
        }

        array_splice($tokens, 0, $expr_end, $expr);

        return $tokens;
    }


    /**
     * Parse attribute string
     *
     * @param string $tag_args
     * @return array
     */
    function _parse_attrs($tag_args) {
        /* Tokenize tag attributes. */
        preg_match_all('~(?:' . $this->_obj_call_regexp . '|' . $this->_qstr_regexp . ' | (?>[^"\'=\s]+)
                         )+ |
                         [=]
                        ~x', $tag_args, $match);
        $tokens       = $match[0];

        $attrs = array();
        /* Parse state:
            0 - expecting attribute name
            1 - expecting '='
            2 - expecting attribute value (not '=') */
        $state = 0;

        foreach ($tokens as $token) {
            switch ($state) {
                case 0:
                    /* If the token is a valid identifier, we set attribute name
                       and go to state 1. */
                    if (preg_match('~^\w+$~', $token)) {
                        $attr_name = $token;
                        $state = 1;
                    } else
                        $this->_syntax_error("invalid attribute name: '$token'", E_USER_ERROR, __FILE__, __LINE__);
                    break;

                case 1:
                    /* If the token is '=', then we go to state 2. */
                    if ($token == '=') {
                        $state = 2;
                    } else
                        $this->_syntax_error("expecting '=' after attribute name '$last_token'", E_USER_ERROR, __FILE__, __LINE__);
                    break;

                case 2:
                    /* If token is not '=', we set the attribute value and go to
                       state 0. */
                    if ($token != '=') {
                        /* We booleanize the token if it's a non-quoted possible
                           boolean value. */
                        if (preg_match('~^(on|yes|true)$~', $token)) {
                            $token = 'true';
                        } else if (preg_match('~^(off|no|false)$~', $token)) {
                            $token = 'false';
                        } else if ($token == 'null') {
                            $token = 'null';
                        } else if (preg_match('~^' . $this->_num_const_regexp . '|0[xX][0-9a-fA-F]+$~', $token)) {
                            /* treat integer literally */
                        } else if (!preg_match('~^' . $this->_obj_call_regexp . '|' . $this->_var_regexp . '(?:' . $this->_mod_regexp . ')*$~', $token)) {
                            /* treat as a string, double-quote it escaping quotes */
                            $token = '"'.addslashes($token).'"';
                        }

                        $attrs[$attr_name] = $token;
                        $state = 0;
                    } else
                        $this->_syntax_error("'=' cannot be an attribute value", E_USER_ERROR, __FILE__, __LINE__);
                    break;
            }
            $last_token = $token;
        }

        if($state != 0) {
            if($state == 1) {
                $this->_syntax_error("expecting '=' after attribute name '$last_token'", E_USER_ERROR, __FILE__, __LINE__);
            } else {
                $this->_syntax_error("missing attribute value", E_USER_ERROR, __FILE__, __LINE__);
            }
        }

        $this->_parse_vars_props($attrs);

        return $attrs;
    }

    /**
     * compile multiple variables and section properties tokens into
     * PHP code
     *
     * @param array $tokens
     */
    function _parse_vars_props(&$tokens) {
        foreach($tokens as $key => $val) {
            $tokens[$key] = $this->_parse_var_props($val);
        }
    }

    /**
     * compile single variable and section properties token into
     * PHP code
     *
     * @param string $val
     * @param string $tag_attrs
     * @return string
     */
    function _parse_var_props($val) {
        $val = trim($val);

        if(preg_match('~^(' . $this->_obj_call_regexp . '|' . $this->_dvar_regexp . ')(' . $this->_mod_regexp . '*)$~', $val, $match)) {
            // $ variable or object
            $return = $this->_parse_var($match[1]);
            $modifiers = $match[2];
            if (!empty($this->default_modifiers) && !preg_match('~(^|\|)smarty:nodefaults($|\|)~',$modifiers)) {
                $_default_mod_string = implode('|',(array)$this->default_modifiers);
                $modifiers = empty($modifiers) ? $_default_mod_string : $_default_mod_string . '|' . $modifiers;
            }
            $this->_parse_modifiers($return, $modifiers);
            return $return;
        } elseif (preg_match('~^' . $this->_db_qstr_regexp . '(?:' . $this->_mod_regexp . '*)$~', $val)) {
                // double quoted text
                preg_match('~^(' . $this->_db_qstr_regexp . ')('. $this->_mod_regexp . '*)$~', $val, $match);
                $return = $this->_expand_quoted_text($match[1]);
                if($match[2] != '') {
                    $this->_parse_modifiers($return, $match[2]);
                }
                return $return;
            }
        elseif(preg_match('~^' . $this->_num_const_regexp . '(?:' . $this->_mod_regexp . '*)$~', $val)) {
                // numerical constant
                preg_match('~^(' . $this->_num_const_regexp . ')('. $this->_mod_regexp . '*)$~', $val, $match);
                if($match[2] != '') {
                    $this->_parse_modifiers($match[1], $match[2]);
                    return $match[1];
                }
            }
        elseif(preg_match('~^' . $this->_si_qstr_regexp . '(?:' . $this->_mod_regexp . '*)$~', $val)) {
                // single quoted text
                preg_match('~^(' . $this->_si_qstr_regexp . ')('. $this->_mod_regexp . '*)$~', $val, $match);
                if($match[2] != '') {
                    $this->_parse_modifiers($match[1], $match[2]);
                    return $match[1];
                }
            }
        elseif(preg_match('~^' . $this->_cvar_regexp . '(?:' . $this->_mod_regexp . '*)$~', $val)) {
                // config var
                return $this->_parse_conf_var($val);
            }
        elseif(preg_match('~^' . $this->_svar_regexp . '(?:' . $this->_mod_regexp . '*)$~', $val)) {
                // section var
                return $this->_parse_section_prop($val);
            }
        elseif(!in_array($val, $this->_permitted_tokens) && !is_numeric($val)) {
            // literal string
            return $this->_expand_quoted_text('"' . strtr($val, array('\\' => '\\\\', '"' => '\\"')) .'"');
        }
        return $val;
    }

    /**
     * expand quoted text with embedded variables
     *
     * @param string $var_expr
     * @return string
     */
    function _expand_quoted_text($var_expr) {
        // if contains unescaped $, expand it
        if(preg_match_all('~(?:\`(?<!\\\\)\$' . $this->_dvar_guts_regexp . '(?:' . $this->_obj_ext_regexp . ')*\`)|(?:(?<!\\\\)\$\w+(\[[a-zA-Z0-9]+\])*)~', $var_expr, $_match)) {
            $_match = $_match[0];
            $_replace = array();
            foreach($_match as $_var) {
                $_replace[$_var] = '".(' . $this->_parse_var(str_replace('`','',$_var)) . ')."';
            }
            $var_expr = strtr($var_expr, $_replace);
            $_return = preg_replace('~\.""|(?<!\\\\)""\.~', '', $var_expr);
        } else {
            $_return = $var_expr;
        }
        // replace double quoted literal string with single quotes
        $_return = preg_replace('~^"([\s\w]+)"$~',"'\\1'",$_return);
        return $_return;
    }

    /**
     * parse variable expression into PHP code
     *
     * @param string $var_expr
     * @param string $output
     * @return string
     */
    function _parse_var($var_expr) {
        $_has_math = false;
        $_math_vars = preg_split('~('.$this->_dvar_math_regexp.'|'.$this->_qstr_regexp.')~', $var_expr, -1, PREG_SPLIT_DELIM_CAPTURE);

        if(count($_math_vars) > 1) {
            $_first_var = "";
            $_complete_var = "";
            $_output = "";
            // simple check if there is any math, to stop recursion (due to modifiers with "xx % yy" as parameter)
            foreach($_math_vars as $_k => $_math_var) {
                $_math_var = $_math_vars[$_k];

                if(!empty($_math_var) || is_numeric($_math_var)) {
                    // hit a math operator, so process the stuff which came before it
                    if(preg_match('~^' . $this->_dvar_math_regexp . '$~', $_math_var)) {
                        $_has_math = true;
                        if(!empty($_complete_var) || is_numeric($_complete_var)) {
                            $_output .= $this->_parse_var($_complete_var);
                        }

                        // just output the math operator to php
                        $_output .= $_math_var;

                        if(empty($_first_var))
                            $_first_var = $_complete_var;

                        $_complete_var = "";
                    } else {
                        $_complete_var .= $_math_var;
                    }
                }
            }
            if($_has_math) {
                if(!empty($_complete_var) || is_numeric($_complete_var))
                    $_output .= $this->_parse_var($_complete_var);

                // get the modifiers working (only the last var from math + modifier is left)
                $var_expr = $_complete_var;
            }
        }

        // prevent cutting of first digit in the number (we _definitly_ got a number if the first char is a digit)
        if(is_numeric(substr($var_expr, 0, 1)))
            $_var_ref = $var_expr;
        else
            $_var_ref = substr($var_expr, 1);
        
        if(!$_has_math) {
            
            // get [foo] and .foo and ->foo and (...) pieces
            preg_match_all('~(?:^\w+)|' . $this->_obj_params_regexp . '|(?:' . $this->_var_bracket_regexp . ')|->\$?\w+|\.\$?\w+|\S+~', $_var_ref, $match);
                        
            $_indexes = $match[0];
            $_var_name = array_shift($_indexes);

            /* Handle $smarty.* variable references as a special case. */
            if ($_var_name == 'smarty') {
                /*
                 * If the reference could be compiled, use the compiled output;
                 * otherwise, fall back on the $smarty variable generated at
                 * run-time.
                 */
                if (($smarty_ref = $this->_compile_smarty_ref($_indexes)) !== null) {
                    $_output = $smarty_ref;
                } else {
                    $_var_name = substr(array_shift($_indexes), 1);
                    $_output = "\$this->_smarty_vars['$_var_name']";
                }
            } elseif(is_numeric($_var_name) && is_numeric(substr($var_expr, 0, 1))) {
                // because . is the operator for accessing arrays thru inidizes we need to put it together again for floating point numbers
                if(count($_indexes) > 0)
                {
                    $_var_name .= implode("", $_indexes);
                    $_indexes = array();
                }
                $_output = $_var_name;
            } else {
                $_output = "\$this->_tpl_vars['$_var_name']";
            }

            foreach ($_indexes as $_index) {
                if (substr($_index, 0, 1) == '[') {
                    $_index = substr($_index, 1, -1);
                    if (is_numeric($_index)) {
                        $_output .= "[$_index]";
                    } elseif (substr($_index, 0, 1) == '$') {
                        if (strpos($_index, '.') !== false) {
                            $_output .= '[' . $this->_parse_var($_index) . ']';
                        } else {
                            $_output .= "[\$this->_tpl_vars['" . substr($_index, 1) . "']]";
                        }
                    } else {
                        $_var_parts = explode('.', $_index);
                        $_var_section = $_var_parts[0];
                        $_var_section_prop = isset($_var_parts[1]) ? $_var_parts[1] : 'index';
                        $_output .= "[\$this->_sections['$_var_section']['$_var_section_prop']]";
                    }
                } else if (substr($_index, 0, 1) == '.') {
                    if (substr($_index, 1, 1) == '$')
                        $_output .= "[\$this->_tpl_vars['" . substr($_index, 2) . "']]";
                    else
                        $_output .= "['" . substr($_index, 1) . "']";
                } else if (substr($_index,0,2) == '->') {
                    if(substr($_index,2,2) == '__') {
                        $this->_syntax_error('call to internal object members is not allowed', E_USER_ERROR, __FILE__, __LINE__);
                    } elseif(substr($_index, 2, 1) == '_') {
                        $this->_syntax_error('(secure) call to private object member is not allowed', E_USER_ERROR, __FILE__, __LINE__);
                    } elseif (substr($_index, 2, 1) == '$') {
                        $this->_syntax_error('(secure) call to dynamic object member is not allowed', E_USER_ERROR, __FILE__, __LINE__);
                    } else {
                        $_output .= $_index;
                    }
                } elseif (substr($_index, 0, 1) == '(') {
                    $_index = $this->_parse_parenth_args($_index);
                    $_output .= $_index;
                } else {
                    $_output .= $_index;
                }
            }
        }

        return $_output;
    }

    /**
     * parse arguments in function call parenthesis
     *
     * @param string $parenth_args
     * @return string
     */
    function _parse_parenth_args($parenth_args) {
        preg_match_all('~' . $this->_param_regexp . '~',$parenth_args, $match);
        $orig_vals = $match = $match[0];
        $this->_parse_vars_props($match);
        $replace = array();
        for ($i = 0, $count = count($match); $i < $count; $i++) {
            $replace[$orig_vals[$i]] = $match[$i];
        }
        return strtr($parenth_args, $replace);
    }

    /**
     * parse configuration variable expression into PHP code
     *
     * @param string $conf_var_expr
     */
    function _parse_conf_var($conf_var_expr) {
        $parts = explode('|', $conf_var_expr, 2);
        $var_ref = $parts[0];
        $modifiers = isset($parts[1]) ? $parts[1] : '';

        $var_name = substr($var_ref, 1, -1);

        $output = "\$this->_config[0]['vars']['$var_name']";

        $this->_parse_modifiers($output, $modifiers);

        return $output;
    }

    /**
     * parse section property expression into PHP code
     *
     * @param string $section_prop_expr
     * @return string
     */
    function _parse_section_prop($section_prop_expr) {
        $parts = explode('|', $section_prop_expr, 2);
        $var_ref = $parts[0]; // 需要处理的文本
        $modifiers = isset($parts[1]) ? $parts[1] : ''; // 修饰器名称（包含冒号隔开的参数）

        preg_match('!%(\w+)\.(\w+)%!', $var_ref, $match);
        $section_name = $match[1];
        $prop_name = $match[2];

        $output = "\$this->_sections['$section_name']['$prop_name']";

        $this->_parse_modifiers($output, $modifiers);

        return $output;
    }


    /**
     * 加载对应的修饰器到PHP代码中，最终执行修饰器函数是在编译文件中
     *
     * sets $output to parsed modified chain
     * @param string $output
     * $modifier_string 如 |truncate:96:"..." 其中修饰器参数以冒号隔开
     */
    function _parse_modifiers(&$output, $modifier_string) {
        preg_match_all('~\|(@?\w+)((?>:(?:'. $this->_qstr_regexp . '|[^|]+))*)~', '|' . $modifier_string, $_match);
        list(, $_modifiers, $modifier_arg_strings) = $_match;

        for ($_i = 0, $_for_max = count($_modifiers); $_i < $_for_max; $_i++) {
            $_modifier_name = $_modifiers[$_i];

            if($_modifier_name == 'smarty') {
                // skip smarty modifier
                continue;
            }

            preg_match_all('~:(' . $this->_qstr_regexp . '|[^:]+)~', $modifier_arg_strings[$_i], $_match);
            $_modifier_args = $_match[1]; // 获取修饰器参数（此时为数组形式）

            $this->_parse_vars_props($_modifier_args);

            if($_modifier_name == 'default') {
                // 安全设置：如果处理字符包含变量符号，则强制将'$'符号转为'@'
                if(substr($output, 0, 1) == '$') $output = '@' . $output;
                // 安全设置：如果修饰器名称包含变量符号，则强制将'$'符号转为'@'
                if(isset($_modifier_args[0]) && substr($_modifier_args[0], 0, 1) == '$') {
                    $_modifier_args[0] = '@' . $_modifier_args[0];
                }
            }
         
            // 如果存在修饰器参数，则格式化，将数组形式转为逗号分隔形式
            $_modifier_args = count($_modifier_args) > 0 ? ', '.implode(', ', $_modifier_args) : '';

            $output = '$this->smarty_modifier' . '_' . $_modifier_name . "($output$_modifier_args)";
            // 这部分没有 return 是因为这里生成的函数代码是在编译文件中，最终执行是在编译文件中
        }
    }

    /**
     * Compiles references of type $smarty.foo
     *
     * @param string $indexes
     * @return string
     */
    function _compile_smarty_ref(&$indexes)
    {
        /* Extract the reference name. */
        $_ref = substr($indexes[0], 1);
        foreach($indexes as $_index_no=>$_index) {
            if (substr($_index, 0, 1) != '.' && $_index_no<2 || !preg_match('~^(\.|\[|->)~', $_index)) {
                $this->_syntax_error('$smarty' . implode('', array_slice($indexes, 0, 2)) . ' is an invalid reference', E_USER_ERROR, __FILE__, __LINE__);
            }
        }

        switch ($_ref) {
            case 'now':
                $compiled_ref = 'time()';
                $_max_index = 1;
                break;

            case 'foreach':
                array_shift($indexes);
                $_var = $this->_parse_var_props(substr($indexes[0], 1));
                $_propname = substr($indexes[1], 1);
                $_max_index = 1;
                switch ($_propname) {
                    case 'index':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach[$_var]['iteration']-1)";
                        break;
                        
                    case 'first':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach[$_var]['iteration'] <= 1)";
                        break;

                    case 'last':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach[$_var]['iteration'] == \$this->_foreach[$_var]['total'])";
                        break;
                        
                    case 'show':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach[$_var]['total'] > 0)";
                        break;
                        
                    default:
                        unset($_max_index);
                        $compiled_ref = "\$this->_foreach[$_var]";
                }
                break;

            case 'section':
                array_shift($indexes);
                $_var = $this->_parse_var_props(substr($indexes[0], 1));
                $compiled_ref = "\$this->_sections[$_var]";
                break;

            case 'capture':
                return null;

            case 'template':
                $compiled_ref = "'$this->_current_file'";
                $_max_index = 1;
                break;

            case 'version':
                $compiled_ref = "'$this->_version'";
                $_max_index = 1;
                break;

            case 'config':
                $compiled_ref = "\$this->_config[0]['vars']";
                $_max_index = 3;
                break;

            case 'ldelim':
                $compiled_ref = "'$this->left_delimiter'";
                break;

            case 'rdelim':
                $compiled_ref = "'$this->right_delimiter'";
                break;
                
            default:
                $this->_syntax_error('$smarty.' . $_ref . ' is an unknown reference', E_USER_ERROR, __FILE__, __LINE__);
                break;
        }

        if (isset($_max_index) && count($indexes) > $_max_index) {
            $this->_syntax_error('$smarty' . implode('', $indexes) .' is an invalid reference', E_USER_ERROR, __FILE__, __LINE__);
        }

        array_shift($indexes);
        return $compiled_ref;
    }

    /**
     * Quote subpattern references
     *
     * @param string $string
     * @return string
     */
    function _quote_replace($string) {
        return strtr($string, array('\\' => '\\\\', '$' => '\\$'));
    }

    /**
     * display Smarty syntax error
     *
     * @param string $error_msg
     * @param integer $error_type
     * @param string $file
     * @param integer $line
     */
    function _syntax_error($error_msg, $error_type = E_USER_ERROR, $file=null, $line=null) {
        $this->_trigger_fatal_error("syntax error: $error_msg", $this->_current_file, $this->_current_line_no, $file, $line, $error_type);
    }


    /**

     * push opening tag-name, file-name and line-number on the tag-stack
     * @param string the opening tag's name
     */
    function _push_tag($open_tag) {
        array_push($this->_tag_stack, array($open_tag, $this->_current_line_no));
    }

    /**
     * pop closing tag-name
     * raise an error if this stack-top doesn't match with the closing tag
     * @param string the closing tag's name
     * @return string the opening tag's name
     */
    function _pop_tag($close_tag) {
        $message = '';
        if (count($this->_tag_stack)>0) {
            list($_open_tag, $_line_no) = array_pop($this->_tag_stack);
            if ($close_tag == $_open_tag) {
                return $_open_tag;
            }
            if ($close_tag == 'if' && ($_open_tag == 'else' || $_open_tag == 'elseif' )) {
                return $this->_pop_tag($close_tag);
            }
            if ($close_tag == 'section' && $_open_tag == 'sectionelse') {
                $this->_pop_tag($close_tag);
                return $_open_tag;
            }
            if ($close_tag == 'foreach' && $_open_tag == 'foreachelse') {
                $this->_pop_tag($close_tag);
                return $_open_tag;
            }
            if ($_open_tag == 'else' || $_open_tag == 'elseif') {
                $_open_tag = 'if';
            } elseif ($_open_tag == 'sectionelse') {
                $_open_tag = 'section';
            } elseif ($_open_tag == 'foreachelse') {
                $_open_tag = 'foreach';
            }
            $message = " expected {/$_open_tag} (opened line $_line_no).";
        }
        $this->_syntax_error("mismatched tag {/$close_tag}.$message",
                             E_USER_ERROR, __FILE__, __LINE__);
    }
    
    /**
     * douco modify for DouPHP
     */
    function callback_source($matches) {
        return "".$this->_quote_replace($this->left_delimiter)."php".str_repeat("n",substr_count("","n"))."".$this->_quote_replace($this->right_delimiter)."";
    }


    function _each(&$array) {
       $res = array();
       $key = key($array);
       if ($key !== null) {
           next($array); 
           $res[1] = $res['value'] = $array[$key];
           $res[0] = $res['key'] = $key;
       } else {
           $res = false;
       }
       return $res;
    }
    
    
    
    /**
     * --------------------------------------------------------------------------------------------------
     * 内部构件 internals
     * --------------------------------------------------------------------------------------------------
    /**
    /**
     * Prepend the cache information to the cache file
     * and write it
     *
     * @param string $tpl_file
     * @param string $cache_id
     * @param string $compile_id
     * @param string $results
     * @return true|null
     */

     // $tpl_file, $cache_id, $compile_id, $results

    function smarty_core_write_cache_file($params) {
        // put timestamp in cache header
        $this->_cache_info['timestamp'] = time();
        if ($this->cache_lifetime > -1){
            // expiration set
            $this->_cache_info['expires'] = $this->_cache_info['timestamp'] + $this->cache_lifetime;
        } else {
            // cache will never expire
            $this->_cache_info['expires'] = -1;
        }

        // collapse nocache.../nocache-tags
        if (preg_match_all('!\{(/?)nocache\:[0-9a-f]{32}#\d+\}!', $params['results'], $match, PREG_PATTERN_ORDER)) {
            // remove everything between every pair of outermost noache.../nocache-tags
            // and replace it by a single nocache-tag
            // this new nocache-tag will be replaced by dynamic contents in
            // smarty_core_process_compiled_includes() on a cache-read

            $match_count = count($match[0]);
            $results = preg_split('!(\{/?nocache\:[0-9a-f]{32}#\d+\})!', $params['results'], -1, PREG_SPLIT_DELIM_CAPTURE);

            $level = 0;
            $j = 0;
            for ($i=0, $results_count = count($results); $i < $results_count && $j < $match_count; $i++) {
                if ($results[$i] == $match[0][$j]) {
                    // nocache tag
                    if ($match[1][$j]) { // closing tag
                        $level--;
                        unset($results[$i]);
                    } else { // opening tag
                        if ($level++ > 0) unset($results[$i]);
                    }
                    $j++;
                } elseif ($level > 0) {
                    unset($results[$i]);
                }
            }
            $params['results'] = implode('', $results);
        }
        $this->_cache_info['cache_serials'] = $this->_cache_serials;

        // prepend the cache header info into cache file
        $_cache_info = serialize($this->_cache_info);
        $params['results'] = strlen($_cache_info) . "\n" . $_cache_info . $params['results'];

        // use local cache file
        if(!@is_writable($this->cache_dir)) {
            // cache_dir not writable, see if it exists
            if(!@is_dir($this->cache_dir)) {
                $this->trigger_error('the $cache_dir \'' . $this->cache_dir . '\' does not exist, or is not a directory.', E_USER_ERROR);
                return false;
            }
            $this->trigger_error('unable to write to $cache_dir \'' . realpath($this->cache_dir) . '\'. Be sure $cache_dir is writable by the web server user.', E_USER_ERROR);
            return false;
        }

        $_auto_id = $this->_get_auto_id($params['cache_id'], $params['compile_id']);
        $_cache_file = $this->_get_auto_filename($this->cache_dir, $params['tpl_file'], $_auto_id);
        $_params = array('filename' => $_cache_file, 'contents' => $params['results'], 'create_dirs' => true);
        $this->smarty_core_write_file($_params);
        return true;
    }
 
    /**
     * determines if a resource is secure or not.
     *
     * @param string $resource_name
     * @return boolean
     */

    //  $resource_name
    function smarty_core_is_secure($params) {
        $_rp = realpath($params['resource_name']);
        foreach ((array)$this->template_dir as $curr_dir) {
            if ( ($_cd = realpath($curr_dir)) !== false &&
                 strncmp($_rp, $_cd, strlen($_cd)) == 0 &&
                 substr($_rp, strlen($_cd), 1) == DIRECTORY_SEPARATOR ) {
                return true;
            }
        }

        return false;
    }
 
    /**
     * Extract non-cacheable parts out of compiled template and write it
     *
     * @param string $compile_path
     * @param string $template_compiled
     * @return boolean
     */

    function smarty_core_write_compiled_include($params) {
        $_tag_start = 'if \(\$this->caching && \!\$this->_cache_including\)\: echo \'\{nocache\:('.$params['cache_serial'].')#(\d+)\}\'; endif;';
        $_tag_end   = 'if \(\$this->caching && \!\$this->_cache_including\)\: echo \'\{/nocache\:(\\2)#(\\3)\}\'; endif;';

        preg_match_all('!('.$_tag_start.'(.*)'.$_tag_end.')!Us',
                       $params['compiled_content'], $_match_source, PREG_SET_ORDER);

        // no nocache-parts found: done
        if (count($_match_source)==0) return;

        // convert the matched php-code to functions
        $_include_compiled =  "<?php /* Smarty version ".$this->_version.", created on ".strftime("%Y-%m-%d %H:%M:%S")."\n";
        $_include_compiled .= "         compiled from " . strtr(urlencode($params['resource_name']), array('%2F'=>'/', '%3A'=>':')) . " */\n\n";

        $_compile_path = $params['include_file_path'];

        $this->_cache_serials[$_compile_path] = $params['cache_serial'];
        $_include_compiled .= "\$this->_cache_serials['".$_compile_path."'] = '".$params['cache_serial']."';\n\n?>";

        $_include_compiled .= "<?php";

        $this_varname = ((double)phpversion() >= 5.0) ? '_smarty' : 'this';
        for ($_i = 0, $_for_max = count($_match_source); $_i < $_for_max; $_i++) {
            $_match =& $_match_source[$_i];
            $source = $_match[4];
            if ($this_varname == '_smarty') {
                /* rename $this to $_smarty in the sourcecode */
                $tokens = token_get_all('<?php ' . $_match[4]);

                /* remove trailing <?php */
                $open_tag = '';
                while ($tokens) {
                    $token = array_shift($tokens);
                    if (is_array($token)) {
                        $open_tag .= $token[1];
                    } else {
                        $open_tag .= $token;
                    }
                    if ($open_tag == '<?php ') break;
                }

                for ($i=0, $count = count($tokens); $i < $count; $i++) {
                    if (is_array($tokens[$i])) {
                        if ($tokens[$i][0] == T_VARIABLE && $tokens[$i][1] == '$this') {
                            $tokens[$i] = '$' . $this_varname;
                        } else {
                            $tokens[$i] = $tokens[$i][1];
                        }                   
                    }
                }
                $source = implode('', $tokens);
            }

            /* add function to compiled include */
            $_include_compiled .= "
    function _smarty_tplfunc_$_match[2]_$_match[3](&\$$this_varname)
    {
    $source
    }

    ";
        }
        $_include_compiled .= "\n\n?>\n";

        $_params = array('filename' => $_compile_path,
                         'contents' => $_include_compiled, 'create_dirs' => true);

        $this->smarty_core_write_file($_params);
        return true;
    }
 
    /**
     * write the compiled resource
     *
     * @param string $compile_path
     * @param string $compiled_content
     * @return true
     */
    function smarty_core_write_compiled_resource($params) {
        if(!@is_writable($this->compile_dir)) {
            // compile_dir not writable, see if it exists
            if(!@is_dir($this->compile_dir)) {
                $this->trigger_error('the $compile_dir \'' . $this->compile_dir . '\' does not exist, or is not a directory.', E_USER_ERROR);
                return false;
            }
            $this->trigger_error('unable to write to $compile_dir \'' . realpath($this->compile_dir) . '\'. Be sure $compile_dir is writable by the web server user.', E_USER_ERROR);
            return false;
        }

        $_params = array('filename' => $params['compile_path'], 'contents' => $params['compiled_content'], 'create_dirs' => true);
        $this->smarty_core_write_file($_params);
        return true;
    }
 
    /**
     * read a cache file, determine if it needs to be
     * regenerated or not
     *
     * @param string $tpl_file
     * @param string $cache_id
     * @param string $compile_id
     * @param string $results
     * @return boolean
     */

    //  $tpl_file, $cache_id, $compile_id, &$results

    function smarty_core_read_cache_file(&$params) {
        static  $content_cache = array();

        if ($this->force_compile) {
            // force compile enabled, always regenerate
            return false;
        }

        if (isset($content_cache[$params['tpl_file'].','.$params['cache_id'].','.$params['compile_id']])) {
            list($params['results'], $this->_cache_info) = $content_cache[$params['tpl_file'].','.$params['cache_id'].','.$params['compile_id']];
            return true;
        }

        // use local cache file
        $_auto_id = $this->_get_auto_id($params['cache_id'], $params['compile_id']);
        $_cache_file = $this->_get_auto_filename($this->cache_dir, $params['tpl_file'], $_auto_id);
        $params['results'] = $this->_read_file($_cache_file);

        if (empty($params['results'])) {
            // nothing to parse (error?), regenerate cache
            return false;
        }

        $_contents = $params['results'];
        $_info_start = strpos($_contents, "\n") + 1;
        $_info_len = (int)substr($_contents, 0, $_info_start - 1);
        $_cache_info = unserialize(substr($_contents, $_info_start, $_info_len));
        $params['results'] = substr($_contents, $_info_start + $_info_len);

        if ($this->caching == 2 && isset ($_cache_info['expires'])){
            // caching by expiration time
            if ($_cache_info['expires'] > -1 && (time() > $_cache_info['expires'])) {
                // cache expired, regenerate
                return false;
            }
        } else {
            // caching by lifetime
            if ($this->cache_lifetime > -1 && (time() - $_cache_info['timestamp'] > $this->cache_lifetime)) {
                // cache expired, regenerate
                return false;
            }
        }

        if ($this->compile_check) {
            $_params = array('get_source' => false, 'quiet'=>true);
            foreach (array_keys($_cache_info['template']) as $_template_dep) {
                $_params['resource_name'] = $_template_dep;
                if (!$this->_fetch_resource_info($_params) || $_cache_info['timestamp'] < $_params['resource_timestamp']) {
                    // template file has changed, regenerate cache
                    return false;
                }
            }
        }

        $content_cache[$params['tpl_file'].','.$params['cache_id'].','.$params['compile_id']] = array($params['results'], $_cache_info);

        $this->_cache_info = $_cache_info;
        return true;
    }
 
    /**
     * write out a file to disk
     *
     * @param string $filename
     * @param string $contents
     * @param boolean $create_dirs
     * @return boolean
     */
    function smarty_core_write_file($params) {
        $_dirname = dirname($params['filename']);

        if ($params['create_dirs']) {
            $_params = array('dir' => $_dirname);
            $this->smarty_core_create_dir_structure($_params);
        }

        // write to tmp file, then rename it to avoid file locking race condition
        $_tmp_file = tempnam($_dirname, 'wrt');

        if (!($fd = @fopen($_tmp_file, 'wb'))) {
            $_tmp_file = $_dirname . DIRECTORY_SEPARATOR . uniqid('wrt');
            if (!($fd = @fopen($_tmp_file, 'wb'))) {
                $this->trigger_error("problem writing temporary file '$_tmp_file'");
                return false;
            }
        }

        fwrite($fd, $params['contents']);
        fclose($fd);

        if (DIRECTORY_SEPARATOR == '\\' || !@rename($_tmp_file, $params['filename'])) {
            // On platforms and filesystems that cannot overwrite with rename() 
            // delete the file before renaming it -- because windows always suffers
            // this, it is short-circuited to avoid the initial rename() attempt
            @unlink($params['filename']);
            @rename($_tmp_file, $params['filename']);
        }
        @chmod($params['filename'], $this->_file_perms);

        return true;
    }
 
    /**
     * create full directory structure
     *
     * @param string $dir
     */

    // $dir

    function smarty_core_create_dir_structure($params) {
        if (!file_exists($params['dir'])) {
            $_open_basedir_ini = ini_get('open_basedir');

            if (DIRECTORY_SEPARATOR=='/') {
                /* unix-style paths */
                $_dir = $params['dir'];
                $_dir_parts = preg_split('!/+!', $_dir, -1, PREG_SPLIT_NO_EMPTY);
                $_new_dir = (substr($_dir, 0, 1)=='/') ? '/' : getcwd().'/';
                if($_use_open_basedir = !empty($_open_basedir_ini)) {
                    $_open_basedirs = explode(':', $_open_basedir_ini);
                }

            } else {
                /* other-style paths */
                $_dir = str_replace('\\','/', $params['dir']);
                $_dir_parts = preg_split('!/+!', $_dir, -1, PREG_SPLIT_NO_EMPTY);
                if (preg_match('!^((//)|([a-zA-Z]:/))!', $_dir, $_root_dir)) {
                    /* leading "//" for network volume, or "[letter]:/" for full path */
                    $_new_dir = $_root_dir[1];
                    /* remove drive-letter from _dir_parts */
                    if (isset($_root_dir[3])) array_shift($_dir_parts);

                } else {
                    $_new_dir = str_replace('\\', '/', getcwd()).'/';

                }

                if($_use_open_basedir = !empty($_open_basedir_ini)) {
                    $_open_basedirs = explode(';', str_replace('\\', '/', $_open_basedir_ini));
                }

            }

            /* all paths use "/" only from here */
            foreach ($_dir_parts as $_dir_part) {
                $_new_dir .= $_dir_part;

                if ($_use_open_basedir) {
                    // do not attempt to test or make directories outside of open_basedir
                    $_make_new_dir = false;
                    foreach ($_open_basedirs as $_open_basedir) {
                        if (substr($_new_dir, 0, strlen($_open_basedir)) == $_open_basedir) {
                            $_make_new_dir = true;
                            break;
                        }
                    }
                } else {
                    $_make_new_dir = true;
                }

                if ($_make_new_dir && !file_exists($_new_dir) && !@mkdir($_new_dir, $this->_dir_perms) && !is_dir($_new_dir)) {
                    $this->trigger_error("problem creating directory '" . $_new_dir . "'");
                    return false;
                }
                $_new_dir .= '/';
            }
        }
    }
  
    /**
     * Replace nocache-tags by results of the corresponding non-cacheable
     * functions and return it
     *
     * @param string $compiled_tpl
     * @param string $cached_source
     * @return string
     */

    function smarty_core_process_compiled_include($params) {
        $_cache_including = $this->_cache_including;
        $this->_cache_including = true;

        $_return = $params['results'];

        foreach ($this->_cache_info['cache_serials'] as $_include_file_path=>$_cache_serial) {
            $this->_include($_include_file_path, true);
        }

        foreach ($this->_cache_info['cache_serials'] as $_include_file_path=>$_cache_serial) {
            $_return = preg_replace_callback('!(\{nocache\:('.$_cache_serial.')#(\d+)\})!s',
                                             array($this, '_process_compiled_include_callback'),
                                             $_return);
        }
        $this->_cache_including = $_cache_including;
        return $_return;
    }

    /**
     * --------------------------------------------------------------------------------------------------
     * 修饰器 modifier
     * --------------------------------------------------------------------------------------------------
    /**
    /**
     * Smarty truncate modifier plugin
     */
    function smarty_modifier_truncate ($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
        if ($length == 0) return '';
        if ($this->mystrlen($string) > $length) {
            $length -= min($length, $this->mystrlen($etc));
            if (!$break_words && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/', '', $this->mysubstr($string, 0, $length +1));
            }
            if (!$middle) {
                return $this->mysubstr($string, 0, $length) . $etc;
            } else {
                return $this->mysubstr($string, 0, $length / 2) . $etc . $this->mysubstr($string, - $length / 2);
            }
        } else {
            return $string;
        }
    }

    function mysubstr($str, $start, $len) {
        $step = $this->is_utf8($str) ? 2 : 1;
        $tmpstr = "";
        $strlen = $start + $len;
        for ($i = 0; $i < $strlen; $i++) {
            if (ord(substr($str, $i, 1)) > 0xa0) {
                $tmpstr .= substr($str, $i, $step +1);
                $i += $step;
                $strlen += $step;
            } else {
                $tmpstr .= substr($str, $i, 1);
            }
        }
        return $tmpstr;
    }

    function mystrlen($str) {
        $step = $this->is_utf8($str) ? 2 : 1;
        $strlen = $mystrlen = strlen($str);
        for ($i = 0; $i < $strlen; $i++) {
            if (ord(substr($str, $i, 1)) > 0xa0) {
             $mystrlen -= $step;
             $i += $step;
            }
        }
        return $mystrlen;
    }

    function is_utf8($string) {
        return preg_match('%^(?:
                 [\x09\x0A\x0D\x20-\x7E]            # ASCII
               | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
               | \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
               | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
               | \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
               | \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
               | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
               | \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
           )*$%xs', $string);

    }
 
    /**
     * Smarty escape modifier plugin
     *
     * Type:     modifier<br>
     * Name:     escape<br>
     * Purpose:  Escape the string according to escapement type
     * @link http://smarty.php.net/manual/en/language.modifier.escape.php
     *          escape (Smarty online manual)
     * @author   Monte Ohrt <monte at ohrt dot com>
     * @param string
     * @param html|htmlall|url|quotes|hex|hexentity|javascript
     * @return string
     */
    function smarty_modifier_escape($string, $esc_type = 'html', $char_set = 'ISO-8859-1') {
        switch ($esc_type) {
            case 'html':
                return htmlspecialchars($string, ENT_QUOTES, $char_set);

            case 'htmlall':
                return htmlentities($string, ENT_QUOTES, $char_set);

            case 'url':
                return rawurlencode($string);

            case 'urlpathinfo':
                return str_replace('%2F','/',rawurlencode($string));

            case 'quotes':
                // escape unescaped single quotes
                return preg_replace("%(?<!\\\\)'%", "\\'", $string);

            case 'hex':
                // escape every character into hex
                $return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $return .= '%' . bin2hex($string[$x]);
                }
                return $return;

            case 'hexentity':
                $return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $return .= '&#x' . bin2hex($string[$x]) . ';';
                }
                return $return;

            case 'decentity':
                $return = '';
                for ($x=0; $x < strlen($string); $x++) {
                    $return .= '&#' . ord($string[$x]) . ';';
                }
                return $return;

            case 'javascript':
                // escape quotes and backslashes, newlines, etc.
                return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));

            case 'mail':
                // safe way to display e-mail address on a web page
                return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);

            case 'nonstd':
               // escape non-standard chars, such as ms document quotes
               $_res = '';
               for($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
                   $_ord = ord(substr($string, $_i, 1));
                   // non-standard char, escape it
                   if($_ord >= 126){
                       $_res .= '&#' . $_ord . ';';
                   }
                   else {
                       $_res .= substr($string, $_i, 1);
                   }
               }
               return $_res;

            default:
                return $string;
        }
    }
 
    /**
     * 在字符串中的新行（\n）之前插入换行符
     *
     */
    function smarty_modifier_nl2br($string) {
        return nl2br($string);
    }
 
    /**
     * 剥去字符串中的 HTML 标签
     */
    function smarty_modifier_strip_tags($string, $replace_with_space = true) {
        if ($replace_with_space)
            return preg_replace('!<[^>]*?>!', ' ', $string);
        else
            return strip_tags($string);
    }
 
 
}

/**
 * compare to values by their string length
 *
 * @access private
 * @param string $a
 * @param string $b
 * @return 0|-1|1
 */
function _smarty_sort_length($a, $b) {
    if($a == $b) return 0;
    if(strlen($a) == strlen($b)) return ($a > $b) ? -1 : 1;
    return (strlen($a) > strlen($b)) ? -1 : 1;
}


?>