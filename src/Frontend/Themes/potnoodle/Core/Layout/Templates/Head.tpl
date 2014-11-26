<!DOCTYPE html>
<!--[if IE 7]>         <html lang="{$LANGUAGE}" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="{$LANGUAGE}" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="{$LANGUAGE}" class="no-js"> <!--<![endif]-->

<head>
	{* Meta *}
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">
	<meta name="generator" content="Fork CMS" />
	{$meta}
	{$metaCustom}

	<title>{$pageTitle}</title>

	<!-- Mobile settings http://t.co/dKP3o1e -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{* Stylesheets *}
	{iteration:cssFiles}
		<link rel="stylesheet" href="{$cssFiles.file}" />
	{/iteration:cssFiles}

	{* Favicon *}
	<link rel="icon" href="{$THEME_URL}/favicon.ico" />

	{* Facebook *}
	<meta property="og:title" content="VRD">
	<meta property="og:type" content="website">
	<meta property="og:url" content="http://vrd.be">
	<meta property="og:image" content="http://vrd.be/src/Frontend/Core/Layout/images/logo@2x.png">
	<meta property="og:description" content="Uw partner in opslag, distributie en verhuur!">


	{* Fonts *}
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700|Merriweather:400,700' rel='stylesheet' type='text/css'>

	{* Windows 8 tile *}
	<meta name="application-name" content="{$siteTitle}"/>
	<meta name="msapplication-TileColor" content="#3380aa"/>
	<meta name="msapplication-TileImage" content="{$THEME_URL}/tile.png"/>

	{* HTML5 shim and Respond.js: "IE8 and below" support of HTML5 elements and media queries *}
	<!--[if lt IE 9  & (!IEMobile)]
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="{$THEME_URL}/Core/Js/respond.min.js"></script>
	<![endif]-->

	{* Custom modernizr build: http://modernizr.com *}
	<script src="{$THEME_URL}/Core/Js/modernizr.custom.js"></script>

	{* Site wide HTML *}
	{$siteHTMLHeader}


</head>
