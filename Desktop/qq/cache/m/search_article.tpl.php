<?php /* Smarty version 2.6.26, created on 2019-06-07 15:06:31
         compiled from inc/search_article.tpl */ ?>
<div class="searchBox">
 <form name="search" method="get" action="<?php echo $this->_tpl_vars['site']['m_url']; ?>
">
  <input type="hidden" name="module" value="article">
  <input name="s" type="text" class="keyword" value="<?php echo $this->smarty_modifier_escape($this->_tpl_vars['keyword_article']); ?>
" placeholder="<?php echo $this->_tpl_vars['lang']['search_article']; ?>
">
  <input type="submit" class="btnSearch" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
">
 </form>
</div>