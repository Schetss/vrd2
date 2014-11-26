{*
	variables that are available:
	- {$widgetJobsSpotlight}: contains an array with a spotlight job
*}

{option:widgetJobsSpotlight}
<section class="jobs">
	<article class="full article">
		<div class="centered articleContent plain">
			<header class="hd">
				<h3><a href="{$LANGUAGE}/{$lblAboutWeMetalLink|lowercase}/{$lblJobs|lowercase}" title="">{$lblJobs|ucfirst}</a></h3>
				<h4><a title="{$widgetJobsSpotlight.title}" href="{$widgetJobsSpotlight.full_url}">{$widgetJobsSpotlight.title}</a></h4>
			</header>
				{$widgetJobsSpotlight.introduction}
			<div class="bd content">
			
			</div>
		</div>
		<a class="readmore" title="{$widgetJobsSpotlight.title}" href="{$widgetJobsSpotlight.full_url}">
			{$lblMoreJobs}
		</a>
	</article>
</section>
{/option:widgetJobsSpotlight}
