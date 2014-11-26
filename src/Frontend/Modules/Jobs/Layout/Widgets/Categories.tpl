{*
	variables that are available:
	- {$widgetJobsCategories}: contains an array with the job categories
*}

{option:widgetJobsCategories}
<section id="jobsCategoriesWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblCategories|ucfirst}</h3>
		</header>
		<div class="bd content">
			<ul>
				{iteration:widgetJobsCategories}
					<li><a href="{$widgetJobsCategories.full_url}">{$widgetJobsCategories.title}</a></li>
				{/iteration:widgetJobsCategories}
			</ul>
		</div>
	</div>
</section>
{/option:widgetJobsCategories}

