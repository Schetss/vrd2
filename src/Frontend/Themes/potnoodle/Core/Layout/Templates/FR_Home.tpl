{include:Core/Layout/Templates/Head.tpl}

<body class="{$LANGUAGE}" itemscope itemtype="http://schema.org/WebPage">
	<!--[if lt IE 8]>
		<div class="alert-box">
			<p>You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser to improve your experience.</p>
		</div>
	<![endif]-->

	{* Header *}
	{include:Core/Layout/Templates/FR_Header.tpl}
	

	<main id="main" class="holder main-holder" role="main">
		<div id="selector">
			<div class="selector-picture">
				<a href="/fr/l-entreprise/distribution">
					<p class="selector-name">
						Distribution
					</p>
					<img class="selector-img" src="/src/Frontend/Core/Layout/images/selector_1.jpg"/>

				</a>
			</div>

			<div class="selector-picture">
				<a href="/fr/l-entreprise/transport">
					<p class="selector-name">
						Transport
					</p>
					<img class="selector-img" src="/src/Frontend/Core/Layout/images/selector_2.jpg"/>
				</a>
			</div>


			<div class="selector-picture">
				<a href="/fr/l-entreprise/stockage">
					<p class="selector-name">
						Stockage <!-- &amp; Préparation de commandes -->
					</p>
					<img class="selector-img" src="/src/Frontend/Core/Layout/images/selector_3.jpg"/>
				</a>
			</div>


			<div class="selector-picture-right">
				<a href="/fr/l-entreprise/immo">
					<p class="selector-name">
						Immo
					</p>
					<img class="selector-img" src="/src/Frontend/Core/Layout/images/selector_4.jpg"/>
				</a>
			</div>

		</div>
	
		<div class="clear"></div>

		<div class="line-title">
			<hr />

			<div class="row">
				<h3><span class="line-title-text">VRD ne reste pas immobile!</span></h3>
			</div>
			<div class="clear"></div>
		</div>

		<div class="row">
			<div class="main">

				{* Main position *}
				{option:positionMain}
					{iteration:positionMain}
					{option:!positionMain.blockIsHTML}
						{$positionMain.blockContent}
					{/option:!positionMain.blockIsHTML}
					{option:positionMain.blockIsHTML}
						{$positionMain.blockContent}
					{/option:positionMain.blockIsHTML}
					{/iteration:positionMain}
				{/option:positionMain}
			</div>
		</div>
	</main>

	{* Footer *}
	{include:Core/Layout/Templates/Footer.tpl}

</body>
</html>
