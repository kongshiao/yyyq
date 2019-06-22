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
class Cloud {
    private $cache_dir; // 模块包所在目录
    private $root_dir; // 站点根目录目录
    
    /**
     * +----------------------------------------------------------
     * 构造函数
     * +----------------------------------------------------------
     */
    function __construct($cache_dir) {
        $this->cache_dir = ROOT_PATH . $cache_dir . '/';
        $this->root_dir = ROOT_PATH;
    }
 
    // 构造函数.兼容PHP4
    function Cloud($cache_dir) {
        $this->__construct($cache_dir);
    }

    /**
     * +----------------------------------------------------------
     * 安装模块
     * +----------------------------------------------------------
     * $type 类型
     * $cloud_id 模块ID
     * $mode 是否是本地上传模式
     * +----------------------------------------------------------
     */
    function handle($type, $cloud_id, $mode = '', $version = '') {
        // 基础数据
        $item_zip = $this->cache_dir . $cloud_id . '.zip'; // 扩展压缩包
        $item_dir = $this->cache_dir . $cloud_id; // 扩展目录

        ob_end_flush(); // init.php打开输出缓冲区，输出信息不直接发送到浏览器，要先关闭以便输出缓冲区的内容
        
        // STEP1 验证码最低版本要求
        if ($version) {
            if ($wrong = $this->check_mini_version_support($version)) {
                @unlink($item_zip);
                $this->dou_flush($wrong);
                exit;
            }
        }

        // STEP2 安装条件验证
        if ($mode != 'update') { // 升级时无需安装条件验证
            if ($wrong = $this->install_check($type, $cloud_id)) {
                @unlink($item_zip);
                $this->dou_flush($wrong);
                exit;
            }
        }

        // STEP3 下载压缩包，如果是本地安装则直接显示正在解压缩
        if ($mode == 'local') {
            $this->dou_flush($GLOBALS['_LANG']['cloud_unzip_ing']);
        } else {
            if ($type == 'system') {
                $down_url = 'http://down.dou.co/' . $mode . '/' . $cloud_id . '.zip';
            } elseif ($type == 'onedou') {
                $down_url = 'https://cloud.dou.co/onedou/' . $cloud_id . '.html';
            } else {
                $down_url = 'https://cloud.dou.co/extend/down/' . $cloud_id . '.html';
            }
            
            $this->dou_flush($GLOBALS['_LANG']['cloud_down_ing_0'] . $down_url . $GLOBALS['_LANG']['cloud_down_ing_1']);
            if ($this->file_download($down_url, $this->cache_dir)) {
               $this->dou_flush($GLOBALS['_LANG']['cloud_unzip_ing']);
            } else {
               $this->dou_flush($GLOBALS['_LANG']['cloud_down_wrong']);
               exit;
            }
        }
        
        // STEP4 解压缩
        if ($this->file_unzip($item_zip, $item_dir)) {
            if ($type == 'module') $this->dirname_synchronization($item_dir); // 将安装包里的目录名同步为系统当前设定的目录名（如模板、手机版目录、后台目录、小程序目录）
            $this->dou_flush($GLOBALS['_LANG']['cloud_install_ing'] . $GLOBALS['_LANG']['cloud_' . $type] . '…');
        } else {
            @unlink($item_zip);
            $GLOBALS['dou']->del_dir($item_dir);
            $this->dou_flush($GLOBALS['_LANG']['cloud_unzip_wrong']);
            exit;
        }
        
        // STEP5 安装模块
        if ($wrong = $this->install($type, $cloud_id, $mode)) {
            $this->dou_flush($wrong);
            exit;
        } else {
            $text = $mode == 'update' ? $GLOBALS['_LANG']['cloud_update_0'] : $GLOBALS['_LANG']['cloud_install_0'];
            $success[] = $text . $cloud_id . $GLOBALS['_LANG']['cloud_install_1'];
            $success[] = $this->msg_success($type, $cloud_id);
            
            $this->dou_flush($success);
        }
        
        $GLOBALS['dou']->create_admin_log($GLOBALS['_LANG']['cloud_handle_success'] . $GLOBALS['_LANG']['cloud_' . $type] . ': ' . $cloud_id);
        
        // 操作完成补足页面HTML代码
        echo '</div></div></div><div class="clear"></div><div id="dcFooter"><div id="footer"><div class="line"></div><ul>' . $GLOBALS['_LANG']['footer_copyright'] . '</ul></div></div><div class="clear"></div></div></body></html>';
    }

