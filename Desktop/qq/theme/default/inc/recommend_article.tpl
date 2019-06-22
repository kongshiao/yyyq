<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- {if $recommend_article} -->
<div class="recArticle">
 <h3><em>{$lang.article_news}</em><a href="{$url.article}" class="more">{$lang.article_more}</a></h3>
 <ul class="list">
  <!-- {foreach from=$recommend_article name=recommend_article item=article} -->
  <li{if $smarty.foreach.recommend_article.last} class="last"{/if}><b>{$article.add_time_short}</b><a href="{$article.url}">{$article.title|truncate:26:"..."}</a></li>
  <!-- {/foreach} -->
 </ul>
</div>
<!-- {/if} -->