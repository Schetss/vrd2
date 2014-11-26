{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$immo.title}: {$lblMedia}</h2>
</div>

<div class="tabs">
		<ul>
			<li><a href="#tabImages">{$lblImages|ucfirst}</a></li>
			<li><a href="#tabFiles">{$lblFiles|ucfirst}</a></li>
			<li><a href="#tabVideos">{$lblVideos|ucfirst}</a></li>
		</ul>
		
		<div id="tabImages">
     {option:showImmoAddImage}
			<div class="buttonHolderRight">
				<a href="{$var|geturl:'add_image'}&amp;immo_id={$immo.id}" class="button icon iconAdd" title="{$lblAddImage|ucfirst}">
					<span>{$lblAddImage|ucfirst}</span>
				</a>
			</div>
			{/option:showImmoAddImage}
      
      <div class="seperator">&nbsp;</div>
      
      <div id="dataGridImmoImagesHolder">
        {option:dataGridImages}
          <div class="dataGridImagesHolder">
            <form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
            <fieldset>
              <input type="hidden" name="immo_id" value="{$immo.id}" />
              {$dataGridImages}
            </fieldset>
            </form>
          </div>
        {/option:dataGridImages}
      </div>
      {option:!dataGridImages}<p>{$msgNoImmoImages}</p>{/option:!dataGridImages}
		</div>
    
		<div id="tabFiles">
     <!-- change option name to showImmoAddFile -->
     {option:showImmoAddImage}
			<div class="buttonHolderRight">
				<a href="{$var|geturl:'add_file'}&amp;immo_id={$immo.id}" class="button icon iconAdd" title="{$lblAddFile|ucfirst}">
					<span>{$lblAddFile|ucfirst}</span>
				</a>
			</div>
			{/option:showImmoAddImage}
      
      <div class="seperator">&nbsp;</div>
      
      <div id="dataGridImmoFilesHolder">
        {option:dataGridFiles}
          <div class="dataGridFilesHolder">
            <form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
            <fieldset>
              <input type="hidden" name="immo_id" value="{$immo.id}" />
              {$dataGridFiles}
            </fieldset>
            </form>
          </div>
        {/option:dataGridFiles}
      </div>
      {option:!dataGridFiles}<p>{$msgNoImmoFiles}</p>{/option:!dataGridFiles}
    </div>
    
    <div id="tabVideos">
     <!-- change option name to showImmoAddVideo -->
     {option:showImmoAddImage}
			<div class="buttonHolderRight">
				<a href="{$var|geturl:'add_video'}&amp;immo_id={$immo.id}" class="button icon iconAdd" title="{$lblAddVideo|ucfirst}">
					<span>{$lblAddVideo|ucfirst}</span>
				</a>
			</div>
			{/option:showImmoAddImage}
      
      <div class="seperator">&nbsp;</div>
      
      <div id="dataGridImmoVideosHolder">
        {option:dataGridVideos}
          <div class="dataGridVideosHolder">
            <form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
            <fieldset>
              <input type="hidden" name="immo_id" value="{$immo.id}" />
              {$dataGridVideos}
            </fieldset>
            </form>
          </div>
        {/option:dataGridVideos}
      </div>
      {option:!dataGridVideos}<p>{$msgNoImmoVideos}</p>{/option:!dataGridVideos}
    </div>
</div>

<div class="fullwidthOptions">
	<a href="{$var|geturl:'index'}" class="button">
		<span>{$lblBackToOverview|ucfirst}</span>
	</a>
</div>

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}