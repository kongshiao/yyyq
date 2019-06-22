<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- {if $recommend_product} -->
<div class="recProduct">
 <h3><em>{$lang.product_news}</em><a href="{$url.product}" class="more">{$lang.product_more}</a></h3>
 <div class="list">
  <!-- {foreach from=$recommend_product name=recommend_product item=product} -->
  <dl{if $smarty.foreach.recommend_product.iteration % 4 eq 0} class="noMargin"{/if}>
   <dd class="img"><a href="{$product.url}"><img src="{$product.thumb}" /></a></dd>
   <dt><a href="{$product.url}">{$product.name}</a></dt>
   <dd class="price">{$product.price}</dd>
  </dl>
  <!-- {/foreach} -->
 </div>
</div>
<!-- {/if} -->