    /**
     * +----------------------------------------------------------
     * 安装模块
     * +----------------------------------------------------------
     * $type 类型
     * $cloud_id 模块ID
     * $mode 操作模式
     * +----------------------------------------------------------
     */
    function install($type, $cloud_id, $mode) {
        global $prefix;

        // 基础数据
        $item_zip = $this->cache_dir . $cloud_id . '.zip'; // 模块压缩包
        $item_dir = $this->cache_dir . $cloud_id; // 模块目录
        $sql_install = $this->root_dir . "data/backup/$cloud_id.sql"; // 安装用的SQL文件
        $update_dir = $this->root_dir . '_update/'; // 升级程序目录
        $update_file = $update_dir . 'action.php'; // 升级文件
        
        // STEP1 拷贝模块文件
        if ($type == 'theme') {
            $GLOBALS['dou']->copy_dir($item_dir, $this->root_dir . 'theme/' . $cloud_id);
        } elseif ($type == 'mobile') {
            $GLOBALS['dou']->copy_dir($item_dir, $this->root_dir . M_PATH . '/theme/' . $cloud_id);
        } elseif ($type == 'plugin') {
            $GLOBALS['dou']->copy_dir($item_dir, $this->root_dir . 'include/plugin/' . $cloud_id);
        } else {
            // 升级之前新备份使用中的默认模板
            if ($type == 'system' && $mode == 'update') {
                if ($GLOBALS['_CFG']['site_theme'] == 'default') {
                    $GLOBALS['dou']->copy_dir($this->root_dir . 'theme/default', $this->root_dir . 'theme/default_old');
                }
                if ($GLOBALS['_CFG']['mobile_theme'] == 'default') {
                    $GLOBALS['dou']->copy_dir($this->root_dir . M_PATH . '/theme/default', $this->root_dir . M_PATH . '/theme/default_old');
                }
            }
            $GLOBALS['dou']->copy_dir($item_dir, $this->root_dir);
            // 后台目录如果修改过就将文件拷贝到新的后台目录
            if (ADMIN_PATH != 'admin') {
                $GLOBALS['dou']->copy_dir($this->root_dir . 'admin', $this->root_dir . ADMIN_PATH, true); // 参数为true执行剪切操作
            }
        }
        
        // STEP2 数据库操作
        if ($type == 'module') {
            if ($mode != 'update') { // 模块安装操作
                if (file_exists($sql_install)) { // 比如EXCEL导出模块就不包含数据库
                    $sql = file_get_contents($sql_install);
                    $sql = preg_replace('/dou_/Ums', "$prefix", $sql); // 数据表前缀替换
                    if ($GLOBALS['dou']->fn_execute($sql)) {
                        // 根据SQL文件中是否包含'_category'来区分是栏目模块还是简单模块
                        $module_type = strpos($sql, $cloud_id . '_category') === false ? 'single_module' : 'column_module';

                        // 根据SQL中的操作指令来生成操作选项
                        if (strpos($sql, 'CREATE-CONFIG-DISPLAY') !== false) $operate[] = 'CREATE-CONFIG-DISPLAY';
                        if (strpos($sql, 'CREATE-CONFIG-HOME-DISPLAY') !== false) $operate[] = 'CREATE-CONFIG-HOME-DISPLAY';
                        if (strpos($sql, 'CREATE-CONFIG-DEFINED') !== false) $operate[] = 'CREATE-CONFIG-DEFINED';

                        // 加入显示设置项和自定义设置项
                        if ($operate) $this->change_system_config($cloud_id, $operate);
                        
                        // 判断是否需要关联到会员模块
                        if (strpos($sql, 'LINK-USER-CENTER') !== false) $link_user_center = true;
                    } else {
                        $wrong[] = $GLOBALS['_LANG']['cloud_sql_wrong'];
                    }
                } elseif ($cloud_id == 'mobile' || $cloud_id == 'miniprogram') { // DouPHP手机版模块
                    $module_type = 'single_module';
                }
                      
                // 添加自定义菜单
                if (strpos($sql, 'CREATE-NAV') !== false) $this->change_nav($cloud_id, $module_type);
           } else { // 模块升级操作
                if (file_exists($update_dir)) {
                    include_once ($update_file); // 运行升级程序
                }
            }
            
            // STEP3 将模块写入系统文件
            if ($module_type) { // 如果不存在模块类型，则不写入系统文件
                if (!$this->change_system_php($cloud_id, $module_type, false, $link_user_center))
                    $wrong[] = $GLOBALS['_LANG']['cloud_systemfile_wrong'];
            }        
        } elseif ($type == 'system') {
            if (file_exists($update_dir)) {
                include_once ($update_file); // 执行系统升级操作
            }
        }
     
        // STEP4 无论安装成功与否都将删除安装文件或升级文件
        if ($wrong) $this->clear_module($cloud_id); // 如果安装过程出错，则回滚安装步骤
        @unlink($item_zip);
        $GLOBALS['dou']->del_dir($item_dir);
        @unlink($sql_install);
        @unlink($sql_update);
        $GLOBALS['dou']->del_dir($update_dir);
        if ($type == 'system') $GLOBALS['dou']->dou_clear_cache(ROOT_PATH . 'cache'); // 更新缓存
        
        if ($wrong) {
            return $wrong;
        } else {
            if ($type != 'system') // 类型为system时不再重复以下操作
                $this->change_updatedate($type, $cloud_id, false, $mode); // 写入更新日期
        }
    }
 
