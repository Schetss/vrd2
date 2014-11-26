{option:navigation}
	<ul>
		{iteration:navigation}
			<li{option:navigation.selected} class="selected"{/option:navigation.selected}>
				<a href="{$navigation.link}" title="{$navigation.navigation_title}"{option:navigation.nofollow} rel="nofollow"{/option:navigation.nofollow}>{$navigation.navigation_title}</a>
				{option:navigation.selected}<span class="main-nav-sub">{$navigation.children}</span>{/option:navigation.selected}
			</li>
		{/iteration:navigation}
    </ul>
{/option:navigation}