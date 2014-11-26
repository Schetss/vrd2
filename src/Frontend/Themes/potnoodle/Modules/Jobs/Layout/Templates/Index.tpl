{*
    variables that are available:
    - {$categories}: contains the categories which have jobs in it
    - {$clients}: contains all clients
    - {$jobs}: contains all jobs
*}

<div class="article-index row">
    {option:jobs}
        <div class="jobs">
            {iteration:jobs}
                <article class="article">
                    <div class="left">
                        <div class="article-image">
                             {option:jobs.images}
                                    {iteration:jobs.images}
                                        <img src="{$jobs.images.sizes.small}" alt="{$jobs.images.title}" title="{$jobs.images.title}" />
                                    {/iteration:jobs.images}
                            {/option:jobs.images}
                        </div>
                        <div class="mail-btn">
                            <a class="mail-btn-link" href="mailto:raoul@vrd.be?Subject=Sollicitatie:%20{$jobs.title|ucfirst}" target="_top">solliciteer nu</a>
                        </div>
                    </div>
                
                    <div class="right">
                        <header>
                            <h2 class="h2">{$jobs.title|ucfirst}</h2>
                          
                        </header>
                        
                        <div class="job-intro clear">
                            {$jobs.introduction}  
                        </div>
                        <div class="job-text clear">
                            {$jobs.text}
                        </div>
                    </div>
                   <div class="clear"></div>
                </article>
            {/iteration:jobs}
        </div>
        {include:Core/Layout/Templates/Pagination.tpl}
    {/option:jobs}
    {option:!jobs}   
         <p>{$msgNoJobs}</p>
    {/option:!jobs}

</div>