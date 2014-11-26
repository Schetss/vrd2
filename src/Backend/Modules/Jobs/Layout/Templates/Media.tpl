{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$job.title}: {$lblMedia}</h2>
</div>

<div class="tabs">
		<ul>
			<li><a href="#tabImages">{$lblImages|ucfirst}</a></li>
			<li><a href="#tabFiles">{$lblFiles|ucfirst}</a></li>
			<li><a href="#tabVideos">{$lblVideos|ucfirst}</a></li>
		</ul>
		
		<div id="tabImages">
     {option:showJobsAddImage}
			<div class="buttonHolderRight">
				<a href="{$var|geturl:'add_image'}&amp;job_id={$job.id}" class="button icon iconAdd" title="{$lblAddImage|ucfirst}">
					<span>{$lblAddImage|ucfirst}</span>
				</a>
			</div>
			{/option:showJobsAddImage}
      
      <div class="seperator">&nbsp;</div>
      
      <div id="dataGridJobsImagesHolder">
        {option:dataGridImages}
          <div class="dataGridImagesHolder">
            <form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
            <fieldset>
              <input type="hidden" name="job_id" value="{$job.id}" />
              {$dataGridImages}
            </fieldset>
            </form>
          </div>
        {/option:dataGridImages}
      </div>
      {option:!dataGridImages}<p>{$msgNoJobsImages}</p>{/option:!dataGridImages}
		</div>
    
		<div id="tabFiles">
     <!-- change option name to showJobsAddFile -->
     {option:showJobsAddImage}
			<div class="buttonHolderRight">
				<a href="{$var|geturl:'add_file'}&amp;job_id={$job.id}" class="button icon iconAdd" title="{$lblAddFile|ucfirst}">
					<span>{$lblAddFile|ucfirst}</span>
				</a>
			</div>
			{/option:showJobsAddImage}
      
      <div class="seperator">&nbsp;</div>
      
      <div id="dataGridJobsFilesHolder">
        {option:dataGridFiles}
          <div class="dataGridFilesHolder">
            <form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
            <fieldset>
              <input type="hidden" name="job_id" value="{$job.id}" />
              {$dataGridFiles}
            </fieldset>
            </form>
          </div>
        {/option:dataGridFiles}
      </div>
      {option:!dataGridFiles}<p>{$msgNoJobsFiles}</p>{/option:!dataGridFiles}
    </div>
    
    <div id="tabVideos">
     <!-- change option name to showJobsAddVideo -->
     {option:showJobsAddImage}
			<div class="buttonHolderRight">
				<a href="{$var|geturl:'add_video'}&amp;job_id={$job.id}" class="button icon iconAdd" title="{$lblAddVideo|ucfirst}">
					<span>{$lblAddVideo|ucfirst}</span>
				</a>
			</div>
			{/option:showJobsAddImage}
      
      <div class="seperator">&nbsp;</div>
      
      <div id="dataGridJobsVideosHolder">
        {option:dataGridVideos}
          <div class="dataGridVideosHolder">
            <form action="{$var|geturl:'mass_action'}" method="get" class="forkForms submitWithLink" id="massAction">
            <fieldset>
              <input type="hidden" name="job_id" value="{$job.id}" />
              {$dataGridVideos}
            </fieldset>
            </form>
          </div>
        {/option:dataGridVideos}
      </div>
      {option:!dataGridVideos}<p>{$msgNoJobsVideos}</p>{/option:!dataGridVideos}
    </div>
</div>

<div class="fullwidthOptions">
	<a href="{$var|geturl:'index'}" class="button">
		<span>{$lblBackToOverview|ucfirst}</span>
	</a>
</div>

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}