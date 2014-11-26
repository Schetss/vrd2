<div class="jobs jobs-detail">
	<article>
		<div class="hd">
			<h1 itemprop="name">{$item.title}</h1>
			<p class="date">
				<time datetime="{$item.date|date:'c':{$LANGUAGE}}">
				<small>{$item.created_on|timeago}</small>
				{$item.date|date:'d':{$LANGUAGE}} {$item.date|date:'F':{$LANGUAGE}} {$item.date|date:'Y':{$LANGUAGE}}
				</time>
			</p>
			<p class="client">
				{$lblClient|ucfirst}
				<a href="{$item.client_full_url}" title="{$item.client_title}"><b>{$item.client_title}</b></a>
			</p>
			<p class="category">
				{$lblCategory|ucfirst}
				<a href="{$item.category_full_url}" title="{$item.category_title}"><b>{$item.category_title}</b></a>
			</p>
		</div>
		<div class="bd">
			<p>{$item.text}</p>
			{option:images}
			<div class="jobImages">
				<h3>{$lblImages|ucfirst}</h3>
				{iteration:images}
					<a class="colorbox" rel="group1" href="{$images.sizes.large}" title="{$images.title}">
						<img src="{$images.sizes.small}" alt="{$images.title}" title="{$images.title}" />
					</a>
				{/iteration:images}
				{iteration:videos}
					<a class="fancybox fancybox.iframe" rel="gallery" href="{$videos.url}">
						<img src="{$videos.image}" alt="{$videos.title}" title="{$videos.title}">
					</a>
				{/iteration:videos}
			</div>
			{/option:images}
			
		</div>
	</article>
	<div>
		<a href="{$var|geturlforblock:'Jobs'}" title="{$msgToJobsOverview|ucfirst}">{$msgToJobsOverview|ucfirst}</a>
	</div>
</div>
