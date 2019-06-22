<?php /* Smarty version 2.6.26, created on 2019-05-25 10:03:43
         compiled from mobile.htm */ ?>
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
  <div id="subBox">
   <div id="sMenu">
    <h3><i class="mobile"></i><?php echo $this->_tpl_vars['lang']['mobile']; ?>
</h3>
    <ul>
     <li><a href="mobile.php"<?php if ($this->_tpl_vars['rec'] == 'system'): ?> class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['mobile_system']; ?>
</a></li>
     <li><a href="mobile.php?rec=nav"<?php if ($this->_tpl_vars['rec'] == 'nav'): ?> class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['mobile_nav']; ?>
</a></li>
     <li><a href="mobile.php?rec=show"<?php if ($this->_tpl_vars['rec'] == 'show'): ?> class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['mobile_show']; ?>
</a></li>
     <li><a href="mobile.php?rec=theme"<?php if ($this->_tpl_vars['rec'] == 'theme'): ?> class="cur"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['mobile_theme']; ?>
</a></li>
    </ul>
   </div>
   <div id="sMain">
    <div class="mainBox" style="<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
     <?php if ($this->_tpl_vars['rec'] == 'system'): ?>
     <h3><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
     <div class="system">
      <form action="mobile.php?rec=system&act=update" method="post" enctype="multipart/form-data">
       <table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTable">
        <?php $_from = $this->_tpl_vars['cfg_list_mobile']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cfg_list']):
?>
        <?php if ($this->_tpl_vars['cfg_list']['type'] == 'array'): ?>
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
        <?php else: ?>
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
" cols="70" rows="5" class="textArea" /><?php echo $this->_tpl_vars['cfg_list']['value']; ?>
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
          <?php if ($this->_tpl_vars['cfg_list']['name'] == 'mobile_logo' && $this->_tpl_vars['cfg_list']['value']): ?>
          <p class="cue"><?php echo $this->_tpl_vars['lang']['mobile_logo_del']; ?>
</p>
          <?php endif; ?>
         </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <tr>
         <th><?php echo $this->_tpl_vars['lang']['mobile_subdir_binding']; ?>
</th>
         <td>
          <?php if ($this->_tpl_vars['subdir_binding']): ?>
          <a href="mobile.php?rec=system&act=create_subdir_binding" class="btnSet"><?php echo $this->_tpl_vars['lang']['mobile_subdir_binding_recreate']; ?>
</a>
          <a href="mobile.php?rec=system&act=del_subdir_binding" class="btnSet"><?php echo $this->_tpl_vars['lang']['mobile_subdir_binding_del']; ?>
</a>
          <a href="<?php echo $this->_tpl_vars['subdir_binding']; ?>
" class="btnSet" target="_blank"><?php echo $this->_tpl_vars['lang']['mobile_subdir_binding_text']; ?>
：<?php echo $this->_tpl_vars['subdir_binding']; ?>
</a>
          <?php else: ?>
          <a href="mobile.php?rec=system&act=create_subdir_binding" class="btnSet"><?php echo $this->_tpl_vars['lang']['mobile_subdir_binding_create']; ?>
</a>
          <?php endif; ?>
          <p class="cue" style="padding-bottom: 20px;"><?php echo $this->_tpl_vars['lang']['mobile_subdir_binding_cue']; ?>
</p>
         </td>
        </tr>
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
      </form>
     </div>
     <?php endif; ?> 
     <?php if ($this->_tpl_vars['rec'] == 'nav'): ?>
     <h3><a href="<?php echo $this->_tpl_vars['action_link']['href']; ?>
" class="actionBtn"><?php echo $this->_tpl_vars['action_link']['text']; ?>
</a><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
      <?php if ($this->_tpl_vars['act'] == 'default'): ?>
      <table width="100%" border="0" cellpadding="10" cellspacing="0" class="tableBasic">
       <tr>
        <th width="113" align="center"><?php echo $this->_tpl_vars['lang']['nav_name']; ?>
</th>
        <th align="left"><?php echo $this->_tpl_vars['lang']['nav_link']; ?>
</th>
        <th width="80" align="center"><?php echo $this->_tpl_vars['lang']['sort']; ?>
</th>
        <th width="120" align="center"><?php echo $this->_tpl_vars['lang']['handler']; ?>
</th>
       </tr>
       <?php $_from = $this->_tpl_vars['nav_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nav_list']):
