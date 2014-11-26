{include:Core/Layout/Templates/Head.tpl}

<body class="{$LANGUAGE}" itemscope itemtype="http://schema.org/WebPage">
	<!--[if lt IE 8]>
		<div class="alert-box">
			<p>You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser to improve your experience.</p>
		</div>
	<![endif]-->

	{* Header *}
	{include:Core/Layout/Templates/NL_Header.tpl}

	<main id="main" class="holder main-holder" role="main">
		<div class="main-header">
			<div {option:!item.images}class="main-header-image"{/option:!item.images}{option:item.images}id="main-header-image3"{/option:item.images}>
				
				{option:item.images}
					{iteration:images}
					<img class="immo-head-img" src="{$images.sizes.large}" />
					{/iteration:images}
				{/option:item.images}

				{option:!item.images}
					{* Image position *}
					{option:positionImage}
						{iteration:positionImage}
						{option:!positionImage.blockIsHTML}
							{$positionImage.blockContent}
						{/option:!positionImage.blockIsHTML}
						{option:positionImage.blockIsHTML}
							{$positionImage.blockContent}
						{/option:positionImage.blockIsHTML}
						{/iteration:positionImage}
					{/option:positionImage}
				{/option:!item.images}
			
			</div>	
			{option:!item.images}
				<div class="image-overlay"></div>
			{/option:!item.images}
			{option:item.images}
				<div class="image-overlay-detail"></div>
			{/option:item.images}
			
			<nav id="selector2">
				<ul>
					<li>
						<a href="/nl/het-bedrijf/distributie">Distributie</a>
					</li>
					<li>
						<a href="/nl/het-bedrijf/transport">Transport</a>
					</li>
					<li>
						<a href="/nl/het-bedrijf/opslag-fijnpicking">Opslag &amp; fijnpicking</a>
					</li>
					<li>
						<a href="/nl/het-bedrijf/immo">Immo</a>
					</li>
				</ul>
			</nav>

			<div class="row main-header-title">
				{* Page title *}
				{option:!item.title}
					<header>
						<h1 class="main-title">{$page.title}</h1>
					</header>
				{/option:!item.title}

				{option:item.title}
					<header>
						<h1 class="item-title">{$item.title}</h1>
					</header>
				{/option:item.title}
			</div>
		</div>

		<div {option:!item.title}class="immo-bluebox row"{/option:!item.title}class="bluebox row"{option:item.title}{/option:item.title}>
			
			{option:item.title}
				{$item.introduction}
			{/option:item.title}

			{option:!item.title}
				{* Bluebox position *}
				{option:positionBluebox}
					{iteration:positionBluebox}
					{option:!positionBluebox.blockIsHTML}
						{$positionBluebox.blockContent}
					{/option:!positionBluebox.blockIsHTML}
					{option:positionBluebox.blockIsHTML}
						{$positionBluebox.blockContent}
					{/option:positionBluebox.blockIsHTML}
					{/iteration:positionBluebox}
				{/option:positionBluebox}
			{/option:!item.title}
		</div>
			<div class="main main-text">
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
	</main>

	{* Footer *}
	{include:Core/Layout/Templates/Footer.tpl}

</body>
</html>
