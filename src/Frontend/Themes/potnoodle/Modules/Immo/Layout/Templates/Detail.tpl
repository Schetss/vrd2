<div class="immo immo-detail row content">
	<article>
		<div class="bd">
			<p>{$item.text}</p>

			 <div class="mail-btn">
                <a class="mail-btn-link" href="mailto:raoul@vrd.be?Subject=Offerte:%20{$item.title}" target="_top">Vraag nu een offerte aan</a>
            </div>
            

			<div id="flexslider-main">
				<span class="flexbox"></span>
					{option:item.images}
						{iteration:images}
						<img class="immo-head-img" src="{$images.sizes.large}"/>
						{/iteration:images}
					{/option:item.images}
			</div>

			
			{option:images}
			<div class="flexslider">
				<ul class="slides">
					{iteration:images}
					<li>
						<img src="{$images.sizes.large}" id="slide-img-{$images.id}" class="slide" alt="{$images.title}" title="{$images.title}" />
					</li>
					{/iteration:images}
				</ul>
			</div>
			

			<div class="immo-videos">
				{iteration:videos}
					<a class="fancybox fancybox.iframe" rel="gallery" href="{$videos.url}">
						<img src="{$videos.image}" alt="{$videos.title}" title="{$videos.title}">
					</a>
				{/iteration:videos}
			</div>
			<div class="clear"></div>
			{/option:images}

			<div class="divider"></div>
			{option:related}
                <div class="relatedImmo">
                    <h3>{$lblRelatedImmo|ucfirst}</h3>
                    {iteration:related}
                        <div class="relatedImmo">
                            <small><a href="{$related.url}">{$related.title}</a></small>
                            <a href="{$related.url}">
                                <img src="{$related.image}" alt="{$related.title}" title="{$related.title}" />
                            </a>
                        </div>
                    {/iteration:related}
                </div>
			{/option:related}

            
            
		</div>

		<footer>
			<a href="{$var|geturlforblock:'Immo'}" title="{$msgToImmoOverview|ucfirst}">{$msgToImmoOverview|ucfirst}</a>
		</footer>
	</article>
</div>



