<?php /* Smarty version 2.6.26, created on 2019-06-07 23:39:46
         compiled from backup.htm */ ?>
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
   <div class="mainBox" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
    <h3><a href="<?php echo $this->_tpl_vars['action_link']['href']; ?>
" class="actionBtn"><?php echo $this->_tpl_vars['action_link']['text']; ?>
</a><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
    <?php if ($this->_tpl_vars['rec'] == 'default'): ?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <form name="myform" method="post" action="backup.php?rec=backup">
      <tr>
       <th align="center">
        <input name='chkall' type='checkbox' id='chkall' onclick='selectcheckbox(this.form)' value='check' checked>
       </th>
       <th align="left"><?php echo $this->_tpl_vars['lang']['backup_table_name']; ?>
</th>
       <th align="center"><?php echo $this->_tpl_vars['lang']['backup_table_engine']; ?>
</th>
       <th align="center"><?php echo $this->_tpl_vars['lang']['backup_table_rows']; ?>
</th>
       <th align="center"><?php echo $this->_tpl_vars['lang']['backup_data_length']; ?>
</th>
       <th align="center"><?php echo $this->_tpl_vars['lang']['backup_index_length']; ?>
</th>
       <th align="center"><?php echo $this->_tpl_vars['lang']['backup_data_free']; ?>
</th>
      </tr>
      <?php $_from = $this->_tpl_vars['tables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tables']):
?>
      <tr>
       <td align="center"><input type=checkbox name=tables[] value=<?php echo $this->_tpl_vars['tables']['Name']; ?>
 checked></td>
       <td align="left"><?php echo $this->_tpl_vars['tables']['Name']; ?>
</td>
       <td align="center"><?php echo $this->_tpl_vars['tables']['Engine']; ?>
</td>
       <td align="center"><?php echo $this->_tpl_vars['tables']['Rows']; ?>
</td>
       <td align="center"><?php echo $this->_tpl_vars['tables']['Data_length']; ?>
</td>
       <td align="center"><?php echo $this->_tpl_vars['tables']['Index_length']; ?>
</td>
       <td align="center"><?php echo $this->_tpl_vars['tables']['Data_free']; ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
      <tr>
       <td colspan="7" align="right"><?php echo $this->_tpl_vars['lang']['backup_total_size']; ?>
 <?php echo $this->_tpl_vars['totalsize']; ?>
 KB </td>
      </tr>
      <tr>
       <td colspan="7" align="center"><?php echo $this->_tpl_vars['lang']['backup_vol_set']; ?>
</td>
      </tr>
      <tr>
       <td colspan="7" align="center">
        <?php echo $this->_tpl_vars['lang']['backup_sql_filename']; ?>
：<input type="text" class="inpMain" name="filename" value="<?php echo $this->_tpl_vars['filename']; ?>
" size=30>
        <?php echo $this->_tpl_vars['lang']['backup_vol_size']; ?>
：<input type="text" class="inpMain" name="vol_size" value="2048" size="5">K
       </td>
      </tr>
      <tr>
       <td height="26" colspan="7">
        <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
        <input type="hidden" name="totalsize" value="<?php echo $this->_tpl_vars['totalsize']; ?>
">
        <input type="submit" name="submit" class="btn" value="<?php echo $this->_tpl_vars['lang']['backup_submit']; ?>
"  onClick="document.myform.action='backup.php?rec=backup'">
       </td>
      </tr>
     </form>
    </table>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['rec'] == 'restore'): ?>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
     <tr>
      <th width="" align="left"><?php echo $this->_tpl_vars['lang']['backup_sql_filename']; ?>
</th>
      <th width="100"><?php echo $this->_tpl_vars['lang']['backup_sql_filesize']; ?>
</th>
      <th width="160"><?php echo $this->_tpl_vars['lang']['backup_sql_maketime']; ?>
</th>
      <th width="140"><?php echo $this->_tpl_vars['lang']['handler']; ?>
</th>
     </tr>
     <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
     <tr<?php if ($this->_tpl_vars['file']['number'] > 1): ?> class="child"<?php endif; ?>>
      <td align="left"><?php echo $this->_tpl_vars['file']['filename']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['file']['filesize']; ?>
</td>
      <td align="center"><?php echo $this->_tpl_vars['file']['maketime']; ?>
</td>
      <td align="center"><?php if ($this->_tpl_vars['file']['number'] <= 1): ?><a href="backup.php?rec=import&sql_filename=<?php echo $this->_tpl_vars['file']['filename']; ?>
&token=<?php echo $this->_tpl_vars['token']; ?>
"><?php echo $this->_tpl_vars['lang']['backup_sql_pre']; ?>
</a> | <a href=backup.php?rec=down&sql_filename=<?php echo $this->_tpl_vars['file']['filename']; ?>
><?php echo $this->_tpl_vars['lang']['down']; ?>
</a> | <a href=backup.php?rec=del&sql_filename=<?php echo $this->_tpl_vars['file']['filename']; ?>
><?php echo $this->_tpl_vars['lang']['del']; ?>
</a><?php endif; ?></td>
     </tr>
     <?php endforeach; endif; unset($_from); ?>
    </table>
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