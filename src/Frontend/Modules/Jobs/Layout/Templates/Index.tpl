{*
	variables that are available:
	- {$categories}: contains the categories which have jobs in it
	- {$clients}: contains all clients
	- {$jobs}: contains all jobs
*}

<div class="jobs jobs-index">
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

    {option:jobs}
        <div class="jobs">
            {iteration:jobs}
                <article>
                    <a href="{$jobs.full_url}" title="{$jobs.title}">
                        <div class="inner">
                            <header>
                                <h2 class="h2">{$jobs.title|ucfirst}</h2>
                                <p class="date"><time datetime="{$jobs.date|date:'d':{$LANGUAGE}}{$jobs.date|date:'F':{$LANGUAGE}}{$jobs.date|date:'Y':{$LANGUAGE}}">{$jobs.date|date:'d':{$LANGUAGE}} {$jobs.date|date:'F':{$LANGUAGE}} {$jobs.date|date:'Y':{$LANGUAGE}}</time></p>
                                {$jobs.introduction}
                            </header>
                        </div>
                        {option:jobs.images}
                            <div class="images clearfix">
                                {iteration:jobs.images}
                                    <img src="{$jobs.images.sizes.small}" alt="{$jobs.images.title}" title="{$jobs.images.title}" />
                                {/iteration:jobs.images}
                            </div>
                        {/option:jobs.images}
                    </a>
                </article>
            {/iteration:jobs}
        </div>
        {include:Core/Layout/Templates/Pagination.tpl}
    {/option:jobs}

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