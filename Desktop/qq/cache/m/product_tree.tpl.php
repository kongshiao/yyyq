<?php /* Smarty version 2.6.26, created on 2019-05-30 21:33:06
         compiled from inc/product_tree.tpl */ ?>
<div class="treeBox">
 <?php $_from = $this->_tpl_vars['product_category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cate']):
?>
 <a href="<?php echo $this->_tpl_vars['cate']['url']; ?>
" <?php if ($this->_tpl_vars['cate']['cur']): ?>class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['cate']['cat_name']; ?>
</a>
 <?php endforeach; endif; unset($_from); ?>
</div>