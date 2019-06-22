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
class File {
    var $file_dir; // 文件上传路径 结尾加斜杠
    var $full_file_dir; // 文件上传绝对路径
    var $thumb_dir; // 为空时默认跟主图在一个目录
    var $file_type; // 上传的类型，默认为：jpg gif png
    var $file_size_max; // 上传大小限制，单位是“KB”，默认为：2048KB
    
    /**
     * +----------------------------------------------------------
     * 构造函数
     * +----------------------------------------------------------
     * $file_dir 文件上传路径
     * $thumb_dir 缩略图路径
     * $file_type 上传文件类型
     * $file_size_max 上传最大限制单位KB
     * +----------------------------------------------------------
     */
    function __construct($file_dir = 'images/upload/', $thumb_dir = '', $file_type = 'jpg,gif,png', $file_size_max = '2048') {
        $this->file_dir = $file_dir; // 文件上传路径 结尾加斜杠
        $this->full_file_dir = ROOT_PATH . '/' . $file_dir; // 文件上传绝对路径
        if (!file_exists($this->full_file_dir)) mkdir($this->full_file_dir, 0777);
        $this->thumb_dir = $thumb_dir; // 缩略图路径（相对于$file_dir） 结尾加斜杠，留空则跟$file_dir相同
        $this->file_type = $file_type;
        $this->file_size_max = $file_size_max;
    }
 
    // 构造函数.兼容PHP4
    function File($file_dir = 'images/upload/', $thumb_dir = '', $file_type = 'jpg,gif,png', $file_size_max = '2048') {
        $this->__construct($file_dir, $thumb_dir, $file_type, $file_size_max);
    }
    
