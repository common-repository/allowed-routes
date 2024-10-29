<?php
/* 
Plugin Name: Allowed Routes
Description: Define only allowed routes for your website. Permalinks will be overruled. Wildcard Support.
Version: 1.1
Author: nerdismFTW
Author URI: https://profiles.wordpress.org/nerdismftw
License: GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/ 

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

if (!class_exists('AlwdRts_Main')) {
	
	// requirements
	require('classes/class.abstract.php');
	
	final class AlwdRts_Main extends AlwdRts_AbstractFunctions
	{
		protected $version = '1.0';
		protected $pluginName = 'Allowed Routes';
		protected $contactEmail = 'contact@nerdismftw.com';
		
		public function __construct()
		{
			// init disable routing option
			if ( !defined( 'ALWDRTS_DISABLE_ROUTING' ) ) define(ALWDRTS_DISABLE_ROUTING, false);
			
			// register hooks
			register_activation_hook(__FILE__, array($this, 'allowedroutes_activate'));
			register_uninstall_hook(__FILE__, array($this, 'allowedroutes_uninstall'));
			register_deactivation_hook( __FILE__, array($this, 'allowedroutes_deactivation'));
			
			// check lockout
			$this->checkLockedOut();
			
			// actions hooks
			add_action('admin_menu', array($this, 'action_show_menu'));
			add_action('admin_init', array($this, 'action_register_settings'));
			add_action('init', array($this, 'action_routing'));
			add_action('admin_enqueue_scripts', array($this, 'action_register_js_css'));
			
			// filters
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'filter_action_links') );
		}

		/**
		 * If locked out emergency mode, disable routing anyway.
		 * @return void
		 */
		protected function checkLockedOut()
		{
			if($this->isLockedOut()) {
				update_option( 'enabled', '0' );
			}
		}

		/**
		 * Check if locked out emergency mode
		 * @return bool
		 */
		protected function isLockedOut()
		{
			if(ALWDRTS_DISABLE_ROUTING === true) {
				return true;
			}
			else {
				return false;
			}
		}
		
		/**
		 * Plugin activation routine
		 * @return void
		 */
		function allowedroutes_activate()
		{
			// Well, nothing to do here
		}
		
		/**
		 * Plugin deactivation routine
		 * disable routing
		 * @return void
		 */
		function allowedroutes_deactivation()
		{
			// set enabled to false
			update_option( 'enabled', '0' );
		}
		
		/**
		 * Plugin uninstall routine
		 * @return void
		 */
		function allowedroutes_uninstall()
		{
			delete_option('enabled');
			delete_option('routes');
		}		
		
		/**
		 * Set "Settings" link in plugin directory
		 * @return array
		 */
		public function filter_action_links ( $links )
		{
			$mylinks = array(
				'<a href="' . admin_url( 'admin.php?page=allowedroutes_listing' ) . '">Settings</a>',
			);
			return array_merge( $mylinks, $links );
		}
		
		/**
		 * Register Settings
		 * @return void
		 */
		function action_register_settings()
		{
			register_setting( 'allowed_routes_settings_group', 'routes', array($this, 'sanitizeSettingRoutes'));
			register_setting( 'allowed_routes_settings_group', 'enabled', array($this, 'sanitizeSettingEnabled'));
		}
		
		/**
		 * Add admin footer changes
		 * @return void
		 */
		protected function change_admin_footer_text()
		{
			add_filter('admin_footer_text', array($this, 'filter_change_footer_admin'), 9999);
			add_filter('update_footer', array($this, 'filter_change_footer_version'), 9999);			
		}
		
		/**
		 * Callback function for change_admin_footer_text()
		 * @return string
		 */
		public function filter_change_footer_version()
		{
			echo '<span id="footer-thankyou"><b>'.$this->pluginName.'</b> Version '.$this->version.'</span>';
		}

		/**
		 * Callback function for change_admin_footer_text()
		 * @return string
		 */
		public function filter_change_footer_admin() 
		{
			echo '';
		}
		
		/**
		 * Sanitize settings route
		 * @return array
		 */
		function sanitizeSettingRoutes($input)
		{
			if(!is_array($input))
				return array();
			
			$sanitizedArray = array();
			foreach($input as $element) {
				// no standard sanitize function works here
				// use base64 for safe saving
				$sanitizedArray[] = base64_encode($element);
			}

			return $sanitizedArray;
		}
		
		/**
		 * Sanitize settings enabled/disabled
		 * @return string '1'|'0'
		 */
		function sanitizeSettingEnabled($input)
		{
			if($input !== '1' && $input !== '0') {
				return '0';
			}
			return $input;
		}
		
		/**
		 * Register css & js files
		 * @return void
		 */
		function action_register_js_css()
		{
			wp_register_script('plugin_js', plugins_url( '/js/functions.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), false, true);
			wp_register_style('plugin_css1', plugins_url( '/css/style.css', __FILE__ ), array(), false, 'all');
			wp_register_style('plugin_css2', plugins_url( '/css/tooltips.css', __FILE__ ), array(), false, 'all');
			wp_enqueue_script('plugin_js');
			wp_enqueue_style('plugin_css1');
			wp_enqueue_style('plugin_css2');
		}
		
		/**
		 * Add string '(Not removable)' to routes
		 * @return array
		 */
		protected function addNotRemoveableToRoute($array)
		{
			$postfix = '(Not removable)';
			$maxlen = max(array_map('strlen', $array));
			foreach($array as $var => $item) {
				$array[$var] = str_replace(' ', '&nbsp;', str_pad($item, ($maxlen+3)).$postfix);
			}
			return $array;
		}

		/**
		 * Add menu page
		 * @return void
		 */
		function action_show_menu()
		{
			add_menu_page( 	$this->pluginName, // page title
							$this->pluginName, // menu title
							'activate_plugins',	// capability
							'allowedroutes_listing', // menu slug
							array($this, 'menu_page'), // callable function
							'dashicons-yes', // icon
							null // position
						);
		}
		
		/**
		 * Check if routing is enabled
		 * @return bool
		 */
		protected function isRoutingEnabled()
		{
			$enabled = get_option('enabled');
			if($enabled && $enabled === '1') {
				return true;
			}
			else {
				return false;
			}
		}
		
		/**
		 * Actual routing. If routing is enabled, it will validate and outputs the 404 page on failure.
		 * If there is no 404 template, use wp_die() to output message.
		 * @return void
		 */
		public function action_routing()
		{
			// check if routing is enabled
			if(!$this->isRoutingEnabled()) {
				return;
			}
			
			// include router class and initiate
			require('classes/class.router.php');
			$router = new AlwdRts_Router();
			
			// check routing status
			if($router->getStatus() === false) {
				$message = $router->getMessage();
				status_header( 404 );
				nocache_headers();
				
				// output 404
				if ( '' != get_404_template() ) {
					// see https://richjenks.com/wordpress-throw-404/
					global $wp_query;
					$wp_query->set_404();
					require get_404_template();
				}
				else {
					// fallback if no 404 template is found
					wp_die( '404 - Not found.', '404 - Not found.', 404 );
				}
				exit();
			}
		}
	
		/** 
		 * Output menu page html
		 * @return void
		 */
		function menu_page()
		{
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			} else { ?>
			
			<?php
				// change footer info in plugin page
				$this->change_admin_footer_text();
			?>
			
			<div class="wrap">
				<?php if($this->isLockedOut()) : ?>
					<div class="notice notice-error"><p><strong>Attention:</stron> Routing was disabled because the constant ALWDRTS_DISABLE_ROUTING is set to "true".
						<br/>Please change the value back to "false" or remove the variable from your wp-config.php file.
					</p></div>
				<?php elseif(is_multisite()) : ?>
					<div class="notice notice-error"><p><strong>Attention:</strong> This is a multisite installation. This is not supported yet. Sorry :-(</p></div>
					<?php update_option( 'enabled', '0' ); ?>
					
				<?php elseif($this->getEnvErrors()) : ?>
					<div class="notice notice-error"><p><strong>Error:</strong><ul><?php foreach($this->getEnvErrors() as $msg) { echo '<li>'.$msg.'<li/>'; } ?></ul></p></div>
					<?php update_option( 'enabled', '0' ); ?>
				<?php else : ?>					
					<?php if( isset($_GET['settings-updated']) ) { ?>
						<div class="notice notice-info"><p><strong>Notice:</strong> Please don't forget to delete all page caches if you use a caching plugin.</p></div>				
						<div id="message" class="updated">
							<p><strong>Settings saved.</strong> </p>
						</div>
					<?php } ?>
				<?php endif; ?>
				
				<h2><?php echo $this->pluginName; ?></h2>
				<h2 class="nav-tab-wrapper">
					<a id="tabRouting" href="#" class="nav-tab nav-tab-active">Routing</a>
					<a id="tabHelp" href="#" class="nav-tab">Help</a>
				</h2>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="postbox-container-1" class="postbox-container " >
							<div id="tab1sidebar">
								<?php require('partials/part_tab1sidebar.php'); ?>
							</div>
							<div id="tab2sidebar">
								<?php require('partials/part_tab2sidebar.php'); ?>
							</div>
						</div>
                        <div id="postbox-container-2" class="postbox-container">
							<div id="tab1content">
								<?php require('partials/part_tab1content.php'); ?>
							</div>
							<div id="tab2content">
								<?php require('partials/part_tab2content.php'); ?>
							</div>
                        </div>
                    </div>
                    <br class="clear">
                </div>
			</div><?php
			}
		} 
	}
	new AlwdRts_Main();
}