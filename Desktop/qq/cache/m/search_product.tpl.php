<?php /* Smarty version 2.6.26, created on 2019-05-22 21:50:59
         compiled from inc/search_product.tpl */ ?>
<div class="searchBox">
 <form name="search" method="get" action="<?php echo $this->_tpl_vars['site']['m_url']; ?>
">
  <input name="s" type="text" class="keyword" value="<?php echo $this->smarty_modifier_escape($this->_tpl_vars['keyword']); ?>
" placeholder="<?php echo $this->_tpl_vars['lang']['search_product']; ?>
">
  <input type="submit" class="btnSearch" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
">
 </form>
</div>