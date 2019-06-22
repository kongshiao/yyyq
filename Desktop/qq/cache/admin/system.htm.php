<?php /* Smarty version 2.6.26, created on 2019-05-19 16:16:22
         compiled from system.htm */ ?>
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
<script type="text/javascript" src="images/jquery.tab.js"></script>
</head>
<body>
<div id="dcWrap">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php if (! $this->_tpl_vars['developer_mode']): ?>
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
   <div id="system" class="mainBox" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
    <h3><?php if ($this->_tpl_vars['site']['developer']): ?><a href="<?php echo $this->_tpl_vars['action_link']['href']; ?>
" class="actionBtn"><?php echo $this->_tpl_vars['action_link']['text']; ?>
</a><?php endif; ?><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
    <div class="idTabs">
     <ul class="tab">
      <?php $_from = $this->_tpl_vars['cfg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tab']):
?>
      <li><a href="#<?php echo $this->_tpl_vars['tab']['name']; ?>
"><?php echo $this->_tpl_vars['tab']['lang']; ?>
</a></li>
      <?php endforeach; endif; unset($_from); ?>
     </ul>
     <div class="formBox">
 <?php else: ?>
     <div class="developer">
      <h3><a href="system.php" class="actionBtn"><?php echo $this->_tpl_vars['lang']['system']; ?>
</a><?php echo $this->_tpl_vars['lang']['system_developer']; ?>
<em><?php echo $this->_tpl_vars['lang']['system_developer_cue']; ?>
</em></h3>
 <?php endif; ?>
      <form action="system.php?rec=update" method="post" enctype="multipart/form-data">
       <?php $_from = $this->_tpl_vars['cfg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cfg']):
?>
       <div id="<?php echo $this->_tpl_vars['cfg']['name']; ?>
">
       <table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTable">
        <?php $_from = $this->_tpl_vars['cfg']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cfg_list']):
?>
        <?php if ($this->_tpl_vars['cfg_list']['type'] != 'array'): ?>
        <tr>
         <th><?php echo $this->_tpl_vars['cfg_list']['lang']; ?>
</th>
         <td>
          <?php if ($this->_tpl_vars['cfg_list']['type'] == 'radio'): ?>
          <label for="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
_0">
           <input type="radio" name="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
" id="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
_0" value="0"<?php if ($this->_tpl_vars['cfg_list']['value'] == '0'): ?> checked="true"<?php endif; ?>>
           <?php echo $this->_tpl_vars['lang']['no']; ?>
</label>
          <label for="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
_1">
           <input type="radio" name="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
" id="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
_1" value="1"<?php if ($this->_tpl_vars['cfg_list']['value'] == '1'): ?> checked="true"<?php endif; ?>>
           <?php echo $this->_tpl_vars['lang']['yes']; ?>
</label>
          <?php elseif ($this->_tpl_vars['cfg_list']['type'] == 'select'): ?>
          <select name="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
">
           <?php $_from = $this->_tpl_vars['cfg_list']['box']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
           <option value="<?php echo $this->_tpl_vars['value']; ?>
"<?php if ($this->_tpl_vars['cfg_list']['value'] == $this->_tpl_vars['value']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['value']; ?>
</option>
           <?php endforeach; endif; unset($_from); ?>
          </select>
          <?php elseif ($this->_tpl_vars['cfg_list']['type'] == 'file'): ?>
          <input type="file" name="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
" size="18" />
          <?php if ($this->_tpl_vars['cfg_list']['value']): ?><a href="../<?php echo $this->_tpl_vars['cfg_list']['value']; ?>
" target="_blank"><img src="images/icon_yes.png"></a><?php else: ?><img src="images/icon_no.png"><?php endif; ?>
          <?php elseif ($this->_tpl_vars['cfg_list']['type'] == 'textarea'): ?>
          <textarea name="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
" cols="83" rows="8" class="textArea" /><?php echo $this->_tpl_vars['cfg_list']['value']; ?>
</textarea>
          <?php else: ?>
          <input type="text" name="<?php echo $this->_tpl_vars['cfg_list']['name']; ?>
" value="<?php echo $this->_tpl_vars['cfg_list']['value']; ?>
" size="80" class="inpMain" />
          <?php endif; ?>
          <?php if ($this->_tpl_vars['cfg_list']['cue']): ?>
           <?php if ($this->_tpl_vars['cfg_list']['type'] == 'radio' || $this->_tpl_vars['cfg_list']['type'] == 'select'): ?>
           <span class="cue ml"><?php echo $this->_tpl_vars['cfg_list']['cue']; ?>
</span>
           <?php else: ?>
           <p class="cue"><?php echo $this->_tpl_vars['cfg_list']['cue']; ?>
</p>
           <?php endif; ?>
          <?php endif; ?>
         </td>
        </tr>
        <?php else: ?>
        <?php $_from = $this->_tpl_vars['cfg_list']['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cfg']):
?>
        <tr>
         <th><?php echo $this->_tpl_vars['cfg']['lang']; ?>
</th>
         <td>
          <input type="text" name="<?php echo $this->_tpl_vars['cfg']['name']; ?>
" value="<?php echo $this->_tpl_vars['cfg']['value']; ?>
" size="80" class="inpMain" />
          <?php if ($this->_tpl_vars['cfg']['cue']): ?>
           <p class="cue"><?php echo $this->_tpl_vars['cfg']['cue']; ?>
</p>
          <?php endif; ?>
         </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
       </table>
       </div>
       <?php endforeach; endif; unset($_from); ?>
       <?php if (! $this->_tpl_vars['developer_mode']): ?>
       <table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTable" style="margin: 20px 0;">
        <tr>
         <th></th>
         <td>
          <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
          <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
         </td>
        </tr>
       </table>
       <?php else: ?>
       <table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTable">
        <tr>
         <th><?php echo $this->_tpl_vars['lang']['tool_custom_admin_path']; ?>
</th>
         <td><a href="tool.php?rec=custom_admin_path" class="btnSet">进入设置</a></td>
        </tr>
       </table>
       <div style="padding: 10px 0 50px 10px">
        <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
        <input type="hidden" name="tab" value="developer" />
        <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
       </div>
       <?php endif; ?>
       </form>
     </div>
 <?php if (! $this->_tpl_vars['developer_mode']): ?>
    </div>
   </div>
 </div>
 <?php endif; ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php if (! $this->_tpl_vars['developer_mode']): ?>
<script type="text/javascript">
 <?php echo '$(function(){$(".idTabs").idTabs();});'; ?>

</script>
<?php endif; ?>
</body>
</html>