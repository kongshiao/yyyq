<?php /* Smarty version 2.6.26, created on 2019-06-09 21:30:37
         compiled from product_category.dwt */ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
<meta name="keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['description']; ?>
" />
<meta name="generator" content="DouPHP v1.5" />
<link href="<?php echo $this->_tpl_vars['site']['root_url']; ?>
favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="http://127.0.0.1/m/theme/default/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://127.0.0.1/m/theme/default/images/jquery.min.js"></script>
<script type="text/javascript" src="http://127.0.0.1/m/theme/default/images/global.js"></script>
</head>
<body>
<div id="wrapper">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/ur_here.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/product_tree.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div id="productCat">
  <div class="productList">
   <?php $_from = $this->_tpl_vars['product_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['product_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['product_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['product_list']['iteration']++;
?>
   <dl>
    <dd<?php if ($this->_foreach['product_list']['iteration'] % 2 == 0): ?> class="clearBorder"<?php endif; ?>>
    <p class="img"><a href="<?php echo $this->_tpl_vars['product']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['product']['thumb']; ?>
" width="<?php echo $this->_tpl_vars['site']['thumb_width']; ?>
" height="<?php echo $this->_tpl_vars['site']['thumb_height']; ?>
" /></a></p>
    <p class="name"><a href="<?php echo $this->_tpl_vars['product']['url']; ?>
"><?php echo $this->smarty_modifier_truncate($this->_tpl_vars['product']['name'], 10, "."); ?>
</a></p>
    </dd>
   </dl>
   <?php endforeach; endif; unset($_from); ?>
  </div>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/pager.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
</body>
</html>