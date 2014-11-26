{*
	variables that are available:
	- {$widgetContactsRandomContact}: contains data about a (random) contacts
*}

<div class="randomContact">
	{option:widgetContactsRandomContact}
		<div class="mod contact">
			<div class="inner">
				<blockquote class="bd">{$widgetContactsRandomContact.contact}</blockquote>
				<p class="name">{$widgetContactsRandomContact.name}</p>
			</div>
		</div>
	{/option:widgetContactsRandomContact}
</div>
