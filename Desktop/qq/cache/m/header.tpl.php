<?php /* Smarty version 2.6.26, created on 2019-05-22 21:50:59
         compiled from inc/header.tpl */ ?>
<div id="header">
 <?php if ($this->_tpl_vars['index']['cur']): ?>
 <em class="logo"><?php if ($this->_tpl_vars['site']['mobile_logo']): ?><img src="http://127.0.0.1/m/theme/default/images/<?php echo $this->_tpl_vars['site']['mobile_logo']; ?>
"><?php else: ?><?php echo $this->_tpl_vars['site']['mobile_name']; ?>
<?php endif; ?></em>
 <?php else: ?>
 <a href="javascript:;" onClick="javascript:history.back(-1);" class="back"></a>
 <?php endif; ?>
 <?php if ($this->_tpl_vars['product_list'] || $this->_tpl_vars['search_module'] == 'product'): ?>
 <div class="topSearch"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/search_product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
 <?php elseif ($this->_tpl_vars['article_list'] || $this->_tpl_vars['search_module'] == 'article'): ?>
 <div class="topSearch"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/search_article.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
 <?php else: ?>
 <em><?php echo $this->_tpl_vars['head']; ?>
</em>
 <?php endif; ?>
 <a href="<?php echo $this->_tpl_vars['url']['catalog']; ?>
" class="siteMap"></a>
	<?php if ($this->_tpl_vars['open']['order']): ?>
	<a href="<?php echo $this->_tpl_vars['url']['cart']; ?>
" class="order"></a>
	<?php endif; ?>
	<?php if (! $this->_tpl_vars['index']['cur']): ?>
 <a href="<?php echo $this->_tpl_vars['site']['m_url']; ?>
" class="home"></a>
	<?php endif; ?>
</div>