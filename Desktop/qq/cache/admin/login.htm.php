<?php /* Smarty version 2.6.26, created on 2019-05-19 16:07:06
         compiled from login.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
<meta name="Copyright" content="Douco Design." />
<link href="templates/public.css" rel="stylesheet" type="text/css">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "javascript.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>
<div id="login">
  <div class="logo"></div>
  <?php if ($this->_tpl_vars['rec'] == 'default'): ?>
  <form action="login.php?rec=login" method="post">
   <div class="form">
    <div class="basicBox">
     <dl>
      <dt><?php echo $this->_tpl_vars['lang']['login_user_name']; ?>
</dt>
      <dd><input type="text" name="user_name" class="input"></dd>
     </dl>
     <dl>
      <dt><?php echo $this->_tpl_vars['lang']['login_password']; ?>
</dt>
      <dd><input type="password" name="password" class="input"></dd>
     </dl>
    </div>
    <div class="captchaBox">
     <?php if ($this->_tpl_vars['site']['captcha']): ?>
     <dl class="captcha">
      <dt><?php echo $this->_tpl_vars['lang']['login_captcha']; ?>
</dt>
      <dd><input type="text" name="captcha" class="input"></dd>
     </dl>
     <img id="vcode" src="../captcha.php" class="captchaImg" alt="<?php echo $this->_tpl_vars['lang']['captcha']; ?>
" border="1" onClick="refreshimage()" title="<?php echo $this->_tpl_vars['lang']['login_captcha_refresh']; ?>
">
     <?php endif; ?>
    </div>
    <div class="btnBox">
     <input type="submit" name="submit" class="btn" value="<?php echo $this->_tpl_vars['lang']['login_submit']; ?>
">
     <div class="action"><?php echo $this->_tpl_vars['lang']['login_password_forget']; ?>
？<a href="login.php?rec=password_reset"><?php echo $this->_tpl_vars['lang']['login_password_reset_action']; ?>
</a></div> 
    </div>
    <div class="linkBox">
     <a href="http://www.dou.co/license.html" target="_blank">使用协议</a> <a href="<?php echo $this->_tpl_vars['site']['root_url']; ?>
"><?php echo $this->_tpl_vars['lang']['login_back']; ?>
</a>
    </div>
   </div>
  </form>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['rec'] == 'password_reset'): ?>
  <form action="login.php?rec=password_reset_post" method="post">
   <div class="form">
    <?php if ($this->_tpl_vars['action'] == 'default'): ?>
    <div class="basicBox">
     <dl>
      <dt><?php echo $this->_tpl_vars['lang']['login_user_name']; ?>
</dt>
      <dd><input type="text" name="user_name" class="input"></dd>
     </dl>
     <dl>
      <dt><?php echo $this->_tpl_vars['lang']['login_email']; ?>
</dt>
      <dd><input type="text" name="email" class="input"></dd>
     </dl>
    </div>
    <div class="btnBox">
     <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
     <input type="submit" name="submit" class="btn" value="<?php echo $this->_tpl_vars['lang']['login_password_reset']; ?>
">
    </div>
    <?php elseif ($this->_tpl_vars['action'] == 'reset'): ?>
    <div class="basicBox">
     <dl>
      <dt><?php echo $this->_tpl_vars['lang']['manager_new_password']; ?>
</dt>
      <dd><input type="password" name="password" class="input"></dd>
     </dl>
     <dl>
      <dt><?php echo $this->_tpl_vars['lang']['manager_new_password_confirm']; ?>
</dt>
      <dd><input type="password" name="password_confirm" class="input"></dd>
     </dl>
    </div>
    <div class="btnBox">
     <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
     <input type="hidden" name="code" value="<?php echo $this->_tpl_vars['code']; ?>
" />
     <input type="hidden" name="action" value="reset" />
     <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" />
     <input type="submit" name="submit" class="btn" value="<?php echo $this->_tpl_vars['lang']['btn_submit']; ?>
">
    </div>
    <?php endif; ?>
    <div class="action"><a href="login.php"><?php echo $this->_tpl_vars['lang']['login_submit']; ?>
</a></div>
    <div class="linkBox">
     <a href="http://www.dou.co/license.html" target="_blank"><?php echo $this->_tpl_vars['lang']['license']; ?>
</a> <a href="<?php echo $this->_tpl_vars['site']['root_url']; ?>
"><?php echo $this->_tpl_vars['lang']['login_back']; ?>
</a>
    </div>
   </div>
  </form>
  <?php endif; ?>
</div>
</body>
</html>