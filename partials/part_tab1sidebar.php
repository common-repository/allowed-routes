<div class="meta-box-sortables">
	<div class="postbox">
		<h2><span>About</span></h2>
			<div class="inside">
				Define allowed routes for your website, all other routes will return the 404 - not found page template. Permalinks can be overruled.
				<p>Login, Backend routes and local files (like wp-login.php) are always available to prevent a lockout.</p>
				<p><b>Warning:</b> The routing overrules permalinks and works like a whitelist. Only correct entered routes will go through. You should test the routing before using on production environments.</p>
				<p>Also delete all your page caches after enabling to prevent unwanted routing behavior.</p>
				<p>
					<input type="button" class="alignright button" id="button_more" value="More" />
				</p>
				<br clear="all"/>
			</div>
	</div>
</div>

<div class="meta-box-sortables">
	<div class="postbox">
		<h2><span>Basic example</span></h2>
		<div class="inside">
			Hover or tap the line for explanation:
			<p>
			<div class="">
			<div class="fullwidth hint--medium hint--top" aria-label="#01 Allows all term combinations after /menu like /menu/page/1 or /menu/submenu"><code class="block">#01 /</code></div>
			<div class="fullwidth hint--medium hint--top" aria-label="#02 Allows one term after the index page like /faq or /help"><code class="block">#02 /*</code></div>
			<div class="fullwidth hint--medium hint--top" aria-label="#03 Allows one term after /page like /page/1 or /page/999"><code class="block">#03 page/*</code></div>
			<div class="fullwidth hint--medium hint--top" aria-label="#04 Allows urls like /category/books/page/1 or /category/cakes/page/999"><code class="block">#04 category/*/page/*</code></div>
			<div class="fullwidth hint--medium hint--top" aria-label="#05 Allows all term combinations after /menu like /menu/page/1 or /menu/submenu"><code class="block">#05 menu/**</code></div>
			<div class="fullwidth hint--medium hint--top" aria-label="#06 Allows /sample-page only"><code class="block">#06 sample-page</code></div>
			</div>
			</p>
			<p>
			<input type="button" class="alignright button" id="button_moreexamples" value="More Examples" />
			</p>
			<br clear="all"/>
		</div>
	</div>
</div>
<div class="meta-box-sortables">
	<div class="postbox">
		<h2><span>Contact</span></h2>
		<div class="inside">
			<p>
				Ideas, feedback, bug reports or recommendations?
			</p>
			<p>
				<a href="mailto:<?php echo $this->contactEmail; ?>"><strong>Feel free to contact us.</strong></a>
			</p>
		</div>
	</div>
</div>