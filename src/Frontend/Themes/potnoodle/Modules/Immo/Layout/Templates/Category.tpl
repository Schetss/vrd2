{*
    variables that are available:
    - {$categories}: contains the categories which have immo in it
    - {$clients}: contains all clients
    - {$immo}: contains all immo
*}

<div class="immo immo-index">
       {option:immo}
        <div class="immo">
            {iteration:immo}
                <article>
                    <div class="line-title">
                        <hr />
                        <div class="row">
                            <h3><span class="line-title-text">{$immo.title|ucfirst}</span></h3>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="row">
                    
                        {option:immo.images}
                            <div class="images clearfix">
                                {iteration:immo.images}
                                <a href="{$immo.full_url}" title="{$immo.title}">
                                    <img src="{$immo.images.sizes.large}" alt="{$immo.images.title}" title="{$immo.images.title}" /></a>
                                {/iteration:immo.images}
                                 <div class="immo-btn">
                                    <a href="{$immo.full_url}" title="{$immo.title}">Meer informatie</a>
                                </div>
                            </div>
                        {/option:immo.images}
                        
                        <div class="immo-intro">    
                            {$immo.introduction}
                        </div>
                    </div>

                </article>
            {/iteration:immo}
        </div>
        {include:Core/Layout/Templates/Pagination.tpl}
    {/option:immo}

</div>