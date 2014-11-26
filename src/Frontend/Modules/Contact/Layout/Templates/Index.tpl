{option:!items}
    <div id="contactIndex">
        <section class="mod">
            <div class="inner">
                <div class="bd content">
                    <p>{$msgContactNoItems}</p>
                </div>
            </div>
        </section>
    </div>
{/option:!items}

{option:items}
    <div id="contact-main">
        {iteration:items}
            <section class="contact-category">
                <header class="hd">
                    <h3>{$items.name}</h3>
                </header>

                {iteration:items.albums}
                    <div class="wrap {option:items.albums.first}first{/option:items.albums.first}">
                        <div class="album-thumbnail">
                            <a class="cover" href="{$items.albums.full_url}">
                                {option:items.albums.image}<img src="{$FRONTEND_FILES_URL}/Contact/images/150x150/{$items.albums.image}" alt="{$items.albums.title}">{/option:items.albums.image}
                                {option:!items.albums.image}<img src="{$FRONTEND_FILES_URL}/Contact/images/150x150/placeholder.png" alt="{$items.albums.title}">{/option:!items.albums.image}
                            </a>
                        </div>

                        <div class="album-label">
                            <div class="name"><a href="{$items.albums.full_url}" title="{$items.albums.title}">{$items.albums.title}</a></div>
                            <div class="year">{$items.albums.release_date|date:'Y'}</div>
                        </div>
                    </div>
                {/iteration:items.albums}
            </section>

        {option:!items.last} <hr> {/option:!items.last}
        {/iteration:items}
    </div>

    {include:core/layout/templates/pagination.tpl}
{/option:items}
