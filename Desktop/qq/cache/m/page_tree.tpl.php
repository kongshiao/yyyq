<?php /* Smarty version 2.6.26, created on 2019-05-22 22:24:07
         compiled from inc/page_tree.tpl */ ?>
<div class="treeBox">
 <a href="<?php echo $this->_tpl_vars['top']['url']; ?>
"<?php if ($this->_tpl_vars['top_cur']): ?> class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['top']['page_name']; ?>
</a></li>
 <?php $_from = $this->_tpl_vars['page_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
 <a href="<?php echo $this->_tpl_vars['list']['url']; ?>
"<?php if ($this->_tpl_vars['list']['cur']): ?> class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['list']['page_name']; ?>
</a>
 <?php endforeach; endif; unset($_from); ?>
</div>