{include:Core/Layout/Templates/Head2.tpl}

<body class="{$LANGUAGE}" class="comp-body" itemscope itemtype="http://schema.org/WebPage">
  <div class="wrapper">	
  			{* Header *}
			{include:Core/Layout/Templates/EN_Header.tpl}
		
	  <div class="main">
		<section class="page1">
			<div class="page-container">
				<div class="comp-text-1">
				 	<h2>Sinds 1986 gespecialiseerd in</h2>
					<div class="clear"></div>
					<div class="comp-nav">
						<ul>
							<li><a href="/en/the-company/distribution">Distribution</a></li>
							<li><a href="/en/the-company/transport">Transport</a></li>
							<li><a href="/en/the-company/storage-order-picking">Storage &amp; order picking</a></li>
							<li><a href="/en/the-company/immo">Immo</a></li>
						</ul>
					</div>
				</div>
				<img src="/src/Frontend/Core/Layout/images/company_0.jpg" />				
			</div>
		</section>

		<section class="page2">
			<div class="page-container">
				<div class="comp-text">
					{* Page1 position *}
					{option:positionPage1}
						{iteration:positionPage1}
						{option:!positionPage1.blockIsHTML}
							{$positionPage1.blockContent}
						{/option:!positionPage1.blockIsHTML}
						{option:positionPage1.blockIsHTML}
							{$positionPage1.blockContent}
						{/option:positionPage1.blockIsHTML}
						{/iteration:positionPage1}
					{/option:positionPage1}
				</div>
				<img src="/src/Frontend/Core/Layout/images/company_1.jpg" />
			</div>
		</section>

		<section class="page3">
			<div class="page-container"><div class="comp-text">
					{* Page2 position *}
					{option:positionPage2}
						{iteration:positionPage2}
						{option:!positionPage2.blockIsHTML}
							{$positionPage2.blockContent}
						{/option:!positionPage2.blockIsHTML}
						{option:positionPage2.blockIsHTML}
							{$positionPage2.blockContent}
						{/option:positionPage2.blockIsHTML}
						{/iteration:positionPage2}
					{/option:positionPage2}
				</div>
				<img src="/src/Frontend/Core/Layout/images/company_2.jpg" />
			</div>
		</section>

		<section class="page4">
			<div class="page-container">
				<div class="comp-text">
					{* Page3 position *}
					{option:positionPage3}
						{iteration:positionPage3}
						{option:!positionPage3.blockIsHTML}
							{$positionPage3.blockContent}
						{/option:!positionPage3.blockIsHTML}
						{option:positionPage3.blockIsHTML}
							{$positionPage3.blockContent}
						{/option:positionPage3.blockIsHTML}
						{/iteration:positionPage3}
					{/option:positionPage3}
				</div>
				<img src="/src/Frontend/Core/Layout/images/company_3.jpg" />
			</div>
		</section>

		<section class="page5">
			<div class="page-container">
				<div class="comp-text">
					{* Page4 position *}
					{option:positionPage4}
						{iteration:positionPage4}
						{option:!positionPage4.blockIsHTML}
							{$positionPage4.blockContent}
						{/option:!positionPage4.blockIsHTML}
						{option:positionPage4.blockIsHTML}
							{$positionPage4.blockContent}
						{/option:positionPage4.blockIsHTML}
						{/iteration:positionPage4}
					{/option:positionPage4}
				</div>
				<img src="/src/Frontend/Core/Layout/images/company_4.jpg" />
			</div>
		</section>


</div>


<!-- Footer stuff -->

<noscript>
	<div class="holder">
		<div class="row">
			<div class="alert-box notice">
				<h4>{$lblEnableJavascript|ucfirst}</h4>
				<p>{$msgEnableJavascript}</p>
			</div>
		</div>
	</div>
</noscript>

{* General Javascript *}
{iteration:jsFiles}
	<script src="{$jsFiles.file}"></script>
{/iteration:jsFiles}

{* Theme specific Javascript *}
<script src="{$THEME_URL}/Core/Js/theme.js"></script>

	<script>
		 onePageScroll(".main", {
	     sectionContainer: "section",
	     loop: true,
	     responsiveFallback: false
	   });
	</script>

</body>
</html>
