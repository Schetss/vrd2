{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblImmo|ucfirst}: {$lblImages}</h2>

	{option:showImmoAddImage}
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add_image'}&amp;immo_id={$immo.id}" class="button icon iconAdd" title="{$lblAddImage|ucfirst}">
			<span>{$lblAddImage|ucfirst}</span>
		</a>
	</div>
	{/option:showImmoAddImage}
</div>

<div id="dataGridImmoHolder">
	{option:dataGrid}
		<div class="dataGridHolder">
			<form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
			<fieldset>
				<input type="hidden" name="immo_id" value="{$immo.id}" />
				{$dataGrid}
			</fieldset>
			</form>
		</div>
	{/option:dataGrid}
</div>
{option:!dataGrid}<p>{$msgNoItems}</p>{/option:!dataGrid}

<div class="fullwidthOptions">
	<a href="{$var|geturl:'index'}" class="button">
		<span>{$lblBackToOverview|ucfirst}</span>
	</a>
</div>

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}