<?php /* Smarty version 2.6.26, created on 2019-05-19 16:08:09
         compiled from inc/recommend_product.tpl */ ?>
<?php if ($this->_tpl_vars['recommend_product']): ?>
<div class="recProduct">
 <h3><em><?php echo $this->_tpl_vars['lang']['product_news']; ?>
</em><a href="<?php echo $this->_tpl_vars['url']['product']; ?>
" class="more"><?php echo $this->_tpl_vars['lang']['product_more']; ?>
</a></h3>
 <div class="list">
  <?php $_from = $this->_tpl_vars['recommend_product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['recommend_product'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['recommend_product']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['recommend_product']['iteration']++;
?>
  <dl<?php if ($this->_foreach['recommend_product']['iteration'] % 4 == 0): ?> class="noMargin"<?php endif; ?>>
   <dd class="img"><a href="<?php echo $this->_tpl_vars['product']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['product']['thumb']; ?>
" /></a></dd>
   <dt><a href="<?php echo $this->_tpl_vars['product']['url']; ?>
"><?php echo $this->_tpl_vars['product']['name']; ?>
</a></dt>
   <!-- <dd class="price"><?php echo $this->_tpl_vars['product']['price']; ?> -->
</dd>
  </dl>
  <?php endforeach; endif; unset($_from); ?>
 </div>
</div>
<?php endif; ?>