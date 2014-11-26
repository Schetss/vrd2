<header class="holder header-holder">
	<div class="header-row">
		<div class="header">


			{* SEO logo: H1 for home, p for other pages *}

			<div class="inner-header">
				<div class="inner-inner-header row">
					<a href="{$SITE_URL}/en"><img class="logo retina" alt="{$siteTitle}" src="/src/Frontend/Core/Layout/images/logo.png" /></a>
	                <p class="slogan">Your partner in storage, distribution and lease!</p>

					{* Languages *}
					{option:languages}
						<nav class="lan-nav">
							{include:Core/Layout/Templates/Languages.tpl}
						</nav>
					{/option:languages}
	            </div>
	            <div class="clear"></div>
	        </div>

			{* Navigation *}
			<div class="row header-nav">
				<span class="main-nav-trigger">menu</span>
				<nav class="main-nav">
					{$var|getnavigation:'page':0:2}
				</nav>
				<div class="clear"></div>
			</div>

			{* Meta *}
			<nav>
				{$var|getnavigation:'meta':0:1}
			</nav>

		</div>
	</div>
	
</header>