    /**
     * +----------------------------------------------------------
     * 验证最低版本要求
     * +----------------------------------------------------------
     * $version 版本
     * +----------------------------------------------------------
     */
    function check_mini_version_support($version) {
        $client_version_number = substr($GLOBALS['_CFG']['douphp_version'], -8);
        if ($client_version_number < $version) {
            $wrong[] = $GLOBALS['_LANG']['cloud_below_mini_version_support'];
        }
     
        return $wrong;
    }
    
    /**
     * +----------------------------------------------------------
     * 模块安装验证
     * +----------------------------------------------------------
     * $type 类型
     * $cloud_id 模块ID
     * +----------------------------------------------------------
     */
    function install_check($type, $cloud_id) {
        if ($type == 'module') {
            if (in_array($cloud_id, $GLOBALS['_MODULE']['all'])) {
                $wrong[] = $GLOBALS['_LANG']['cloud_'  .$type] . $GLOBALS['_LANG']['cloud_install_repeat'];
            } elseif ($GLOBALS['dou']->table_exist($cloud_id)) {
                $wrong[] = $GLOBALS['_LANG']['cloud_' . $type] . $GLOBALS['_LANG']['cloud_sql_repeat'];
            }
        } elseif ($type == 'plugin') {
            if (file_exists($this->root_dir . 'include/plugin/' . $cloud_id))
                $wrong[] = $GLOBALS['_LANG']['cloud_' . $type] . $GLOBALS['_LANG']['cloud_install_repeat'];
        } elseif ($type == 'theme') {
            if (file_exists($this->root_dir . 'theme/' . $cloud_id))
                $wrong[] = $GLOBALS['_LANG']['cloud_' . $type] . $GLOBALS['_LANG']['cloud_install_repeat'];
        } elseif ($type == 'mobile') {
            if (file_exists($this->root_dir . M_PATH . '/theme/' . $cloud_id))
                $wrong[] = $GLOBALS['_LANG']['cloud_' . $type] . $GLOBALS['_LANG']['cloud_install_repeat'];
        }
        
        return $wrong;
    }
    
