<?php /* Smarty version 2.6.26, created on 2019-05-19 16:08:21
         compiled from inc/product_tree.tpl */ ?>
<div class="treeBox">
 <h3><?php echo $this->_tpl_vars['lang']['product_tree']; ?>
</h3>
 <ul>
  <?php $_from = $this->_tpl_vars['product_category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):foreach ($_from as $this->_tpl_vars['cate']):?>
      <li<?php if ($this->_tpl_vars['cate']['cur']): ?> class="cur"<?php endif; ?>>
        <a class="line-single fleft" href="<?php echo $this->_tpl_vars['cate']['url']; ?>"><?php echo $this->_tpl_vars['cate']['cat_name']; ?></a>
        <?php if ($this->_tpl_vars['cate']['child']): ?><span class="fright"> +</span> <?php endif; ?>
      </li>
  <?php if ($this->_tpl_vars['cate']['child']): ?>
  <ul class="child">
   <?php $_from = $this->_tpl_vars['cate']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
   <li<?php if ($this->_tpl_vars['child']['cur']): ?> class=" cur"<?php endif; ?>><a class="line-single" href="<?php echo $this->_tpl_vars['child']['url']; ?>">-<?php echo $this->_tpl_vars['child']['cat_name']; ?></a>
   </li>
   <?php endforeach; endif; unset($_from); ?>
  </ul>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
 </ul>
</div>