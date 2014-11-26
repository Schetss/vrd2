{*
	variables that are available:
	- {$client}: contains the client
	- {$jobs}: contains all jobs
*}

<div class="jobs jobs-client">
    {option:client}
        <h2>{$lblClient|ucfirst}: {$client.title}</h2>
	
	{option:client.image}
	    <img src="{$client.image}" alt="{$client.title}"/>
	{/option:client.image}
       
        {option:jobs}
            <div class="job-list">
                {iteration:jobs}
                    <article>
                            <div class="inner">
                                <header>
                                    <h1 class="h2"><a href="{$jobs.full_url}" title="{$jobs.title}">{$jobs.title|ucfirst}</a></h1>
                                    {option:jobs.images}
					{iteration:jobs.images}
					    <img src="{$jobs.images.sizes.small}" title="{$jobs.title}" alt="{$jobs.title}"/>
					{/iteration:jobs.images}
				    {/option:jobs.images}
                                    <p class="date">
                                        <time datetime="{$jobs.date|date:'d':{$LANGUAGE}}{$jobs.date|date:'F':{$LANGUAGE}}{$jobs.date|date:'Y':{$LANGUAGE}}">{$jobs.date|date:'d':{$LANGUAGE}} {$jobs.date|date:'F':{$LANGUAGE}} {$jobs.date|date:'Y':{$LANGUAGE}}</time>
                                    </p>
                                    {$jobs.introduction}
                                </header>
                            </div>
                    </article>
                {/iteration:jobs}
                <div class="ft">
                    <p>
                        <a href="{$var|geturlforblock:'Jobs'}"title="{$msgToJobsOverview|ucfirst}">{$msgToJobsOverview|ucfirst}</a>
                    </p>
                </div>
            </div>
        {/option:jobs}

    {/option:client}
</div>
