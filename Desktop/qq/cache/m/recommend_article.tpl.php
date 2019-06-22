<?php /* Smarty version 2.6.26, created on 2019-05-22 21:51:00
         compiled from inc/recommend_article.tpl */ ?>
<?php if ($this->_tpl_vars['recommend_article']): ?>
<div class="incBox">
 <h3><a href="<?php echo $this->_tpl_vars['url']['article']; ?>
" class="more"><?php echo $this->_tpl_vars['lang']['article_more']; ?>
 ></a><?php echo $this->_tpl_vars['lang']['article_news']; ?>
</h3>
 <div class="articleList">
  <?php $_from = $this->_tpl_vars['recommend_article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['recommend_article'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['recommend_article']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['article']):
        $this->_foreach['recommend_article']['iteration']++;
?>
  <dl>
   <dt><a href="<?php echo $this->_tpl_vars['article']['url']; ?>
"><?php echo $this->_tpl_vars['article']['title']; ?>
</a></dt>
   <dd><em><?php echo $this->_tpl_vars['lang']['add_time']; ?>
：<?php echo $this->_tpl_vars['article']['add_time']; ?>
</em><em><?php echo $this->_tpl_vars['lang']['click']; ?>
：<?php echo $this->_tpl_vars['article']['click']; ?>
</em></dd>
  </dl>
  <?php endforeach; endif; unset($_from); ?>
 </div>
</div>
<?php endif; ?>