    /**
     * +----------------------------------------------------------
     * 文件上传与信息写入
     * +----------------------------------------------------------
     * $module 模块名称
     * $item_id 项目ID
     * $file_field 文件域
     * $type 文件类别
     * $custom_filename 自定义文件名
     * $thumb 是否创建缩略图
     * primary_key 主键
     * +----------------------------------------------------------
     */
    function box($module, $item_id, $file_field = 'image', $type = 'main', $custom_filename = '', $thumb_width = '', $thumb_height = '', $primary_key = 'id') {
        if ($_FILES[$file_field]['name'] != "") {
            // 在项目中判断是否已经存在文件编号，没有就生成一个
            if ($GLOBALS['dou']->field_exist($module, $file_field)) {
                $number = $GLOBALS['dou']->get_one("SELECT " . $file_field . " FROM " . $GLOBALS['dou']->table($module) . " WHERE $primary_key = '$item_id'");
            }
            $number = $number ? $number : $this->create_file_number();
            
            // 在文件库中判断是否存在文件编号
            if (!$GLOBALS['dou']->value_exist('file', 'number', $number)) {
                // 文件编号不存在则执行写入文件操作
                $file_name = $custom_filename ? $custom_filename : $this->create_file_name($item_id); // 创建一个随机不重复文件名
                $action = 'insert'; // 标注为写入操作
            } else {
                // 文件编号存在执行文件更新操作
                $file_name = $GLOBALS['dou']->get_file_name($GLOBALS['dou']->get_one("SELECT file FROM " . $GLOBALS['dou']->table('file') . " WHERE number = '$number'"));
                if (empty($file_name))
                    $file_name = $custom_filename ? $custom_filename : $this->create_file_name($item_id); // 空文件数据要随机生成一个文件名
                $action = 'update'; // 标注为升级操作
            }
            
            // 文件上传操作
            $full_file_name = $this->upload($file_field, $file_name);
            $file = $this->file_dir . $full_file_name;
            
            // 获取文件大小前需要先清除文件状态缓存
            clearstatcache();
            $size = filesize(ROOT_PATH . '/' . $file);
            
            $add_time = $action_time = time();
            
            // 缩略图
            if ($thumb_width || $thumb_height) {
                $thumb = $this->thumb($full_file_name, $thumb_width, $thumb_height);

                // 获取文件大小
                clearstatcache(); // 获取文件大小前需要先清除文件状态缓存
                $thumb_size = filesize($this->full_file_dir . $this->thumb_dir . $thumb);
            }
            $thumb_size = $thumb_size ? $thumb_size : 0;
            
            if ($action == 'insert') {
                // 写入文件数据
                $sql = "INSERT INTO " . $GLOBALS['dou']->table('file') . " (id, number, file, module, item_id, type, size, thumb_size, action_time, add_time)" . " VALUES (NULL, '$number', '$file', '$module', '$item_id', '$type', '$size', '$thumb_size', '$action_time',  '$add_time')";
            } elseif ($action == 'update') {
                // 更新文件数据
                $sql = "update " . $GLOBALS['dou']->table('file') . " SET file = '$file', size = '$size', thumb_size = '$thumb_size', action_time = '$action_time' WHERE number = '$number'";
            }
         
            // 执行SQL语句
            $GLOBALS['dou']->query($sql);
            
            return $number;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 图片上传的处理函数
     * +----------------------------------------------------------
     * $file_field 上传的图片域
     * $file_name 给上传的图片重命名
     * +----------------------------------------------------------
     */
    function upload($file_field, $file_name = '') {
        if ($GLOBALS['dou']->check_read_write($this->full_file_dir) != 'write') {
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['file_dir_wrong']);
        }
        
        // 没有命名规则默认以时间作为文件名
        if (empty($file_name))
            $file_name = $GLOBALS['dou']->create_rand_string('number', 6, time()); // 设定当前时间为图片名称
        
        if (@ empty($_FILES[$file_field]['name']))
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['file_empty']);

        $name = explode(".", $_FILES[$file_field]["name"]); // 将上传前的文件以“.”分开取得文件类型
        $img_count = count($name); // 获得截取的数量
        $img_type = $name[$img_count - 1]; // 取得文件的类型
        if (stripos($this->file_type, $img_type) === false) {
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['file_support'] . $this->file_type . $GLOBALS['_LANG']['file_support_no'] . $img_type);
        }
        $full_file_name = $file_name . "." . $img_type; // 写入数据库的文件名
        $file_address = $this->full_file_dir . $full_file_name; // 上传后的文件名称
        $file_ok = move_uploaded_file($_FILES[$file_field]["tmp_name"], $file_address);
        if ($file_ok) {
            $img_size = $_FILES[$file_field]["size"];
            $img_size_kb = round($img_size / 1024);
            if ($img_size_kb > $this->file_size_max) {
                @unlink($file_address);
                $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['file_out_size'] . $this->file_size_max . "KB");
            } else {
                return $full_file_name;
            }
        } else {
            $GLOBALS['_LANG']['file_wrong'] = preg_replace('/d%/Ums', $this->file_size_max, $GLOBALS['_LANG']['file_wrong']);
            $GLOBALS['dou']->dou_msg($GLOBALS['_LANG']['file_wrong']);
        }
    }
 
    /**
     * +----------------------------------------------------------
     * 创建图片的缩略图
     * +----------------------------------------------------------
     * $photo 原始图片
     * $width 缩略图宽度
     * $height 缩略图高度
     * $quality 生成缩略图片质量
     * +----------------------------------------------------------
     */
    function thumb($photo, $width = '128', $height = '128', $quality = '95') {
        $photo = $this->full_file_dir . $photo; // 获得图片源
        $img_info = $this->get_img_info($photo);
        $full_thumb_name = substr($img_info["name"], 0, strrpos($img_info["name"], ".")) . "_thumb." . $img_info["ext"];
        
        if ($img_info["type"] == 1) {
            $img = imagecreatefromgif($photo);
        } elseif ($img_info["type"] == 2) {
            $img = imagecreatefromjpeg($photo);
        } elseif ($img_info["type"] == 3) {
            $img = imagecreatefrompng($photo);
        } else {
            $img = "";
        }
        
        if (empty($img)) return false;
    
        // 宽度和高度可不设定，让其根据比例生成
        if (!$width) $width = ($img_info["width"] / $img_info["height"]) * $height;
        if (!$height) $height = ($img_info["height"] / $img_info["width"]) * $width;
        
        if (function_exists("imagecreatetruecolor")) {
            $new_thumb = imagecreatetruecolor($width, $height);
            ImageCopyResampled($new_thumb, $img, 0, 0, 0, 0, $width, $height, $img_info["width"], $img_info["height"]);
        } else {
            $new_thumb = imagecreate($width, $height);
            ImageCopyResized($new_thumb, $img, 0, 0, 0, 0, $width, $height, $img_info["width"], $img_info["height"]);
        }
        
        if (file_exists($this->full_file_dir . $this->thumb_dir . $full_thumb_name)) {
            @ unlink($this->full_file_dir . $this->thumb_dir . $full_thumb_name);
        }
        
        ImageJPEG($new_thumb, $this->full_file_dir . $this->thumb_dir . $full_thumb_name, $quality);
     
        // 释放与 image 关联的内存
        ImageDestroy($new_thumb);
        ImageDestroy($img);
        
        return $full_thumb_name;
    }
 
    /**
     * +----------------------------------------------------------
     * 创建一个随机且不重复的文件编号
     * +----------------------------------------------------------
     * $length 长度
     * +----------------------------------------------------------
     */
    function create_file_number($length = 7) {
        // 字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyz123456789';

        $number = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $number .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        $number = $number . '.file';

        if ($GLOBALS['dou']->value_exist('file', 'number', $number)) {
            $this->create_file_number();
        } else {
            return $number;
        }
    }

    /**
     * +----------------------------------------------------------
     * 创建一个随机不重复的文件名
     * +----------------------------------------------------------
     * $item_id 项目ID
     * +----------------------------------------------------------
     */
    function create_file_name($item_id) {
        $file_name = $item_id . '_' . $GLOBALS['dou']->create_rand_string('number', 6, time());
        $value_exist = $GLOBALS['dou']->get_one("SELECT id FROM " . $GLOBALS['dou']->table('file') . " WHERE file LIKE '%$file_name%'");
     
        if ($value_exist) {
            $this->create_file_name();
        } else {
            return $file_name;
        }
    }
    
    /**
     * +----------------------------------------------------------
     * 获取上传图片信息
     * +----------------------------------------------------------
     * $photo 原始图片地址
     * +----------------------------------------------------------
     */
    function get_img_info($photo) {
        $image_size = getimagesize($photo);
        $img_info["width"] = $image_size[0];
        $img_info["height"] = $image_size[1];
        $img_info["type"] = $image_size[2];
        $img_info["name"] = basename($photo);
        $img_info["ext"] = pathinfo($photo, PATHINFO_EXTENSION);
        
        return $img_info;
    }
 
    /**
     * +----------------------------------------------------------
     * 获取图片列表
     * +----------------------------------------------------------
     */
    function img_list_html($module, $item_id, $type) {
        $sql = "SELECT number, file FROM " . $GLOBALS['dou']->table('file') . " WHERE module = '$module' AND item_id = '$item_id' AND type = '$type' ORDER BY id DESC";
        $query = $GLOBALS['dou']->query($sql);
        while ($row = $GLOBALS['dou']->fetch_array($query)) {
            $html .= '<li><img src="' . ROOT_URL . $row['file'] . '" /><span onclick="fileDel(' . "'" . $row['number'] . "'" . ", 'fileList'" . ');" class="del">X</span></li>';
        }

        return $html;
    }
}

?>