?>
       <tr>
        <td><?php echo $this->_tpl_vars['nav_list']['mark']; ?>
 <?php echo $this->_tpl_vars['nav_list']['nav_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['nav_list']['guide']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['nav_list']['sort']; ?>
</td>
        <td align="center"><a href="mobile.php?rec=nav&act=edit&id=<?php echo $this->_tpl_vars['nav_list']['id']; ?>
"><?php echo $this->_tpl_vars['lang']['edit']; ?>
</a> | <a href="mobile.php?rec=nav&act=del&id=<?php echo $this->_tpl_vars['nav_list']['id']; ?>
"><?php echo $this->_tpl_vars['lang']['del']; ?>
</a></td>
       </tr>
       <?php endforeach; endif; unset($_from); ?>
      </table>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['act'] == 'add'): ?>
      <script type="text/javascript">
      <?php echo '
       $(function(){ $(".idTabs").idTabs(); });
      '; ?>
 
      </script>
      <div class="idTabs">
        <ul class="tab">
          <li><a href="#nav_add"><?php echo $this->_tpl_vars['lang']['nav_inside']; ?>
</a></li>
          <li><a href="#nav_defined"><?php echo $this->_tpl_vars['lang']['nav_defined']; ?>
</a></li>
        </ul>
        <div class="items">
          <div id="nav_add">
           <form action="mobile.php?rec=nav&act=insert" method="post">
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="tableBasic">
             <tr>
              <td width="80" height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_system']; ?>
</td>
              <td>
               <select name="nav_menu" onchange="change('nav_name', this)">
                <option value=""><?php echo $this->_tpl_vars['lang']['nav_menu']; ?>
</option>
                <?php $_from = $this->_tpl_vars['catalog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catalog']):
?>
                <option value="<?php echo $this->_tpl_vars['catalog']['module']; ?>
,<?php echo $this->_tpl_vars['catalog']['guide']; ?>
"<?php if ($this->_tpl_vars['catalog']['cur']): ?> selected="selected"<?php endif; ?> title="<?php echo $this->_tpl_vars['catalog']['name']; ?>
"><?php echo $this->_tpl_vars['catalog']['mark']; ?>
 <?php echo $this->_tpl_vars['catalog']['name']; ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
               </select>
              </td>
             </tr>
             <tr>
              <td width="80" height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_name']; ?>
</td>
              <td>
               <input type="text" id="nav_name" name="nav_name" value="" size="40" class="inpMain" />
              </td>
             </tr>
             <tr>
              <td height="35" align="right"><?php echo $this->_tpl_vars['lang']['sort']; ?>
</td>
              <td>
               <input type="text" name="sort" value="50" size="5" class="inpMain" />
              </td>
             </tr>
             <tr>
              <td></td>
              <td>
               <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
               <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
              </td>
             </tr>
            </table>
           </form>
          </div>
          <div id="nav_defined">
           <form action="mobile.php?rec=nav&act=insert" method="post">
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="tableBasic">
             <tr>
              <td width="80" height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_name']; ?>
</td>
              <td>
               <input type="text" name="nav_name" value="" size="40" class="inpMain" />
              </td>
             </tr>
             <tr>
              <td height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_link']; ?>
</td>
              <td>
               <input type="text" name="guide" value="" size="80" class="inpMain" />
              </td>
             </tr>
             <tr>
              <td height="35" align="right"><?php echo $this->_tpl_vars['lang']['sort']; ?>
</td>
              <td>
               <input type="text" name="sort" value="50" size="5" class="inpMain" />
              </td>
             </tr>
             <tr>
              <td></td>
              <td>
               <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
               <input type="hidden" name="nav_menu" value="nav,0" />
               <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
              </td>
             </tr>
            </table>
           </form>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['act'] == 'edit'): ?>
      <form action="mobile.php?rec=nav&act=update" method="post">
       <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
         <th colspan="2">&nbsp;</th>
        </tr>
        <?php if ($this->_tpl_vars['nav_info']['module'] != 'nav'): ?>
        <tr>
         <td width="80" height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_system']; ?>
</td>
         <td>
          <select name="nav_menu" onchange="change('nav_name', this)">
           <option value=""><?php echo $this->_tpl_vars['lang']['nav_menu']; ?>
</option>
           <?php $_from = $this->_tpl_vars['catalog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catalog']):
?>
           <option value="<?php echo $this->_tpl_vars['catalog']['module']; ?>
,<?php echo $this->_tpl_vars['catalog']['guide']; ?>
"<?php if ($this->_tpl_vars['catalog']['cur']): ?> selected="selected"<?php endif; ?> title="<?php echo $this->_tpl_vars['catalog']['name']; ?>
"><?php echo $this->_tpl_vars['catalog']['mark']; ?>
 <?php echo $this->_tpl_vars['catalog']['name']; ?>
