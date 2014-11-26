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
			<div {option:!item.image}class="main-header-image"{/option:!item.image}{option:item.image}class="main-header-image2"{/option:item.image}>
				{option:item.image}
					<img src="{$FRONTEND_FILES_URL}/blog/images/source/{$item.image}" alt="{$item.title}" itemprop="image" />
				{/option:item.image}
				{option:!item.image}
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
				{/option:!item.image}

			</div>	
			{option:!item.image}
				<div class="image-overlay"></div>
			{/option:!item.image}
			{option:item.image}
				<div class="image-overlay-detail"></div>
			{/option:item.image}

			
			<div class="row main-header-title">
				{* Page title *}
				{option:!hideContentTitle}
					<header>
						<h1 class="main-title">{$page.title}</h1>
					</header>
				{/option:!hideContentTitle}
				
				{option:item.title}
					<header>
						<h1 class="item-title">{$item.title}</h1>
					</header>
				{/option:item.title}
			</div>
		</div>

		
			<div {option:!item.title}class="bluebox row"{/option:!item.title}class="bluebox2 row"{option:item.title}{/option:item.title}>
				{option:item.title}
				<p class="date-detail">
					<time itemprop="datePublished" datetime="{$item.publish_on|date:'Y-m-d\TH:i:s'}">{$item.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}</time>
				</p>
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
