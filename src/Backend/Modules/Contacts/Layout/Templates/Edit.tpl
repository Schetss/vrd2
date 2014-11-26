{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblContacts|ucfirst}: {$msgEdit|sprintf:{$item.name}}</h2>
</div>

{form:edit}
	<p>
		<label for="title">{$lblName|ucfirst}</label>
		{$txtName} {$txtNameError}
	</p>

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td id="leftColumn">

			<div class="box">
				<div class="heading">
					<h3>{$lblContact|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></h3>
				</div>
				<div class="optionsRTE">
					{$txtContact} {$txtContactError}
				</div>
			</div>

			</td>

			<td id="sidebar">

				<div id="publishOptions" class="box">
					<div class="heading">
						<h3>{$lblStatus|ucfirst}</h3>
					</div>

					<div class="options">
						<ul class="inputList">
							{iteration:hidden}
								<li>
									{$hidden.rbtHidden}
									<label for="{$hidden.id}">{$hidden.label}</label>
								</li>
							{/iteration:hidden}
						</ul>
					</div>
				</div>

			</td>
		</tr>
	</table>

	<div class="fullwidthOptions">
		{option:showTesimonialsDelete}
		<a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
			<span>{$lblDelete|ucfirst}</span>
		</a>
		<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
			<p>
				{$msgConfirmDelete|sprintf:{$item.name}}
			</p>
		</div>
		{/option:showTesimonialsDelete}

		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblPublish|ucfirst}" />
		</div>
	</div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}