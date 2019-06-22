<?php /* Smarty version 2.6.26, created on 2019-05-22 22:24:06
         compiled from page.dwt */ ?>
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
<link href="http://127.0.0.1/theme/default/images/zoom.css" rel="stylesheet">
<script type="text/javascript" src="http://127.0.0.1/m/theme/default/images/jquery.min.js"></script>
<script type="text/javascript" src="http://127.0.0.1/m/theme/default/images/global.js"></script>
<script src="http://127.0.0.1/theme/default/images/transition.js"></script>
<script src="http://127.0.0.1/theme/default/images/zoom.js"></script>
</head>
<body>
<div id="wrapper">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/page_tree.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div id="page">
   <h1><?php echo $this->_tpl_vars['page']['page_name']; ?>
</h1>
   <div class="content">
    <?php echo $this->_tpl_vars['page']['content']; ?>

   </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
</body>
</html>