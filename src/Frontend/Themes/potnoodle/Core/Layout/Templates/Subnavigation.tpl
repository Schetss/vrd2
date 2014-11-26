{option:subnavigation}
	<ul>
		{iteration:subnavigation}
			<li{option:subnavigation.selected} class="selected"{/option:subnavigation.selected}>
				<a href="{$subnavigation.link}" title="{$subnavigation.subnavigation_title}"{option:subnavigation.nofollow} rel="nofollow"{/option:subnavigation.nofollow}>{$subnavigation.subnavigation_title}</a>
				{option:subnavigation.selected}{$subnavigation.children}{/option:subnavigation.selected}
			</li>
		{/iteration:subnavigation}
    </ul>
{/option:subnavigation}