{*
	variables that are available:
	- {$client}: contains the client
	- {$immo}: contains all immo
*}

<div class="immo immo-client">
    {option:client}
        <h2>{$lblClient|ucfirst}: {$client.title}</h2>
	
	{option:client.image}
	    <img src="{$client.image}" alt="{$client.title}"/>
	{/option:client.image}
       
        {option:immo}
            <div class="immo-list">
                {iteration:immo}
                    <article>
                            <div class="inner">
                                <header>
                                    <h1 class="h2"><a href="{$immo.full_url}" title="{$immo.title}">{$immo.title|ucfirst}</a></h1>
                                    {option:immo.images}
					{iteration:immo.images}
					    <img src="{$immo.images.sizes.small}" title="{$immo.title}" alt="{$immo.title}"/>
					{/iteration:immo.images}
				    {/option:immo.images}
                                    <p class="date">
                                        <time datetime="{$immo.date|date:'d':{$LANGUAGE}}{$immo.date|date:'F':{$LANGUAGE}}{$immo.date|date:'Y':{$LANGUAGE}}">{$immo.date|date:'d':{$LANGUAGE}} {$immo.date|date:'F':{$LANGUAGE}} {$immo.date|date:'Y':{$LANGUAGE}}</time>
                                    </p>
                                    {$immo.introduction}
                                </header>
                            </div>
                    </article>
                {/iteration:immo}
                <div class="ft">
                    <p>
                        <a href="{$var|geturlforblock:'Immo'}"title="{$msgToImmoOverview|ucfirst}">{$msgToImmoOverview|ucfirst}</a>
                    </p>
                </div>
            </div>
        {/option:immo}

    {/option:client}
</div>