</option>
           <?php endforeach; endif; unset($_from); ?>
          </select>
         </td>
        </tr>
        <?php endif; ?>
        <tr>
         <td width="80" height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_name']; ?>
</td>
         <td>
          <input type="text" id="nav_name" name="nav_name" value="<?php echo $this->_tpl_vars['nav_info']['nav_name']; ?>
" size="40" class="inpMain" />
         </td>
        </tr>
       <tr>
         <td height="35" align="right"><?php echo $this->_tpl_vars['lang']['nav_link']; ?>
</td>
         <td>
          <?php if ($this->_tpl_vars['nav_info']['module'] == 'nav'): ?>
          <input type="text" name="guide" value="<?php echo $this->_tpl_vars['nav_info']['url']; ?>
" size="60" class="inpMain" />
          <?php else: ?>
          <input type="text" name="guide_no" value="<?php echo $this->_tpl_vars['nav_info']['url']; ?>
" size="60" readOnly="true" class="inpMain" />
          <b class="cue"><?php echo $this->_tpl_vars['lang']['nav_not_modify']; ?>
</b>
          <?php endif; ?>
         </td>
        </tr>
        <tr>
         <td height="35" align="right"><?php echo $this->_tpl_vars['lang']['sort']; ?>
</td>
         <td>
          <input type="text" name="sort" value="<?php if ($this->_tpl_vars['nav_info']['sort']): ?><?php echo $this->_tpl_vars['nav_info']['sort']; ?>
<?php else: ?>50<?php endif; ?>" size="5" class="inpMain" />
         </td>
        </tr>
        <tr>
         <td></td>
         <td>
          <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
          <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['nav_info']['id']; ?>
" />
          <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" />
         </td>
        </tr>
       </table>
      </form>
      <?php endif; ?>
     <?php endif; ?> 
     <?php if ($this->_tpl_vars['rec'] == 'show'): ?>
     <h3><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic simpleModule">
      <tr>
       <th><?php echo $this->_tpl_vars['lang']['show_add']; ?>
</th>
       <th><?php echo $this->_tpl_vars['lang']['show_list']; ?>
</th>
      </tr>
      <tr>
       <td width="350" valign="top"><form action="mobile.php?rec=show&act=<?php if ($this->_tpl_vars['show']): ?>update<?php else: ?>insert<?php endif; ?>"<?php if ($this->_tpl_vars['show']): ?> class="formEdit"<?php endif; ?> method="post" enctype="multipart/form-data">
         <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableOnebor">
          <tr>
           <td><b><?php echo $this->_tpl_vars['lang']['show_name']; ?>
</b>
            <input type="text" name="show_name" value="<?php echo $this->_tpl_vars['show']['show_name']; ?>
" size="20" class="inpMain" /></td>
          </tr>
          <tr>
           <td><b><?php echo $this->_tpl_vars['lang']['show_img']; ?>
</b>
            <input type="file" name="show_img" class="inpFlie" />
            <?php if ($this->_tpl_vars['show']['show_img']): ?><a href="<?php echo $this->_tpl_vars['show']['show_img']; ?>
" target="_blank"><img src="images/icon_yes.png"></a><?php else: ?><?php endif; ?> </td>
          </tr>
          <tr>
           <td><b><?php echo $this->_tpl_vars['lang']['show_link']; ?>
</b>
            <input type="text" name="show_link" value="<?php echo $this->_tpl_vars['show']['show_link']; ?>
" size="40" class="inpMain" /></td>
          </tr>
          <tr>
           <td><b><?php echo $this->_tpl_vars['lang']['sort']; ?>
</b>
            <input type="text" name="sort" value="<?php if ($this->_tpl_vars['show']['sort']): ?><?php echo $this->_tpl_vars['show']['sort']; ?>
<?php else: ?>50<?php endif; ?>" size="20" class="inpMain" /></td>
          </tr>
          <tr>
           <td><?php if ($this->_tpl_vars['show']): ?> 
            <a href="mobile.php?rec=show" class="btnGray"><?php echo $this->_tpl_vars['lang']['cancel']; ?>
</a>
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['show']['id']; ?>
">
            <?php endif; ?>
            <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
            <input name="submit" class="btn" type="submit" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
" /></td>
          </tr>
         </table>
        </form></td>
       <td valign="top"><table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableOnebor">
         <tr>
          <td width="100"><?php echo $this->_tpl_vars['lang']['show_name']; ?>
</td>
          <td></td>
          <td width="50" align="center"><?php echo $this->_tpl_vars['lang']['sort']; ?>
</td>
          <td width="80" align="center"><?php echo $this->_tpl_vars['lang']['handler']; ?>
