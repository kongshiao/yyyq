<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div id="top">
 <div class="wrap">
  <ul class="topNav">
   <!-- {foreach from=$nav_top_list item=nav} --> 
   <!-- {if $nav.child} -->
   <li class="parent"><a href="{$nav.url}">{$nav.nav_name}</a>
    <ul>
     <!-- {foreach from=$nav.child item=child} -->
     <li><a href="{$child.url}">{$child.nav_name}</a></li>
     <!-- {/foreach} -->
    </ul>
   </li>
   <li class="spacer"></li>
   <!-- {else} -->
   <li><a href="{$nav.url}"{if $nav.target} target="_blank"{/if}>{$nav.nav_name}</a></li>
   <li class="spacer"></li>
   <!-- {/if} --> 
   <!-- {/foreach} -->
   <li><a href="javascript:AddFavorite('{$site.root_url}', '{$site.site_name}')">{$lang.add_favorite}</a></li>
   <!-- {if $open.user} --> 
   <!-- {if $global_user} -->
   <li class="spacer"></li>
   <li><a href="{$url.user}">{$global_user.user_name}，{$lang.user_welcom_top}</a></li>
   <li class="spacer"></li>
   <li><a href="{$url.logout}">{$lang.user_logout}</a></li>
   <!-- {else} -->
   <li class="spacer"></li>
   <li><a href="{$url.login}">{$lang.user_login_nav}</a></li>
   <li class="spacer"></li>
   <li><a href="{$url.register}">{$lang.user_register_nav}</a></li>
   <!-- {/if} --> 
   <!-- {/if} -->
  </ul>
  <ul class="search">
   <div class="searchBox">
    <form name="search" id="search" method="get" action="{$site.root_url}">
     <input name="s" type="text" class="keyword" value="{if $keyword}{$keyword|escape}{else}{$lang.search_product}{/if}" onclick="inputClick(this, '{$lang.search_product}')" size="25" autocomplete="off">
     <input type="submit" class="btnSearch" value="{$lang.btn_submit}">
    </form>
   </div>
  </ul>
 </div>
</div>
<div id="header">
 <div class="wrap">
  <ul class="logo">
   <a href="{$site.root_url}"><img src="../images/{$site.site_logo}" alt="{$site.site_name}" title="{$site.site_name}" height="55" /></a>
  </ul>
  <ul class="mainNav">
   <li class="m"><a href="{$site.root_url}" class="nav{if $index.cur} cur{/if}">{$lang.home}</a></li>
   <!-- {foreach from=$nav_middle_list name=nav_middle_list item=nav} -->
   <li class="m"><a href="{$nav.url}" class="nav{if $nav.cur} cur{/if}"{if $nav.target} target="_blank"{/if}>{$nav.nav_name}</a> 
    <!-- {if $nav.child} -->
    <ul>
     <!-- {foreach from=$nav.child item=child} -->
     <li><a href="{$child.url}" class="child{if $child.child} parent{/if}">{$child.nav_name}</a> 
      <!-- {if $child.child} -->
      <ul>
       <!-- {foreach from=$child.child item=children} -->
       <li><a href="{$children.url}" class="children">{$children.nav_name}</a></li>
       <!-- {/foreach} -->
      </ul>
      <!-- {/if} --> 
     </li>
     <!-- {/foreach} -->
    </ul>
    <!-- {/if} --> 
   </li>
   <!-- {/foreach} -->
   <p class="clear"></p>
  </ul>
  <p class="clear"></p>
 </div>
</div>
