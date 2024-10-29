<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2>
			Status: <span id="text_status">
			<?php if($this->isRoutingEnabled()) : ?>
				<b class="green">Routing active</b>
			<?php else: ?>
				<b class="red">Routing inactive</b>
			<?php endif; ?>											
		</h2>
		<div class="inside">
			<p>
				<label for="checkbox_enablerouting">
					<input name="" type="checkbox" id="checkbox_enablerouting" value="<?php if($this->isRoutingEnabled()) echo '1'; else echo '0'; ?>" <?php if($this->isRoutingEnabled()) echo 'checked'; ?>>
					<span><b>Enable Routing</b></span>
				</label>
			</p>
		</div>										
	</div>
</div>

<div class="meta-box-sortables ui-sortable">
	<div class="postbox">
		<h2><span>Add allowed route</span></h2>
		<div class="inside">
			<?php echo get_site_url(); ?>/<input placeholder="" maxlength="200" type="text" id="input_newroute" value="<?php //echo esc_attr( get_option('routes') ); ?>" />
			<input type="button" id="button_newroute" value="Add" class="button" />
			<br/>
			<label for="checkbox_allowindexpage">
				<input name="" type="checkbox" id="checkbox_allowindexpage" value="1" <?php if(in_array('/', $this->getAllowedRoutesCustom())) echo 'checked'; ?>>
				<span>Allow index page</span>
			</label> <span class="hint--medium hint--top" aria-label="Adds or removes '/' to the routes to dis/allow the index page.">[?]</span>
			<br/><br/>
	
			<form method="post" action="options.php">
				<?php settings_fields('allowed_routes_settings_group'); ?>
		
				<?php /* enable / disable routing option */ ?>
				<input type="hidden" name="enabled" id="enabled" value="<?php if($this->isRoutingEnabled()) echo '1'; else echo '0'; ?>"/>
				
				<?php /* hidden routes for form submitting */ ?>
				<div id="div_hiddenroutes">
					<?php foreach($this->getAllowedRoutesCustom() as $index => $allowedRouteCustom) : ?>
						<input type="hidden" name="routes[]" id="routes" value="<?php echo $allowedRouteCustom; ?>"/>
					<?php endforeach; ?>
				</div>
		
				<?php
					/* Calc select height */
					// 1 line is 1.2 em
					$selectHeight = '300px';
					$lineCount = count($this->getAllowedRoutesObligate()) + count($this->getAllowedRoutesCustom());
					if($lineCount > 0) {
						$selectHeight = $lineCount * 1.2 + (10 * 1.2);
						$selectHeight .= 'em';
					}
				?>
		
				<select style="height:<?php echo $selectHeight; ?>" name="select_routes" id="select_routes" multiple="multiple">
					<?php foreach($this->addNotRemoveableToRoute($this->getAllowedRoutesObligate()) as $index => $allowedRouteObligate) : ?>
						<option value="obligateroute" disabled><?php echo $allowedRouteObligate; ?></option>
					<?php endforeach; ?>
					
					<option value="obligateroute" disabled>---</option>
					
					<?php foreach($this->getAllowedRoutesCustom() as $index => $allowedRouteCustom) : ?>
						<option value="customroute"><?php echo $allowedRouteCustom; ?></option>
					<?php endforeach; ?>
					
				</select> 
				<input type="button" id="button_removeselectedroutes" value="Remove selected routes" class="alignright button" />
				
			</div>
			<p><br clear="all"/></p>
			</div>
		
		</div>									
		<?php submit_button('Apply Changes'); ?>
	</form>