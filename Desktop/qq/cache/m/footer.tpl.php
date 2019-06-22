<?php /* Smarty version 2.6.26, created on 2019-05-22 21:51:00
         compiled from inc/footer.tpl */ ?>
<div id="footer">
 <a href="javascript:;" onfocus="this.blur();" class="goTop"><?php echo $this->_tpl_vars['lang']['go_top']; ?>
</a>
 <ul>
  <li class="footNav"><?php if ($this->_tpl_vars['open']['user']): ?><?php if ($this->_tpl_vars['user']): ?><a href="<?php echo $this->_tpl_vars['url']['user']; ?>
"><?php echo $this->_tpl_vars['user']['user_name']; ?>
，<?php echo $this->_tpl_vars['lang']['user_welcom_top']; ?>
</a><a href="<?php echo $this->_tpl_vars['url']['logout']; ?>
"><?php echo $this->_tpl_vars['lang']['user_logout']; ?>
</a><?php else: ?><a href="<?php echo $this->_tpl_vars['url']['login']; ?>
"><?php echo $this->_tpl_vars['lang']['user_login_nav']; ?>
</a><s></s><a href="<?php echo $this->_tpl_vars['url']['register']; ?>
"><?php echo $this->_tpl_vars['lang']['user_register_nav']; ?>
</a><?php endif; ?><?php endif; ?><a href="<?php echo $this->_tpl_vars['site']['m_url']; ?>
"><?php echo $this->_tpl_vars['lang']['dou_mobile']; ?>
</a><a href="<?php echo $this->_tpl_vars['site']['root_url']; ?>
?mobile"><?php echo $this->_tpl_vars['lang']['dou_pc']; ?>
</a></li>
  <li class="copyRight"><?php echo $this->_tpl_vars['lang']['copyright']; ?>
</li>
  <li class="powered"><?php echo $this->_tpl_vars['lang']['powered_by']; ?>
 <?php if ($this->_tpl_vars['site']['icp']): ?><?php echo $this->_tpl_vars['site']['icp']; ?>
<?php endif; ?></li>
 </ul>
</div>
<?php if ($this->_tpl_vars['site']['code']): ?>
<div style="display:none"><?php echo $this->_tpl_vars['site']['code']; ?>
</div>
<?php endif; ?>