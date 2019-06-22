<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- {if $site.qq} -->
<script type="text/javascript" src="images/online_service.js"></script>
<div class="onlineService">
 <div class="onlineIcon">{$lang.online}</div>
 <div id="pop">
  <ul class="onlineQQ">
  <!-- {foreach from=$site.qq item=qq} -->
  <a href="http://wpa.qq.com/msgrd?v=3&uin={$qq.number}&site=qq&menu=yes" target="_blank"><!-- {if $qq.nickname} -->{$qq.nickname}<!-- {else} -->{$lang.online_qq}<!-- {/if} --></a>
  <!-- {/foreach} -->
  </ul>
  <ul class="service">
   <li>{$lang.contact_tel}<br />{$site.tel}</li>
   <li><a href="{$url.guestbook}">{$lang.guestbook_add}</a></li>
  </ul>
 </div>
 <p class="goTop"><a href="javascript:;" onfocus="this.blur();" class="goBtn"></a></p>
</div>
<!-- {/if} -->