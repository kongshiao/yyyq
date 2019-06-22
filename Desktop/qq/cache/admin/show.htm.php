<?php /* Smarty version 2.6.26, created on 2019-05-22 21:30:43
         compiled from show.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $this->_tpl_vars['lang']['home']; ?>
<?php if ($this->_tpl_vars['ur_here']): ?> - <?php echo $this->_tpl_vars['ur_here']; ?>
 <?php endif; ?></title>
<meta name="Copyright" content="Douco Design." />
<link href="templates/public.css" rel="stylesheet" type="text/css">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "javascript.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>
<div id="dcWrap"> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div id="dcLeft"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
 <div id="dcMain"> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "handle.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="mainBox simpleModule" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
   <h3><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
   <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
    <tr>
     <th><?php echo $this->_tpl_vars['lang']['show_add']; ?>
</th>
     <th><?php echo $this->_tpl_vars['lang']['show_list']; ?>
</th>
    </tr>
    <tr>
     <td width="350" valign="top"><form action="show.php?rec=<?php if ($this->_tpl_vars['show']): ?>update<?php else: ?>insert<?php endif; ?>"<?php if ($this->_tpl_vars['show']): ?> class="formEdit"<?php endif; ?> method="post" enctype="multipart/form-data">
       <div class="formBasic">
        <dl>
         <dt><?php echo $this->_tpl_vars['lang']['show_name']; ?>
</dt>
         <dd>
          <input type="text" name="show_name" value="<?php echo $this->_tpl_vars['show']['show_name']; ?>
" size="20" class="inpMain" />
         </dd>
        </dl>
        <dl>
         <dt><?php echo $this->_tpl_vars['lang']['show_img']; ?>
</dt>
         <dd>
          <input type="file" name="show_img" class="inpFlie" />
          <?php if ($this->_tpl_vars['show']['show_img']): ?><a href="<?php echo $this->_tpl_vars['show']['show_img']; ?>
" target="_blank"><img src="images/icon_yes.png"></a><?php else: ?><?php endif; ?> </dd>
        </dl>
        <dl>
         <dt><?php echo $this->_tpl_vars['lang']['show_link']; ?>
</dt>
         <input type="text" name="show_link" value="<?php echo $this->_tpl_vars['show']['show_link']; ?>
" size="40" class="inpMain" />
         </dd>
        </dl>
        <dl>
         <dt><?php echo $this->_tpl_vars['lang']['sort']; ?>
</dt>
         <dd>
         <dd>
          <input type="text" name="sort" value="<?php if ($this->_tpl_vars['show']['sort']): ?><?php echo $this->_tpl_vars['show']['sort']; ?>
<?php else: ?>50<?php endif; ?>" size="20" class="inpMain" />
         </dd>
        </dl>
        <dl>
         <?php if ($this->_tpl_vars['show']): ?> 
         <a href="show.php" class="btnGray"><?php echo $this->_tpl_vars['lang']['cancel']; ?>
</a>
         <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['show']['id']; ?>
">
         <?php endif; ?>
         <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
         <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
        </dl>
       </div>
      </form></td>
     <td valign="top"><table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
       <tr>
        <td width="100"><b><?php echo $this->_tpl_vars['lang']['show_name']; ?>
</b></td>
        <td></td>
        <td width="50" align="center"><b><?php echo $this->_tpl_vars['lang']['sort']; ?>
</b></td>
        <td width="80" align="center"><b><?php echo $this->_tpl_vars['lang']['handler']; ?>
</b></td>
       </tr>
       <?php $_from = $this->_tpl_vars['show_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_list']):
?>
       
        <tr<?php if ($this->_tpl_vars['show_list']['id'] == $this->_tpl_vars['id']): ?> class="active"<?php endif; ?>>
       
       
        <td><a href="<?php echo $this->_tpl_vars['show_list']['show_img']; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['show_list']['show_img']; ?>
" width="100" /></a></td>
        <td><?php echo $this->_tpl_vars['show_list']['show_name']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['show_list']['sort']; ?>
</td>
        <td align="center"><a href="show.php?rec=edit&id=<?php echo $this->_tpl_vars['show_list']['id']; ?>
"><?php echo $this->_tpl_vars['lang']['edit']; ?>
</a> | <a href="show.php?rec=del&id=<?php echo $this->_tpl_vars['show_list']['id']; ?>
"><?php echo $this->_tpl_vars['lang']['del']; ?>
</a></td>
       </tr>
       <?php endforeach; endif; unset($_from); ?>
      </table></td>
    </tr>
   </table>
  </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
</body>
</html>