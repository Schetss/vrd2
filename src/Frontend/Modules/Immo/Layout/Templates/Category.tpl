{*
	variables that are available:
	- {$categories}: contains the categories which have immo in it
	- {$clients}: contains all clients
	- {$immo}: contains all immo
*}

<div class="immo immo-index">
    <div class="category">
    <h2>{$lblCategory|ucfirst}: {$category.title}</h2>
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
    </div>
</div>
