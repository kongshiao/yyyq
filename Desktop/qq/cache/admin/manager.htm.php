<?php /* Smarty version 2.6.26, created on 2019-05-22 21:31:36
         compiled from manager.htm */ ?>
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
<div id="dcWrap">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div id="dcLeft"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
 <div id="dcMain">
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "handle.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   <div id="manager" class="mainBox" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
    <h3><?php if ($this->_tpl_vars['rec'] != 'manager_log'): ?><a href="<?php echo $this->_tpl_vars['action_link']['href']; ?>
" class="actionBtn"><?php echo $this->_tpl_vars['action_link']['text']; ?>
</a><?php endif; ?><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
    <?php if ($this->_tpl_vars['rec'] == 'default'): ?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>
      <th width="30" align="center"><?php echo $this->_tpl_vars['lang']['record_id']; ?>
</th>
      <th align="left"><?php echo $this->_tpl_vars['lang']['manager_username']; ?>
</th>
      <th align="center"><?php echo $this->_tpl_vars['lang']['manager_email']; ?>
</th>
      <th align="center"><?php echo $this->_tpl_vars['lang']['manager_add_time']; ?>
</th>
      <th align="center"><?php echo $this->_tpl_vars['lang']['manager_last_login']; ?>
</th>
      <th align="center"><?php echo $this->_tpl_vars['lang']['handler']; ?>
</th>
     </tr>
     <?php $_from = $this->_tpl_vars['manager_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['manager_list']):
?>
     <tr>
      <td align="center"><?php echo $this->_tpl_vars['manager_list']['user_id']; ?>
</td>
      <td><?php echo $this->_tpl_vars['manager_list']['user_name']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['manager_list']['email']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['manager_list']['add_time']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['manager_list']['last_login']; ?>
</td>
      <td align="center"><a href="manager.php?rec=edit&id=<?php echo $this->_tpl_vars['manager_list']['user_id']; ?>
"><?php echo $this->_tpl_vars['lang']['edit']; ?>
</a> | <a href="manager.php?rec=del&id=<?php echo $this->_tpl_vars['manager_list']['user_id']; ?>
"><?php echo $this->_tpl_vars['lang']['del']; ?>
</a></td>
     </tr>
     <?php endforeach; endif; unset($_from); ?>
    </table>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['rec'] == 'add'): ?>
    <form action="manager.php?rec=insert" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right"><?php echo $this->_tpl_vars['lang']['manager_username']; ?>
</td>
       <td>
        <input type="text" name="user_name" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td width="100" align="right"><?php echo $this->_tpl_vars['lang']['manager_email']; ?>
</td>
       <td>
        <input type="text" name="email" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td align="right"><?php echo $this->_tpl_vars['lang']['manager_password']; ?>
</td>
       <td>
        <input type="password" name="password" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td align="right"><?php echo $this->_tpl_vars['lang']['manager_password_confirm']; ?>
</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
        <input type="submit" name="submit" class="btn" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
       </td>
      </tr>
     </table>
    </form>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['rec'] == 'edit'): ?>
    <form action="manager.php?rec=update" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
      <tr>
       <td width="100" align="right"><?php echo $this->_tpl_vars['lang']['manager_username']; ?>
</td>
       <td>
        <input type="text" name="user_name" value="<?php echo $this->_tpl_vars['manager_info']['user_name']; ?>
" size="40" class="inpMain" <?php if ($this->_tpl_vars['user']['action_list'] != 'ALL'): ?>readonly="true"<?php endif; ?>/>
       </td>
      </tr>
      <tr>
       <td width="100" align="right"><?php echo $this->_tpl_vars['lang']['manager_email']; ?>
</td>
       <td>
        <input type="text" name="email" value="<?php echo $this->_tpl_vars['manager_info']['email']; ?>
" size="40" class="inpMain" />
       </td>
      </tr>
      <?php if ($this->_tpl_vars['if_check']): ?>
      <tr>
       <td align="right"><?php echo $this->_tpl_vars['lang']['manager_old_password']; ?>
</td>
       <td>
        <input type="password" name="old_password" size="40" class="inpMain" />
       </td>
      </tr>
      <?php endif; ?>
      <tr>
       <td align="right"><?php echo $this->_tpl_vars['lang']['manager_new_password']; ?>
</td>
       <td>
        <input type="password" name="password" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td align="right"><?php echo $this->_tpl_vars['lang']['manager_new_password_confirm']; ?>
</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
        <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['manager_info']['user_id']; ?>
" />
        <input type="submit" name="submit" class="btn" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
       </td>
      </tr>
     </table>
    </form>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['rec'] == 'manager_log'): ?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>
      <th width="30" align="center"><?php echo $this->_tpl_vars['lang']['record_id']; ?>
</th>
      <th width="150" align="left"><?php echo $this->_tpl_vars['lang']['manager_log_create_time']; ?>
</th>
      <th width="100" align="center"><?php echo $this->_tpl_vars['lang']['manager_log_user_name']; ?>
</th>
      <th align="left"><?php echo $this->_tpl_vars['lang']['manager_log_action']; ?>
</th>
      <th width="100" align="center"><?php echo $this->_tpl_vars['lang']['manager_log_ip']; ?>
</th>
     </tr>
     <?php $_from = $this->_tpl_vars['log_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['log_list']):
?>
     <tr>
      <td align="center"><?php echo $this->_tpl_vars['log_list']['id']; ?>
</td>
      <td><?php echo $this->_tpl_vars['log_list']['create_time']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['log_list']['user_name']; ?>
</td>
      <td align="left"><?php echo $this->_tpl_vars['log_list']['action']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['log_list']['ip']; ?>
</td>
     </tr>
     <?php endforeach; endif; unset($_from); ?>
    </table>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pager.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
   </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 </div>
</body>
</html>