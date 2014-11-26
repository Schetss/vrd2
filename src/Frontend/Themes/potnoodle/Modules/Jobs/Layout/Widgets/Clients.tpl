{*
	variables that are available:
	- {$widgetJobsClients}: contains an array with the job categories
*}

{option:widgetJobsClients}
<section id="jobsClientsWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblClients|ucfirst}</h3>
		</header>
		<div class="bd content">
			<ul>
				{iteration:widgetJobsClients}
					<li><a href="{$widgetJobsClients.full_url}">{$widgetJobsClients.title}</a></li>
				{/iteration:widgetJobsClients}
			</ul>
		</div>
	</div>
</section>
{/option:widgetJobsClients}

