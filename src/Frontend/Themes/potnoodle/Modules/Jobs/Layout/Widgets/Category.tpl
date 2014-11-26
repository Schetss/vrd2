<!--{$widgetJobsInCategory|dump}-->

{option:widgetJobsInCategory}
<div class="jobsContainer">
	{iteration:widgetJobsInCategory}
		<article class="block summary {$widgetJobsInCategory.category_url}">
			<a href="{$widgetJobsInCategory.full_url}" title="{$widgetJobsInCategory.title}" class="summaryContent">
				<div class="inner">
					<header class="summaryContentText autoEllipsis">
						<h1 class="h2">{$widgetJobsInCategory.title|ucfirst}</h1>
						<img src="{$widgetJobsInCategory.image}" title="{$widgetJobsInCategory.title}" alt="{$widgetJobsInCategory.title}" class="jobImage"/>
						<p class="date"><time datetime="{$widgetJobsInCategory.date|date:'d':{$LANGUAGE}}{$widgetJobsInCategory.date|date:'F':{$LANGUAGE}}{$widgetJobsInCategory.date|date:'Y':{$LANGUAGE}}">{$widgetJobsInCategory.date|date:'d':{$LANGUAGE}} {$widgetJobsInCategory.date|date:'F':{$LANGUAGE}} {$widgetJobsInCategory.date|date:'Y':{$LANGUAGE}}</time></p>
						{$widgetJobsInCategory.introduction}
					</header>
				</div>
			</a>
			<footer class="summaryFooter">
				<a href="{$widgetJobsInCategory.category_full_url}" class="textlink category">{$widgetJobsInCategory.category_title}</a>
			</footer>
		</article>
	{/iteration:widgetJobsInCategory}
</div>
{include:core/layout/templates/pagination.tpl}
{/option:widgetJobsInCategory}

{option:!widgetJobsInCategory}
	<p>{$lblNoJobsInCategory}</p>
{/option:!widgetJobsInCategory}
