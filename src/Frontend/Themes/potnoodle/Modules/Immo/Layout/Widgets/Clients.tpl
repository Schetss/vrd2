{*
	variables that are available:
	- {$widgetImmoClients}: contains an array with the Immo categories
*}

{option:widgetImmoClients}
<section id="immoClientsWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblClients|ucfirst}</h3>
		</header>
		<div class="bd content">
			<ul>
				{iteration:widgetImmoClients}
					<li><a href="{$widgetImmoClients.full_url}">{$widgetImmoClients.title}</a></li>
				{/iteration:widgetImmoClients}
			</ul>
		</div>
	</div>
</section>
{/option:widgetImmoClients}

