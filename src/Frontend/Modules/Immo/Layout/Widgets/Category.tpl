<!--{$widgetImmoInCategory|dump}-->

{option:widgetImmoInCategory}
<div class="ImmoContainer">
	{iteration:widgetImmoInCategory}
		<article class="block summary {$widgetImmoInCategory.category_url}">
			<a href="{$widgetImmoInCategory.full_url}" title="{$widgetImmoInCategory.title}" class="summaryContent">
				<div class="inner">
					<header class="summaryContentText autoEllipsis">
						<h1 class="h2">{$widgetImmoInCategory.title|ucfirst}</h1>
						<img src="{$widgetImmoInCategory.image}" title="{$widgetImmoInCategory.title}" alt="{$widgetImmoInCategory.title}" class="immoImage"/>
						<p class="date"><time datetime="{$widgetImmoInCategory.date|date:'d':{$LANGUAGE}}{$widgetImmoInCategory.date|date:'F':{$LANGUAGE}}{$widgetImmoInCategory.date|date:'Y':{$LANGUAGE}}">{$widgetImmoInCategory.date|date:'d':{$LANGUAGE}} {$widgetImmoInCategory.date|date:'F':{$LANGUAGE}} {$widgetImmoInCategory.date|date:'Y':{$LANGUAGE}}</time></p>
						{$widgetImmoInCategory.introduction}
					</header>
				</div>
			</a>
			<footer class="summaryFooter">
				<a href="{$widgetImmoInCategory.category_full_url}" class="textlink category">{$widgetImmoInCategory.category_title}</a>
			</footer>
		</article>
	{/iteration:widgetImmoInCategory}
</div>
{include:core/layout/templates/pagination.tpl}
{/option:widgetImmoInCategory}

{option:!widgetImmoInCategory}
	<p>{$lblNoImmoInCategory}</p>
{/option:!widgetImmoInCategory}
