<?php /* Smarty version 2.6.26, created on 2019-05-19 16:07:46
         compiled from index.htm */ ?>
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
<?php if ($this->_tpl_vars['rec'] == 'default' && ! $this->_tpl_vars['site']['close_update']): ?>
<script type="text/javascript">cloud_update_number('<?php echo $this->_tpl_vars['localsite']; ?>
')</script>
<?php endif; ?>
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
  <div id="index" class="mainBox" style="padding-top:18px;<?php echo $this->_tpl_vars['workspace']['height']; ?>
">
   <?php if ($this->_tpl_vars['rec'] == 'default'): ?>
   <div id="right">
    <div class="quickMenu">
     <h2><?php echo $this->_tpl_vars['lang']['quick_menu']; ?>
</h2>
     <div class="menu">
      <dl>
       <?php if ($this->_tpl_vars['site']['rewrite']): ?>
       <dt><a href="system.php"><?php echo $this->_tpl_vars['lang']['close']; ?>
</a><?php echo $this->_tpl_vars['lang']['quick_menu_rewrite']; ?>
（<?php echo $this->_tpl_vars['lang']['opened']; ?>
）</dt>
       <?php else: ?>
       <dt><a href="system.php"><?php echo $this->_tpl_vars['lang']['open']; ?>
</a><?php echo $this->_tpl_vars['lang']['quick_menu_rewrite']; ?>
（<?php echo $this->_tpl_vars['lang']['open_no']; ?>
）</dt>
       <?php endif; ?>
       <dd><?php echo $this->_tpl_vars['lang']['quick_menu_rewrite_cue']; ?>
</dd>
      </dl>
      <dl>
       <?php if ($this->_tpl_vars['site']['captcha']): ?>
       <dt><a href="system.php"><?php echo $this->_tpl_vars['lang']['close']; ?>
</a><?php echo $this->_tpl_vars['lang']['quick_menu_captcha']; ?>
（<?php echo $this->_tpl_vars['lang']['opened']; ?>
）</dt>
       <?php else: ?>
       <dt><a href="system.php"><?php echo $this->_tpl_vars['lang']['open']; ?>
</a><?php echo $this->_tpl_vars['lang']['quick_menu_captcha']; ?>
（<?php echo $this->_tpl_vars['lang']['open_no']; ?>
）</dt>
       <?php endif; ?>
       <dd><?php echo $this->_tpl_vars['lang']['quick_menu_captcha_cue']; ?>
</dd>
      </dl>
      <dl>
       <dt><a href="tool.php?rec=directory_check"><?php echo $this->_tpl_vars['lang']['quick_menu_directory_check']; ?>
</a><?php echo $this->_tpl_vars['lang']['quick_menu_directory']; ?>
</dt>
       <dd><?php echo $this->_tpl_vars['lang']['quick_menu_directory_cue']; ?>
</dd>
      </dl>
     </div>
    </div>
   </div>
   <div id="main"> 
    <?php $_from = $this->_tpl_vars['sys_info']['folder_exists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['warning']):
?>
    <div class="warning"><?php echo $this->_tpl_vars['warning']; ?>
</div>
    <?php endforeach; endif; unset($_from); ?>
    <div id="douApi"></div>
    <div class="indexBox">
     <h2><?php echo $this->_tpl_vars['lang']['title_page']; ?>
</h2>
     <ul class="page">
      <?php $_from = $this->_tpl_vars['page_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?> 
      <a href="page.php?rec=edit&id=<?php echo $this->_tpl_vars['page']['id']; ?>
"<?php if ($this->_tpl_vars['page']['level'] > 0): ?> class="child<?php echo $this->_tpl_vars['page']['level']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['page']['page_name']; ?>
</a> 
      <?php endforeach; endif; unset($_from); ?>
      <div class="clear"></div>
     </ul>
    </div>
    <?php if ($this->_tpl_vars['quick_start']): ?>
    <div class="indexBox">
     <h2><a href="index.php?rec=close_quick_start" class="close" title="<?php echo $this->_tpl_vars['lang']['close']; ?>
">X</a><?php echo $this->_tpl_vars['lang']['quick_start']; ?>
<em>（<?php echo $this->_tpl_vars['lang']['quick_start_cue']; ?>
）</em></h2>
     <ul class="quickStart">
      <?php $_from = $this->_tpl_vars['quick_start']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?> 
      <li>
       <span>
        <em><?php echo $this->_tpl_vars['item']['text']; ?>
</em>
        <a href="<?php echo $this->_tpl_vars['item']['link']; ?>
"<?php if ($this->_tpl_vars['item']['target']): ?> target="<?php echo $this->_tpl_vars['item']['target']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['lang']['quick_start_do']; ?>
</a>
       </span>
      </li>
      <?php endforeach; endif; unset($_from); ?>
     </ul>
    </div>
    <?php endif; ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="indexBoxTwo">
     <tr>
      <td width="50%" valign="top" class="pr">
       <div class="backupBox">
        <div class="indexBox">
         <h2><?php echo $this->_tpl_vars['lang']['title_backup']; ?>
</h2>
         <div class="backup">
          <dl>
           <dd class="date"><?php echo $this->_tpl_vars['backup']['new']['maketime']; ?>
</dd>
           <dt><?php echo $this->_tpl_vars['lang']['backup_new']; ?>
：<?php echo $this->_tpl_vars['backup']['new']['filename']; ?>
</dt>
           <dd class="size"><?php echo $this->_tpl_vars['backup']['new']['filesize']; ?>
</dd>
          </dl>
          <dl class="last">
           <dd class="date"><?php echo $this->_tpl_vars['backup']['old']['maketime']; ?>
</dd>
           <dt><?php echo $this->_tpl_vars['lang']['backup_old']; ?>
：<?php echo $this->_tpl_vars['backup']['old']['filename']; ?>
</dt>
           <dd class="size"><?php echo $this->_tpl_vars['backup']['old']['filesize']; ?>
</dd>
          </dl>
         </div>
        </div>
        <div class="prompt<?php if ($this->_tpl_vars['backup']['light']): ?> red<?php endif; ?>">
         <span class="text"><?php echo $this->_tpl_vars['lang']['backup_action_cue']; ?>
<em><?php echo $this->_tpl_vars['backup']['msg']; ?>
</em></span>
         <a href="backup.php" class="btnBackup"><?php echo $this->_tpl_vars['lang']['backup_action_btn']; ?>
</a>
        </div>
       </div>
       <div class="indexBox">
        <h2><?php echo $this->_tpl_vars['lang']['title_site_info']; ?>
</h2>
        <div class="siteInfo">
         <ul>
          <li><?php echo $this->_tpl_vars['lang']['num_page']; ?>
：<?php echo $this->_tpl_vars['sys_info']['num_page']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['num_article']; ?>
：<?php echo $this->_tpl_vars['sys_info']['num_article']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['num_product']; ?>
：<?php echo $this->_tpl_vars['sys_info']['num_product']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['num_product']; ?>
：<?php echo $this->_tpl_vars['sys_info']['num_product']; ?>
</li>
         </ul>
         <ul>
          <li><?php echo $this->_tpl_vars['lang']['language']; ?>
：<?php echo $this->_tpl_vars['site']['language']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['charset']; ?>
：<?php echo $this->_tpl_vars['sys_info']['charset']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['site_theme']; ?>
：<?php echo $this->_tpl_vars['site']['site_theme']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['rewrite']; ?>
：<?php if ($this->_tpl_vars['site']['rewrite']): ?><?php echo $this->_tpl_vars['lang']['open']; ?>
<?php else: ?><?php echo $this->_tpl_vars['lang']['close']; ?>
<a href="system.php" class="cueRed ml"><?php echo $this->_tpl_vars['lang']['open_cue']; ?>
</a> 
             <?php endif; ?></li>
          <li><?php echo $this->_tpl_vars['lang']['if_sitemap']; ?>
：<?php if ($this->_tpl_vars['site']['sitemap']): ?><?php echo $this->_tpl_vars['lang']['open']; ?>
<?php else: ?><?php echo $this->_tpl_vars['lang']['close']; ?>
<?php endif; ?></li>
          <li><?php echo $this->_tpl_vars['lang']['build_date']; ?>
：<?php echo $this->_tpl_vars['sys_info']['build_date']; ?>
</li>
         </ul>
         <ul class="last long">
          <li><?php echo $this->_tpl_vars['lang']['dou_version']; ?>
：<?php echo $this->_tpl_vars['site']['douphp_version']; ?>
</li>
         </ul>
        </div>
       </div>
      </td>
      <td valign="top" class="pl">
       <div class="indexBox">
        <h2><?php echo $this->_tpl_vars['lang']['title_admin_log']; ?>
<em>（<?php echo $this->_tpl_vars['lang']['manager_log_create_time']; ?>
/<?php echo $this->_tpl_vars['lang']['manager_log_user_name']; ?>
/<?php echo $this->_tpl_vars['lang']['manager_log_ip']; ?>
）</em></h2>
        <div class="adminLog">
         <?php $_from = $this->_tpl_vars['log_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['log_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['log_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['log']):
        $this->_foreach['log_list']['iteration']++;
?>
         <dl<?php if (($this->_foreach['log_list']['iteration'] == $this->_foreach['log_list']['total'])): ?> class="last"<?php endif; ?>>
          <dd class="date"><?php echo $this->_tpl_vars['log']['ip']; ?>
</dd>
          <dt><?php echo $this->_tpl_vars['log']['create_time']; ?>
</dt>
          <dd class="name"><?php echo $this->_tpl_vars['log']['user_name']; ?>
</dd>
         </dl>
         <?php endforeach; endif; unset($_from); ?>
        </div>
       </div>
       <div class="indexBox">
        <h2><?php echo $this->_tpl_vars['lang']['title_sys_info']; ?>
</h2>
        <div class="siteInfo">
         <ul>
          <li><?php echo $this->_tpl_vars['lang']['php_version']; ?>
：<?php echo $this->_tpl_vars['sys_info']['php_ver']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['max_filesize']; ?>
：<?php echo $this->_tpl_vars['sys_info']['max_filesize']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['gd']; ?>
：<?php echo $this->_tpl_vars['sys_info']['gd']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['zlib']; ?>
：<?php echo $this->_tpl_vars['sys_info']['zlib']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['timezone']; ?>
：<?php echo $this->_tpl_vars['sys_info']['timezone']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['socket']; ?>
：<?php echo $this->_tpl_vars['sys_info']['socket']; ?>
</li>
         </ul>
         <ul class="last long">
          <li><?php echo $this->_tpl_vars['lang']['mysql_version']; ?>
：<?php echo $this->_tpl_vars['sys_info']['mysql_ver']; ?>
</li>
          <li><?php echo $this->_tpl_vars['lang']['os']; ?>
：<?php echo $this->_tpl_vars['sys_info']['os']; ?>
(<?php echo $this->_tpl_vars['sys_info']['ip']; ?>
)</li>
          <li><?php echo $this->_tpl_vars['lang']['web_server']; ?>
：<?php echo $this->_tpl_vars['sys_info']['web_server']; ?>
</li>
         </ul>
        </div>
       </div>
      </td>
     </tr>
    </table>
    <div class="indexBox">
     <h2><?php echo $this->_tpl_vars['lang']['title_official']; ?>
</h2>
     <ul class="powered">
      <li><?php echo $this->_tpl_vars['lang']['about_site']; ?>
：<a href="http://www.dou.co" target="_blank">http://www.dou.co</a></li>
      <li><?php echo $this->_tpl_vars['lang']['about_help']; ?>
：<a href="http://help.dou.co" target="_blank">help.dou.co</a><em>（<?php echo $this->_tpl_vars['lang']['about_help_cue']; ?>
）</em></li>
      <li><?php echo $this->_tpl_vars['lang']['about_contributor']; ?>
：Wooyun.org, Pany, Tea</li>
      <li><?php echo $this->_tpl_vars['lang']['about_license']; ?>
：<a href="http://www.dou.co/license.html" target="_blank">http://www.dou.co/license.html</a><em>（您可以免费使用DouPHP（不限商用），但必须保留相关版权信息。）</em></li>
     </ul>
    </div>
   </div>
   <?php endif; ?>
  </div>
 </div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
</body>
</html>