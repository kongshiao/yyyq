<?php /* Smarty version 2.6.26, created on 2019-05-30 21:45:06
         compiled from inc/recommend_product.tpl */ ?>
<?php if ($this->_tpl_vars['recommend_product']): ?>
<div class="incBox">
 <h3><a href="<?php echo $this->_tpl_vars['url']['product']; ?>
" class="more"><?php echo $this->_tpl_vars['lang']['product_more']; ?>
 ></a><?php echo $this->_tpl_vars['lang']['product_news']; ?>
</h3>
 <div class="productList"> 
  <?php $_from = $this->_tpl_vars['recommend_product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['recommend_product'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['recommend_product']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['recommend_product']['iteration']++;
?>
  <dl>
   <dd<?php if ($this->_foreach['recommend_product']['iteration'] % 2 == 0): ?> class="clearBorder"<?php endif; ?>>
   <p class="img"><a href="<?php echo $this->_tpl_vars['product']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['product']['thumb']; ?>
" width="<?php echo $this->_tpl_vars['site']['thumb_width']; ?>
" height="<?php echo $this->_tpl_vars['site']['thumb_height']; ?>
" /></a></p>
   <p class="name"><a href="<?php echo $this->_tpl_vars['product']['url']; ?>
"><?php echo $this->smarty_modifier_truncate($this->_tpl_vars['product']['name'], 10, "."); ?>
</a></p>
   <p class="price hide"><?php echo $this->_tpl_vars['product']['price']; ?>
</p>
   </dd>
  </dl>
  <?php endforeach; endif; unset($_from); ?> 
 </div >
</div>
<?php endif; ?>