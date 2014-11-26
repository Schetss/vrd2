{*
	variables that are available:
	- {$widgetImmoSpotlight}: contains an array with a spotlight immo
*}

{option:widgetImmoSpotlight}
<section class="immo">
	<article class="full article">
		<div class="centered articleContent plain">
			<header class="hd">
				<h3><a href="{$LANGUAGE}/{$lblAboutWeMetalLink|lowercase}/{$lblImmo|lowercase}" title="">{$lblImmo|ucfirst}</a></h3>
				<h4><a title="{$widgetImmoSpotlight.title}" href="{$widgetImmoSpotlight.full_url}">{$widgetImmoSpotlight.title}</a></h4>
			</header>
				{$widgetImmoSpotlight.introduction}
			<div class="bd content">
			
			</div>
		</div>
		<a class="readmore" title="{$widgetImmoSpotlight.title}" href="{$widgetImmoSpotlight.full_url}">
			{$lblMoreImmo}
		</a>
	</article>
</section>
{/option:widgetImmoSpotlight}
