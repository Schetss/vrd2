{*
	variables that are available:
	- {$widgetImmoCategories}: contains an array with the Immo categories
*}

{option:widgetImmoCategories}
<section id="ImmoCategoriesWidget" class="mod">
	<nav class="immo-nav">
		<ul>
			{iteration:widgetImmoCategories}
				<li><a href="{$widgetImmoCategories.full_url}">{$widgetImmoCategories.title}</a></li>
			{/iteration:widgetImmoCategories}
		</ul>
	</nav>
</section>
{/option:widgetImmoCategories}

