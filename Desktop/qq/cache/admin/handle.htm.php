<?php /* Smarty version 2.6.26, created on 2019-05-19 16:07:46
         compiled from handle.htm */ ?>
<div id="handle"> <!-- 当前位置 -->
 <ul>
  <li class="dropMenu"><a href="JavaScript:void(0);" class="drop"><i></i><em><?php echo $this->_tpl_vars['lang']['top_add']; ?>
</em></a>
   <div class="menu">
    <a href="nav.php?rec=add"><?php echo $this->_tpl_vars['lang']['top_add_nav']; ?>
</a>
    <?php $_from = $this->_tpl_vars['workspace']['menu_column']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
    <a href="<?php echo $this->_tpl_vars['menu']['name']; ?>
.php?rec=add"><?php echo $this->_tpl_vars['menu']['lang_top_add']; ?>
</a>
    <?php endforeach; endif; unset($_from); ?>
    <a href="show.php"><?php echo $this->_tpl_vars['lang']['top_add_show']; ?>
</a>
    <a href="page.php?rec=add"><?php echo $this->_tpl_vars['lang']['top_add_page']; ?>
</a>
    <a href="manager.php?rec=add"><?php echo $this->_tpl_vars['lang']['top_add_manager']; ?>
</a>
    <a href="link.php"><?php echo $this->_tpl_vars['lang']['top_add_link']; ?>
</a>
   </div>
  </li>
  <li class="last"><a href="http://help.dou.co" target="_blank"><i class="help"></i><em><?php echo $this->_tpl_vars['lang']['top_help']; ?>
</em></a></li>
 </ul>
</div>