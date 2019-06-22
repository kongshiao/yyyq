<?php /* Smarty version 2.6.26, created on 2019-05-22 21:31:44
         compiled from theme.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $this->_tpl_vars['lang']['home']; ?>
<?php if ($this->_tpl_vars['ur_here']): ?> - <?php echo $this->_tpl_vars['ur_here']; ?>
 <?php endif; ?></title>
<meta name="Copyright" content="Douco Design." />
<link href="templates/public.css" rel="stylesheet" type="text/css">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "javascript.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>
<div id="dcWrap">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div id="dcLeft"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
 <div id="dcMain">
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "handle.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   <div id="theme" class="mainBox" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
   <h3><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
   <ul class="tab">
    <li><a href="theme.php"<?php if ($this->_tpl_vars['rec'] == 'default'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['theme_list']; ?>
</a></li>
    <li><a href="theme.php?rec=install"<?php if ($this->_tpl_vars['rec'] == 'install'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['theme_install']; ?>
<?php if ($this->_tpl_vars['unum']['theme']): ?><span class="unum"><span><?php echo $this->_tpl_vars['unum']['theme']; ?>
</span></span><?php endif; ?></a></li>
   </ul>
   <?php if ($this->_tpl_vars['rec'] == 'default'): ?>
   <div class="enable">
    <h2><?php echo $this->_tpl_vars['lang']['theme_enabled']; ?>
</h2>
    <p><img src="<?php echo $this->_tpl_vars['theme_enable']['image']; ?>
" width="280" height="175"></p>
    <dl>
     <dt><?php echo $this->_tpl_vars['theme_enable']['theme_name']; ?>
</dt>
     <dd><?php echo $this->_tpl_vars['lang']['version']; ?>
：<?php echo $this->_tpl_vars['theme_enable']['version']; ?>
</dd>
     <dd><?php echo $this->_tpl_vars['lang']['author']; ?>
：<a href="<?php echo $this->_tpl_vars['theme_enable']['author_uri']; ?>
" target="_blank"><?php echo $this->_tpl_vars['theme_enable']['author']; ?>
</a></dd>
     <dd><?php echo $this->_tpl_vars['lang']['theme_description']; ?>
：<?php echo $this->_tpl_vars['theme_enable']['description']; ?>
</dd>
    </dl>
   </div>
   <div class="themeList">
    <h2><?php echo $this->_tpl_vars['lang']['theme_installed']; ?>
</h2>
    <?php $_from = $this->_tpl_vars['theme_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['theme']):
?>
    <dl>
     <p><a href="theme.php?rec=enable&unique_id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
"><img src="<?php echo $this->_tpl_vars['theme']['image']; ?>
" width="280" height="175"></a></p>
     <dt><?php echo $this->_tpl_vars['theme']['theme_name']; ?>
 <?php echo $this->_tpl_vars['theme_enable']['version']; ?>
</dt>
     <dd><?php echo $this->_tpl_vars['lang']['author']; ?>
：<a href="<?php echo $this->_tpl_vars['theme']['author_uri']; ?>
" target="_blank"><?php echo $this->_tpl_vars['theme']['author']; ?>
</a></dd>
     <dd class="btnList"><a href="theme.php?rec=del&unique_id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
" class="del"><?php echo $this->_tpl_vars['lang']['del']; ?>
</a> <span><a href="theme.php?rec=enable&unique_id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
"><?php echo $this->_tpl_vars['lang']['enabled']; ?>
</a> <a href="javascript:void(0)" onclick="douFrame('<?php echo $this->_tpl_vars['theme']['theme_name']; ?>
', 'https://cloud.dou.co/extend.php?rec=client&id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
', 'cloud.php?rec=details')"><?php echo $this->_tpl_vars['lang']['theme_preview']; ?>
</a></span></dd>
    </dl>
    <?php endforeach; endif; unset($_from); ?>
   </div>
   <?php endif; ?>
   <?php if ($this->_tpl_vars['rec'] == 'install'): ?>
   <div class="selector"></div>
   <div class="cloudList themeList">
   </div>
   <script type="text/javascript">get_cloud_list('theme', '<?php echo $this->_tpl_vars['get']; ?>
', '<?php echo $this->_tpl_vars['localsite']; ?>
')</script>
   <div class="pager"></div>
   <?php endif; ?>
   </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </div>
</body>
</html>