    /**
     * +----------------------------------------------------------
     * 修改系统文件
     * +----------------------------------------------------------
     * $cloud_id 扩展ID
     * $type 模块类型
     * $del 删除模式
     * $link_user_center 关联会员中心
     * +----------------------------------------------------------
     */
    function change_system_php($cloud_id, $type = '', $del = false, $link_user_center = false) {
        // 读取模块列表
        $module = $GLOBALS['_MODULE'];
        
        if ($del) { // 删除模块
            // 栏目模块
            foreach ((array)$module['column'] as $key=>$value) {
                if ($value == $cloud_id) unset($module['column'][$key]);
            }
            
            // 简单模块
            foreach ((array)$module['single'] as $key=>$value) {
                if ($value == $cloud_id) unset($module['single'][$key]);
            }
            
            // 关联会员中心
            foreach ((array)$module['link_user_center'] as $key=>$value) {
                if ($value == $cloud_id) unset($module['link_user_center'][$key]);
            }
        } else { // 添加模块
            if ($type == 'column_module') {
                $module['column'][] = $cloud_id;
            } else {
                $module['single'][] = $cloud_id;
            }
         
            // 判断是否关联会员中心
            if ($link_user_center) {
                $module['link_user_center'][] = $cloud_id;
            }
        }
        
        // 删减后的新模块配置信息
        $new_column = "column_module:" . implode(',', $module['column']);
        $new_single = "single_module:" . implode(',', $module['single']);
     
        // 删减后的新关联会员中心配置信息
        if (!empty($module['link_user_center']))
            $new_link_user_center = "link_user_center:" . implode(',', $module['link_user_center']);
        
        // 系统配置文件逐行读取，并逐行进行替换或保留
        $system_file = $this->root_dir . 'data/system.php';
        foreach (@file($system_file) as $line) {
            $line = trim($line);
         
            if (strpos($line, 'column_module') === 0) {
                $new_content .= $new_column . "\r\n"; // 替换栏目模块行
            } elseif (strpos($line, 'single_module') === 0) {
                $new_content .= $new_single . "\r\n"; // 替换简单模块行
                
                if ($new_link_user_center)
                    $new_content .= $new_link_user_center . "\r\n"; // 替换关联会员中心行
            } elseif (strpos($line, 'link_user_center') === 0) {
            } else {
                $new_content .= $line . "\r\n"; // 假如系统文件有自定义的其它配置信息，将逐行保留
            }
        }
        
        // 将系统文件内容写入
        if (file_put_contents($system_file, $new_content))
            return true;
    }
    
    /**
     * +----------------------------------------------------------
     * 为栏目模块加入显示设置项和自定义设置项
     * +----------------------------------------------------------
     * $cloud_id 模块ID
     * $operate 操作项：CREATE-CONFIG-DEFINED, CREATE-CONFIG-DISPLAY, CREATE-CONFIG-HOME-DISPLAY
     * +----------------------------------------------------------
     */
    function change_system_config($cloud_id, $operate) {
        // 序列化数据转为数组
        $display = unserialize($GLOBALS['_CFG']['display']);
        $defined = unserialize($GLOBALS['_CFG']['defined']);
        $mobile_display = unserialize($GLOBALS['_CFG']['mobile_display']);
        
        // 删减操作
        if ($operate == 'DELL') {
            unset($display[$cloud_id], $display['home_' . $cloud_id], $mobile_display[$cloud_id], $mobile_display['home_' . $cloud_id], $defined[$cloud_id]);
        } else {
            if (in_array('CREATE-CONFIG-DISPLAY', $operate)) {
                $display[$cloud_id] = 10;
                $mobile_display[$cloud_id] = 10;
            }
            if (in_array('CREATE-CONFIG-HOME-DISPLAY', $operate)) {
                $display['home_' . $cloud_id] = 5;
                $mobile_display['home_' . $cloud_id] = 5;
            }
            if (in_array('CREATE-CONFIG-DEFINED', $operate)) {
                $defined[$cloud_id] = '';
            }
        }
        
        // 重新写入显示设置项和自定义设置项
        $GLOBALS['dou']->query("UPDATE " . $GLOBALS['dou']->table('config') . " SET value = '" . serialize($defined) . "' WHERE name = 'defined'");
        $GLOBALS['dou']->query("UPDATE " . $GLOBALS['dou']->table('config') . " SET value = '" . serialize($display) . "' WHERE name = 'display'");
        $GLOBALS['dou']->query("UPDATE " . $GLOBALS['dou']->table('config') . " SET value = '" . serialize($mobile_display) . "' WHERE name = 'mobile_display'");
    }
 
