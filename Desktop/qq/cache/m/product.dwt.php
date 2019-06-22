<?php /* Smarty version 2.6.26, created on 2019-05-30 21:51:37
         compiled from product.dwt */ ?>
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
 <div id="product">
   <div class="img"><a href="<?php echo $this->_tpl_vars['product']['image']; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['product']['image']; ?>
" width="300" /></a></div>
   <div class="info">
     <h1><?php echo $this->_tpl_vars['product']['name']; ?>
</h1>
     <dl class="defined">
       <?php $_from = $this->_tpl_vars['defined']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['defined'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['defined']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['defined']):
        $this->_foreach['defined']['iteration']++;
?>
       <dd><?php echo $this->_tpl_vars['defined']['arr']; ?>
：<?php echo $this->_tpl_vars['defined']['value']; ?>
</dd>
       <?php endforeach; endif; unset($_from); ?>
     </dl>
     <dl class="price hide"><?php echo $this->_tpl_vars['product']['price']; ?>
</dl>
     <?php if ($this->_tpl_vars['open']['order']): ?>
     <dl class="btnBuy">
     <form action="<?php echo $this->_tpl_vars['site']['m_url']; ?>
order.php?rec=insert" method="post">
      <input type="hidden" name="module" value="product" />
      <input type="hidden" name="item_id" value="<?php echo $this->_tpl_vars['product']['id']; ?>
" />
      <input type="hidden" name="number" value="1" />
      <input type="submit" name="submit" class="addToCart" value="<?php echo $this->_tpl_vars['lang']['order_addtocart']; ?>
" />
     </form>
     </dl>
     <?php else: ?>
     <dl class="tel">
      <dt><?php echo $this->_tpl_vars['lang']['contact_tel']; ?>
：</dt>
      <dd><a href="tel:<?php echo $this->_tpl_vars['site']['tel']; ?>
"><?php echo $this->_tpl_vars['site']['tel']; ?>
</a></dd>
     </dl>
     <?php endif; ?>
   </div>
   <div class="content">
     <h3><?php echo $this->_tpl_vars['lang']['product_content']; ?>
</h3>
     <ul><?php echo $this->_tpl_vars['product']['content']; ?>
</ul>
   </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
</body>
</html>