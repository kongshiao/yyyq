<?php /* Smarty version 2.6.26, created on 2019-05-30 21:25:12
         compiled from inc/about.tpl */ ?>
<div class="about">
 <h1><?php echo $this->_tpl_vars['site']['site_name']; ?>
</h1>
 <div class="desc"><?php echo $this->smarty_modifier_truncate($this->_tpl_vars['index']['about_content'], 180, "..."); ?>
</div>
 <a href="<?php echo $this->_tpl_vars['index']['about_link']; ?>
" class="more"><?php echo $this->_tpl_vars['lang']['about_link']; ?>
>></a>
</div>