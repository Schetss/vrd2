{*
	variables that are available:
	- {$categories}: contains the categories which have jobs in it
	- {$clients}: contains all clients
	- {$jobs}: contains all jobs
*}

<div class="jobs jobs-index">
    <div class="category">
    <h2>{$lblCategory|ucfirst}: {$category.title}</h2>
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
    </div>
</div>
