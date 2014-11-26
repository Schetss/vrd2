{*
	variables that are available:
	- {$categories}: contains the categories which have immo in it
	- {$clients}: contains all clients
	- {$immo}: contains all immo
*}

<div class="immo immo-index">
    {option:!categories}
        <p>{$msgNoCategories|ucfirst}</p>
    {/option:!categories}
    
    {option:categories}
    <div class="categories">
	<h2>{$lblCategories|ucfirst}:</h2>
	<ul>
	    {iteration:categories}
	    <li><a href="{$categories.full_url}" title="{$categories.title}">{$categories.title|ucfirst}</a></li>
	    {/iteration:categories}
	</ul>
    </div>
    {/option:categories}

    {option:immo}
        <div class="immo">
            {iteration:immo}
                <article>
                    <a href="{$immo.full_url}" title="{$immo.title}">
                        <div class="inner">
                            <header>
                                <h2 class="h2">{$immo.title|ucfirst}</h2>
                                <p class="date"><time datetime="{$immo.date|date:'d':{$LANGUAGE}}{$immo.date|date:'F':{$LANGUAGE}}{$immo.date|date:'Y':{$LANGUAGE}}">{$immo.date|date:'d':{$LANGUAGE}} {$immo.date|date:'F':{$LANGUAGE}} {$immo.date|date:'Y':{$LANGUAGE}}</time></p>
                                {$immo.introduction}
                            </header>
                        </div>
                        {option:immo.images}
                            <div class="images clearfix">
                                {iteration:immo.images}
                                    <img src="{$immo.images.sizes.small}" alt="{$immo.images.title}" title="{$immo.images.title}" />
                                {/iteration:immo.images}
                            </div>
                        {/option:immo.images}
                    </a>
                </article>
            {/iteration:immo}
        </div>
        {include:Core/Layout/Templates/Pagination.tpl}
    {/option:immo}

    {option:clients}
        <div class="clients">
	    <h2>{$lblClients|ucfirst}:</h2>
	    <ul>
		{iteration:clients}
		<li>
		    <a class=title" href="{$clients.full_url}">{$clients.title}</a>
		    {option:clients.image}
                    <a class="image" href="{$clients.full_url}">
                        <img src="{$clients.image}" title="{$clients.title}" alt="{$clients.title}" class="clientImage"/>
                    </a>
		    {/option:clients.image}
		</li>
		{/iteration:clients}
	    </ul>
        </div>
    {/option:clients}
</div>