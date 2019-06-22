<?php /* Smarty version 2.6.26, created on 2019-05-25 10:07:49
         compiled from module.htm */ ?>
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
  <div id="module" class="mainBox" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
   <h3><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
   <ul class="tab">
    <li><a href="module.php"<?php if ($this->_tpl_vars['rec'] == 'default'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['module_install_cloud']; ?>
</a></li>
    <li><a href="module.php?rec=install_local"<?php if ($this->_tpl_vars['rec'] == 'install_local'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['module_install_local']; ?>
</a></li>
    <li><a href="module.php?rec=uninstall"<?php if ($this->_tpl_vars['rec'] == 'uninstall'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['module_uninstall']; ?>
</a></li>
   </ul>
   <?php if ($this->_tpl_vars['rec'] == 'default'): ?>
   <div class="selector"></div>
   <div class="cloudList">
   </div>
   <script type="text/javascript">get_cloud_list('module', '<?php echo $this->_tpl_vars['get']; ?>
', '<?php echo $this->_tpl_vars['localsite']; ?>
')</script>
   <div class="pager"></div>
   <?php endif; ?>
   <?php if ($this->_tpl_vars['rec'] == 'install_local'): ?>
   <div class="handler">
    <div class="handbook"><?php echo $this->_tpl_vars['lang']['module_install_local_cue']; ?>
</div>
    <div class="list">
     <h2 class="install"><?php echo $this->_tpl_vars['lang']['module_install_local_list']; ?>
</h2>
     <ul>
     <?php $_from = $this->_tpl_vars['install_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module']):
?>
     <li><em><?php echo $this->_tpl_vars['module']; ?>
</em><a href="cloud.php?rec=handle&type=module&mode=local&cloud_id=<?php echo $this->_tpl_vars['module']; ?>
"><?php echo $this->_tpl_vars['lang']['module_install_local_btn']; ?>
</a></li>
     <?php endforeach; endif; unset($_from); ?> 
     </ul>
    </div>
   </div>
   <?php endif; ?>
   <?php if ($this->_tpl_vars['rec'] == 'uninstall'): ?>
   <div class="handler">
    <div class="handbook"><?php echo $this->_tpl_vars['lang']['module_uninstall_cue']; ?>
</div>
    <div class="list">
     <h2><?php echo $this->_tpl_vars['lang']['module_uninstall_list']; ?>
</h2>
     <ul>
     <?php $_from = $this->_tpl_vars['uninstall_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module']):
?>
     <li><em><?php echo $this->_tpl_vars['module']; ?>
</em><a href="module.php?rec=del&token=<?php echo $this->_tpl_vars['token']; ?>
&extend_id=<?php echo $this->_tpl_vars['module']; ?>
"><?php echo $this->_tpl_vars['lang']['module_uninstall_btn']; ?>
</a></li>
     <?php endforeach; endif; unset($_from); ?> 
     </ul>
    </div>
   </div>
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