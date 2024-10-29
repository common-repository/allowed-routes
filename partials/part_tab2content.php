<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2><span>Important</span></h2>
		<div class="inside">
				<ul class="discs">
					<li>The routing overrules permalinks and works like a whitelist. Only correct entered routes will go through. You should test the routing before using on production environments.</li>
					<li>Be careful using several routing- or redirect plugins at the same time.</li>
					<li>Do not forget to delete all your page caches after enabling/disabling the routing to prevent unwanting routing behavior.</li>
				</ul>			
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2><span>Basics</span></h2>
		<div class="inside">
			<p>
				<ul class="discs">
					<li>The routes are relative to your wordpress installation url <?php echo get_site_url(); ?></li>
					<li>The used protocol (HTTP or HTTPS) will be ignored</li>
					<li>GET params will be ignored</li>
					<li>Routes are case sensitive</li>
					<li>The route <code>/</code> allows the index page (Check the checkbox "Allow index page")</li>
					<li>Wildcard <code>*</code> allows a single term with an arbitrary value (e.g. <code>category/*/page/*</code>)</li>
					<li>Wildcard <code>**</code> permits all possible combinations of terms (e.g. <code>category/**</code>) This wildcard is only allowed at the end of a route</li>
					<li>Wildcards are only allowed as complete terms. Correct: <code>/foo/*/bar/**</code> Wrong: <code>/foo/ba*/test**</code></li>
					<!--<li>No trailing slashes</li>-->
				</ul>											
			</p>
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2><span>Locked out?</span></h2>
		<div class="inside">
			<p>If you locked yourself out, you can disable the active routing with editing wp-config.php:</p>
			<p>Add the line: <code>define(ALWDRTS_DISABLE_ROUTING, true);</code></p>
			<p>After reviewing the problem change the value back to <code>false</code> (or remove the line again) to be able to use the routing functionality again.</p>
			<p>This should not happen, but if other plugins interfere with the backend routes, this is an emergency exit. The routing gets disabled and the backend can be accessed again.</p>
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>Example #01 - Allow the index page </h2>
		<div class="inside">
			Route: <code>/</code> (or check the checkbox "Allow index page")
			<p>
				<ul>
					<li><span class="green"><b>&check;</b></span> www.example.com</li>
					<li><span class="green"><b>&check;</b></span> www.example.com?param=123</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/contact</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/page/1</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/2011/01/01/test</li>
				</ul>
			</p>
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>Example #02 Allow a specific route</h2>
		<div class="inside">
			Route: <code>contact</code>
			<p>
				<ul>
					<li><span class="green"><b>&check;</b></span> www.example.com/contact</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/contact?param=123</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/contacts</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/contact/new</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/menu/contacts</li>
				</ul>
			</p>
		</div>
	</div>
</div>						
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>Example #03 - Allow a route with wildcard <code>*</code> (1 term with arbitrary value)</h2>
		<div class="inside">
			Route: <code>page/*</code>
			<p>
				<ul>
					<li><span class="green"><b>&check;</b></span> www.example.com/page/1</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/page/2</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/page/3</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/page</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/page/1/2</li>
				</ul>
			</p>
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>Example #04 - Allow a route with wildcard <code>*</code> (1 term with arbitrary value)</h2>
		<div class="inside">
			Route: <code>category/*/page/*</code>
			<p>
				<ul>
					<li><span class="green"><b>&check;</b></span> www.example.com/category/1/page/1</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/category/2/page/3</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/category/100/page/20</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/category</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/category/100/</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/category/100/page</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/category/100/page/1/20</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/page/100/category/1/20</li>
				</ul>
			</p>
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>Example #05 - Allow a route with wildcard <code>**</code> (multiple terms with arbitrary values)</h2>
		<div class="inside">
			Route: <code>archive/**</code>
			<p>
				(This wildcard is only allowed at the end of the route)<br/>
				<ul>
					<li><span class="green"><b>&check;</b></span> www.example.com/archive/2015</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/archive/2015/08</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/archive/2015/08/01</li>
					<li><span class="green"><b>&check;</b></span> www.example.com/archive/2015/08/01/books</li>
					<li><span class="red"><b>&#10007;</b></span> www.example.com/archive</li>
				</ul>
			</p>
			
		</div>
	</div>
</div>
<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>Example #06 - Allow route with wildcard <code>**</code> (multiple terms with arbitrary values)</h2>
		<div class="inside">
			Route: <code>/**</code>
			<p>
				<i>This route allows all requests except the index page.</i>
			</p>
		</div>
	</div>
</div>