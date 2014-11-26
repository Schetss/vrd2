{*
	variables that are available:
	- {$contactsItems}: contains data about all contacts
*}

{option:!contactsItems}
	<div id="contactsIndex">
		<div class="mod">
			<div class="inner">
				<div class="bd">
					<p>{$msgContactsNoItems}</p>
				</div>
			</div>
		</div>
	</div>
{/option:!contactsItems}
{option:contactsItems}
	<div id="contactsIndex">
		{iteration:contactsItems}
			<div class="mod contact">
				<div class="inner">
					<blockquote class="bd">{$contactsItems.contact}</blockquote>
					<p class="name">{$contactsItems.name}</p>
				</div>
			</div>
		{/iteration:contactsItems}
	</div>
{/option:contactsItems}