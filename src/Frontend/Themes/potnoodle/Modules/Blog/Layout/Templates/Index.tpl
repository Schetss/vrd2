{*
	variables that are available:
	- {$items}: contains an array with all posts, each element contains data about the post
*}
<div class="row article-index">
<section >
	{iteration:items}
			<article class="article">
				<div class="left">
					<div class="article-image">
						<a href="{$items.full_url}">
							{option:items.image}<img src="{$FRONTEND_FILES_URL}/blog/images/source/{$items.image}" alt="{$items.title}" />{/option:items.image}
						</a>
					</div>
				</div>


				<div class="right">
					<header>
						<h2><a href="{$items.full_url}" title="{$items.title}">{$items.title}</a></h2>
						<p class="date">
							{$items.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}
						</p>
					</header>
					<div>
						{option:!items.introduction}{$items.text}{/option:!items.introduction}
						{option:items.introduction}{$items.introduction}{/option:items.introduction}
						<a class="readmore" href="{$items.full_url}">Lees meer</a>
					</div>
				</div>
				<div class="clear"></div>
			</article>
		{/iteration:items}



	{include:Core/Layout/Templates/Pagination.tpl}
</section>
</div>