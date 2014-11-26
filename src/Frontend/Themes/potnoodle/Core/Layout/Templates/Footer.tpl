<footer class="holder footer-holder">
	<div class="footer row">
		&copy; {$now|date:'Y'} {$siteTitle} | <span class="nowrap">Jan De Malschelaan 14</span> | <span class="nowrap">9140 Temse</span> | <span class="nowrap">T +32(0)3/771.09.13</span> | <span class="nowrap">F +32(0)3/771.23.58</span>
	</div>
</footer>

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
<script src="{$THEME_URL}/Core/Js/rrssb.min.js"></script>
<script src="{$THEME_URL}/Core/Js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
<script src="{$THEME_URL}/Core/Js/jquery.1.10.2.min.js"></script>



{* Site wide HTML *}
{$siteHTMLFooter}