</td>
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
          <td align="center"><a href="mobile.php?rec=show&act=edit&id=<?php echo $this->_tpl_vars['show_list']['id']; ?>
"><?php echo $this->_tpl_vars['lang']['edit']; ?>
</a> | <a href="mobile.php?rec=show&act=del&id=<?php echo $this->_tpl_vars['show_list']['id']; ?>
"><?php echo $this->_tpl_vars['lang']['del']; ?>
</a></td>
         </tr>
         <?php endforeach; endif; unset($_from); ?>
        </table></td>
      </tr>
     </table>
     <?php endif; ?> 
     <?php if ($this->_tpl_vars['rec'] == 'theme'): ?>
     <div id="theme">
      <h3><?php echo $this->_tpl_vars['ur_here']; ?>
</h3>
      <ul class="tab">
       <li><a href="mobile.php?rec=theme"<?php if ($this->_tpl_vars['act'] == 'default'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['theme_list']; ?>
</a></li>
       <li><a href="mobile.php?rec=theme&act=install"<?php if ($this->_tpl_vars['act'] == 'install'): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['theme_install']; ?>
<?php if ($this->_tpl_vars['unum']['theme']): ?><span class="unum"><span><?php echo $this->_tpl_vars['unum']['theme']; ?>
</span></span><?php endif; ?></a></li>
      </ul>
      <?php if ($this->_tpl_vars['act'] == 'default'): ?>
      <div class="enable">
       <h2><?php echo $this->_tpl_vars['lang']['theme_enabled']; ?>
</h2>
       <p><img src="<?php echo $this->_tpl_vars['theme_enable']['image']; ?>
" width="170" height="230"></p>
       <dl>
        <dt><?php echo $this->_tpl_vars['theme_enable']['theme_name']; ?>
</dt>
        <dd><?php echo $this->_tpl_vars['lang']['version']; ?>
：<?php echo $this->_tpl_vars['theme_enable']['version']; ?>
</dd>
        <dd><?php echo $this->_tpl_vars['lang']['author']; ?>
：<a href="<?php echo $this->_tpl_vars['theme_enable']['author_uri']; ?>
" target="_blank"><?php echo $this->_tpl_vars['theme_enable']['author']; ?>
</a></dd>
        <dd><?php echo $this->_tpl_vars['lang']['theme_description']; ?>
：<?php echo $this->_tpl_vars['theme_enable']['description']; ?>
</dd>
       </dl>
      </div>
      <div class="themeList">
       <h2><?php echo $this->_tpl_vars['lang']['theme_installed']; ?>
</h2>
       <?php $_from = $this->_tpl_vars['theme_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['theme']):
?>
       <dl class="mobile">
        <p><a href="mobile.php?rec=theme&act=enable&unique_id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
"><img src="<?php echo $this->_tpl_vars['theme']['image']; ?>
" width="170" height="230"></a></p>
        <dt><?php echo $this->_tpl_vars['theme']['theme_name']; ?>
 <?php echo $this->_tpl_vars['theme_enable']['version']; ?>
</dt>
        <dd><?php echo $this->_tpl_vars['lang']['author']; ?>
：<a href="<?php echo $this->_tpl_vars['theme']['author_uri']; ?>
" target="_blank"><?php echo $this->_tpl_vars['theme']['author']; ?>
</a></dd>
        <dd class="btnList"><a href="mobile.php?rec=theme&act=del&unique_id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
" class="del"><?php echo $this->_tpl_vars['lang']['del']; ?>
</a> <span><a href="mobile.php?rec=theme&act=enable&unique_id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
"><?php echo $this->_tpl_vars['lang']['enabled']; ?>
</a> <a href="javascript:void(0)" onclick="douFrame('<?php echo $this->_tpl_vars['theme']['theme_name']; ?>
', 'https://cloud.dou.co/extend.php?rec=client&id=<?php echo $this->_tpl_vars['theme']['unique_id']; ?>
', 'cloud.php?rec=details')"><?php echo $this->_tpl_vars['lang']['theme_preview']; ?>
</a></span></dd>
       </dl>
       <?php endforeach; endif; unset($_from); ?>
      </div>
      <?php endif; ?>
      <?php if ($this->_tpl_vars['act'] == 'install'): ?>
      <div class="selector"></div>
      <div class="cloudList themeList">
      </div>
      <script type="text/javascript">get_cloud_list('mobile', '<?php echo $this->_tpl_vars['get']; ?>
', '<?php echo $this->_tpl_vars['localsite']; ?>
')</script>
      <div class="pager"></div>
      <?php endif; ?>
     </div>
     <?php endif; ?> 
    </div>
   </div>
  </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
</body>
</html>