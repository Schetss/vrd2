{*
	variables that are available:
	- {$widgetImmoCategories}: contains an array with the Immo categories
*}

{option:widgetImmoCategories}
<section id="ImmoCategoriesWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblCategories|ucfirst}</h3>
		</header
		<div class="bd content">
			<ul>
				{iteration:widgetImmoCategories}
					<li><a href="{$widgetImmoCategories.full_url}">{$widgetImmoCategories.title}</a></li>
				{/iteration:widgetImmoCategories}
			</ul>
		</div>
	</div>
</section>
{/option:widgetImmoCategories}