    /**
     * +----------------------------------------------------------
     * 修改自定义导航
     * +----------------------------------------------------------
     */
    function change_nav($cloud_id, $module_type = '', $del = false) {
        if ($del) {
            $GLOBALS['dou']->delete('nav', "module = '$cloud_id'");
            $GLOBALS['dou']->delete('nav', "module = '$cloud_id" . '_category' . "'");
        } else {
            include (ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['language'] . '/' . ADMIN_PATH . '/' . $cloud_id . '.lang.php');
            $nav_name = $_LANG['nav_' . $cloud_id];
            $module = $module_type == 'column_module' ? $cloud_id . '_category' : $cloud_id;
            
            $GLOBALS['dou']->query("INSERT INTO " . $GLOBALS['dou']->table('nav') . " (id, module, nav_name, type)" . " VALUES (NULL, '$module', '$nav_name', 'middle')");
            $GLOBALS['dou']->query("INSERT INTO " . $GLOBALS['dou']->table('nav') . " (id, module, nav_name, type)" . " VALUES (NULL, '$module', '$nav_name', 'mobile')");
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 写入更新日期
     * +----------------------------------------------------------
     * $type 类型
     * $cloud_id 模块ID
     * $del 删除模式
     * $mode 操作模式
     * +----------------------------------------------------------
     */
    function change_updatedate($type, $cloud_id, $del = false, $mode = '') {
        // 读取更新时间（不使用$_CFG是因为$_CFG后如果操作了数据库不能获取操作数据库后的最新数据）
        $update_date = $GLOBALS['dou']->get_one("SELECT value FROM " . $GLOBALS['dou']->table('config') . " WHERE name = 'update_date'");
        $update_date = unserialize($update_date);
        
        // 删减操作
        if ($del) {
            unset($update_date[$type][$cloud_id]);
        } else {
            if ($type == 'system') {
                $date = substr(trim($cloud_id), -8);
                $update_date['system'][$mode] = $date;
            } else {
                $update_date[$type][$cloud_id] = date("Ymd", time());
            }
        }
        
        // 重新写入更新时间
        $new_update_date = serialize($update_date);
        $GLOBALS['dou']->query("UPDATE " . $GLOBALS['dou']->table('config') . " SET value = '$new_update_date' WHERE name = 'update_date'");
    }
    
    /**
     * +----------------------------------------------------------
     * 清理模块至未安装的状态
     * +----------------------------------------------------------
     */
    function clear_module($cloud_id) {
        // STEP1 删除模块数据表
        $this->del_module_table($cloud_id);
        $this->change_system_config($cloud_id, 'DELL'); // 删除配置项
        $this->change_nav($cloud_id, null, true); // 删除自定义菜单
     
        // STEP2 删除模块文件
        $GLOBALS['dou']->del_dir($this->root_dir, false, $this->cache_dir . $cloud_id);

        // STEP3 修改系统文件-删除操作
        $this->change_system_php($cloud_id, null, true);
    }
    
    /**
     * +----------------------------------------------------------
     * 删除模块数据库
     * +----------------------------------------------------------
     * $cloud_id 模块ID
     * +----------------------------------------------------------
     */
    function del_module_table($cloud_id) {
        global $prefix;
        
        // 读取数据库文件
        $sql_file = $this->cache_dir . "$cloud_id/data/backup/$cloud_id.sql";
        if (file_exists($sql_file)) {
            $content = file($sql_file);
            foreach ((array)$content as $line) {
                if (strpos($line, 'DROP TABLE IF EXISTS') !== false) {
                    $line = preg_replace('/dou_/Ums', "$prefix", trim($line)); // 数据表删除语句
                    if (!$GLOBALS['dou']->query($line)) return false;
                }
            }
            
            return true;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 将安装包里的目录名同步为系统当前设定的目录名（如模板、手机版目录、后台目录、小程序目录）
     * +----------------------------------------------------------
     * $item_dir 扩展目录
     * +----------------------------------------------------------
     */
    function dirname_synchronization($item_dir) {
        // 修改安装包模板目录为当前系统启用的模板目录
        if (file_exists($item_dir . '/theme/default')) {
            @rename($item_dir . '/theme/default', $item_dir . '/theme/' . $GLOBALS['_CFG']['site_theme']);
        }
     
        // 修改安装包手机版模板目录为当前系统启用的手机版模板目录
        if (file_exists($item_dir . '/m/theme/default')) {
            @rename($item_dir . '/m/theme/default', $item_dir . '/m/theme/' . $GLOBALS['_CFG']['mobile_theme']);
        }
     
        // 修改安装包手机版目录为当前系统手机版目录
        if (M_PATH != 'm') {
            @rename($item_dir . '/m', $item_dir . '/' . M_PATH);
        }
     
        // 修改安装包小程序目录为当前系统小程序目录
        if (file_exists($item_dir . '/miniprogram')) {
            if (MINIPROGRAM_PATH != 'miniprogram')
                @rename($item_dir . '/miniprogram', $item_dir . '/' . MINIPROGRAM_PATH);
        }
     
        // 修改安装包后台目录为当前系统后台目录
        if (ADMIN_PATH != 'admin') {
            @rename($item_dir . '/admin', $item_dir . '/' . ADMIN_PATH);
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 解压缩文件
     * +----------------------------------------------------------
     * $zipfile 压缩包
     * $savepath 解压后的路径
     * +----------------------------------------------------------
     */
    function file_unzip($zipfile, $savepath) {
        include_once (ROOT_PATH . ADMIN_PATH . '/include/pclzip.class.php');
        $archive = new PclZip($zipfile);
        if ($archive->extract(PCLZIP_OPT_PATH, $savepath)) {
            return true;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 下载文件
     * +----------------------------------------------------------
     * $file_url 文件地址
     * $save_path 保存路径
     * +----------------------------------------------------------
     */
    function file_download($file_url, $save_path) {
        $basename = basename($file_url);
        $file_name = strpos($basename, '.html') ? str_replace('.html', '.zip' ,$basename) : $basename;
        $save_file = $save_path . $file_name;
        $file_url = str_replace(' ', '%20', $file_url);
        
        $cloud_account = unserialize($GLOBALS['_CFG']['cloud_account']);
    
        if(function_exists('curl_init')) {
            $ch = curl_init();
            $data = array('user' => $cloud_account['user'], 'password' => $cloud_account['password'], 'url' => ROOT_URL);  
            curl_setopt($ch, CURLOPT_URL, $file_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POST, 1);  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $temp = curl_exec($ch);
            if(@file_put_contents($save_file, $temp) && !curl_error($ch) && !preg_match("/404 Not Found/", $temp)) {
                return $save_file;
            } else {
                return false;
            }
            curl_close($ch);
        }
    }

    /**
     * +----------------------------------------------------------
     * 输出缓冲区内容
     * +----------------------------------------------------------
     * $text 内容
     * +----------------------------------------------------------
     */
    function dou_flush($text) {
        if (is_array($text)) {
            foreach ($text as $value) {
                $flush .= '<p>' . $value . '</p>';
            }
        } else {
            $flush = '<p>' . $text . '</p>';
        }
        echo $flush;
        sleep(1);
        ob_flush();
        flush();
    }
    
    /**
     * +----------------------------------------------------------
     * 生成安装成功后提示连接
     * +----------------------------------------------------------
     * $type 类型
     * +----------------------------------------------------------
     */
    function msg_success($type, $cloud_id) {
        switch ($type) {
            case 'plugin':
              $msg = '<a href="plugin.php?rec=enable&unique_id=' . $cloud_id . '" class="btnGray">' . $GLOBALS['_LANG']['cloud_plugin_enable'] . '</a> <a href="plugin.php" class="btnGray">' . $GLOBALS['_LANG']['cloud_plugin_home'] . '</a>';
              break;  
            case 'theme':
              $msg = '<a href="theme.php?rec=enable&unique_id=' . $cloud_id . '" class="btnGray">' . $GLOBALS['_LANG']['cloud_theme_enable'] . '</a> <a href="theme.php" class="btnGray">' . $GLOBALS['_LANG']['cloud_theme_home'] . '</a>';
              break;
            case 'mobile':
              $msg = '<a href="mobile.php?rec=theme&act=enable&unique_id=' . $cloud_id . '" class="btnGray">' . $GLOBALS['_LANG']['cloud_theme_enable'] . '</a> <a href="mobile.php?rec=theme" class="btnGray">' . $GLOBALS['_LANG']['cloud_mobile_theme_home'] . '</a>';
              break;
            case 'module':
              $msg = '<a href="module.php" class="btnGray">' . $GLOBALS['_LANG']['cloud_module_home'] . '</a>';
              break;
            default:
              $msg = '<a href="index.php" class="btnGray">' . $GLOBALS['_LANG']['cloud_admin_home'] . '</a>';
        }
        return $msg;
    }

}

?>