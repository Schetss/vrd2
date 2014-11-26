{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblContacts|ucfirst}: {$lblAdd}</h2>
</div>

{form:add}
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
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblAdd|ucfirst}" />
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}