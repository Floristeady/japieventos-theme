<?php

/*
	Begin Boilerplate Admin panel.
*/

/*	Define Boilerplate URI */
	define('BP_THEME_URL', get_template_directory_uri());

/*
	There are essentially 5 sections to this:
	1)	Add "Boilerplate Admin" link to left-nav Admin Menu & callback function for clicking that menu link
	2)	Add Admin Page CSS if on the Admin Page
	3)	Add "Boilerplate Admin" Page options
	4)	Create functions to add above elements to pages
	5)	Add Boilerplate options to page as requested
*/

/*	1)	Add "Boilerplate Admin" link to left-nav Admin Menu & callback function for clicking that menu link */

	//	Add option if in Admin Page
	if ( ! function_exists( 'create_boilerplate_admin_page' ) ):
		function create_boilerplate_admin_page() {
			add_theme_page( __('General Options', 'japieventos'), __( 'General Options', 'japieventos'), 'administrator', 'boilerplate-admin', 'build_boilerplate_admin_page');
		}
		add_action('admin_menu', 'create_boilerplate_admin_page');
	endif; // create_boilerplate_admin_page

	//	You get this if you click the left-column "Boilerplate Admin" (added above)
	if ( ! function_exists( 'build_boilerplate_admin_page' ) ):
		function build_boilerplate_admin_page() {
		?>
			<div id="boilerplate-options-wrap">
				<div class="icon32" id="icon-tools"><br /></div>
				<h2><?php _e( 'General options for', 'japieventos' ); ?> <?php echo wp_get_theme() . __( '', 'japieventos' );?> </h2>
				<p><?php _e( 'This is the web site manager. Select the options you want to include on the website.', 'japieventos' ); ?></p>

				<form method="post" action="options.php" enctype="multipart/form-data">
					<?php settings_fields('plugin_options'); /* very last function on this page... */ ?>
					<?php do_settings_sections('boilerplate-admin'); /* let's get started! */?>
					<p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>"></p>
				</form>
			</div>
		<?php
		}
	endif; // build_boilerplate_admin_page

/*	2)	Add Admin Page CSS if on the Admin Page */
	if ( ! function_exists( 'admin_register_head' ) ):
		function admin_register_head() {
			echo '<link rel="stylesheet" href="' .BP_THEME_URL. '/theme-admin/general-options.css">'.PHP_EOL;
		}
		add_action('admin_head', 'admin_register_head');
	endif; // admin_register_head

/*	3)	Add "Boilerplate Admin" Page options */
	//	Register form elements
	if ( ! function_exists( 'register_and_build_fields' ) ):
		function register_and_build_fields() {
			register_setting('plugin_options', 'plugin_options', 'validate_setting');
			add_settings_section('main_section', '', 'section_cb', 'boilerplate-admin');
			add_settings_field('google_verification', 'Google Verification?:', 'google_verification_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('meta_tags', 'Meta Keywords tags?:', 'meta_tags_setting', 'boilerplate-admin', 'main_section');
			
			add_settings_field('favicon', 'Got Favicon?:', 'favicon_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('favicon_ithing', 'Got <em><abbr title="iPhone, iTouch, iPad...">iThing</abbr></em> Favicon?', 'favicon_ithing_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('modernizr_js', 'Modernizr JS?:', 'modernizr_js_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('jquery_js', 'jQuery JS?:', 'jquery_js_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('plugins_js', 'jQuery Plug-ins JS?:', 'plugins_js_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('site_js', 'Site-specific JS?:', 'site_js_setting', 'boilerplate-admin', 'main_section');
			add_settings_field('cache_buster', 'Cache-Buster?:', 'cache_buster_setting', 'boilerplate-admin', 'main_section');
			
		}
		add_action('admin_init', 'register_and_build_fields');
	endif; // register_and_build_fields

	//	Add Admin Page validation
	if ( ! function_exists( 'validate_setting' ) ):
		function validate_setting($plugin_options) {
			$keys = array_keys($_FILES);
			$i = 0;
			foreach ( $_FILES as $image ) {
				// if a files was upload
				if ($image['size']) {
					// if it is an image
					if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {
						$override = array('test_form' => false);
						// save the file, and store an array, containing its location in $file
						$file = wp_handle_upload( $image, $override );
						$plugin_options[$keys[$i]] = $file['url'];
					} else {
						// Not an image.
						$options = get_option('plugin_options');
						$plugin_options[$keys[$i]] = $options[$logo];
						// Die and let the user know that they made a mistake.
						wp_die('No image was uploaded.');
					}
				} else { // else, the user didn't upload a file, retain the image that's already on file.
					$options = get_option('plugin_options');
					$plugin_options[$keys[$i]] = $options[$keys[$i]];
				}
				$i++;
			}
			return $plugin_options;
		}
	endif; // validate_setting

	//	Add Admin Page options

	//	in case you need it...
	if ( ! function_exists( 'section_cb' ) ):
		function section_cb() {}
	endif; // section_cb


	//	callback fn for google_verification
	if ( ! function_exists( 'google_verification_setting' ) ):
		function google_verification_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['google_verification']) && $options['google_verification'] && $options['google_verification_account'] && $options['google_verification_account'] && $options['google_verification_domain'] && $options['google_verification_domain'] !== 'XXXXXXXXX...') ? 'checked="checked" ' : '';
			$account = (isset($options['google_verification_account']) && $options['google_verification_account']) ? $options['google_verification_account'] : 'XXXXXXXXX...';
			$urlsite = (isset($options['google_verification_domain']) && $options['google_verification_domain']) ? $options['google_verification_domain'] : 'mysite.com';
			
			$msg = ($account === 'XXXXXXXXX...') ? ', where </code>XXXXXXXXX...</code> will be replaced with the code you insert above' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[google_verification]" value="true" ' .$checked. '/>';
			echo _e( '<p>Add <a href="https://support.google.com/webmasters/answer/35179">Google Verification</a> code and <a href="https://www.google.com/analytics">Google Universal Analytics </a> code to the <code>&lt;head&gt;</code> of all your pages.</p>', 'japieventos' );
			echo _e( '<p>To include Google Analytics and Google Verification code, select this option and add your Verification number here and the site domain:</p>', 'japieventos' );
			echo _e( '<p>Google Analytics ID: </p>', 'japieventos' );
			echo '<input type="text" size="20" name="plugin_options[google_verification_account]" value="'.$account.'" onfocus="javascript:if(this.value===\'XXXXXXXXX...\'){this.select();}"></p>';
			
			echo _e( '<p>This will add the following code to the <code>&lt;head&gt;</code> of your pages, where <code>XXXXXXXXX?</code> will be replaced with the code you insert above.</p><br/>', 'japieventos' );
			
			echo _e( '<p>Site Domain: </p>', 'japieventos' );
			echo '<input type="text" size="20" name="plugin_options[google_verification_domain]" value="'.$urlsite.'" onfocus="javascript:if(this.value===\'mysite.com\'){this.select();}"></p><br/>';
			echo '<code>&lt;meta name="google-site-verification" content="'.$account.'"&gt;</code>';
			echo _e( '<p>And will add the latest version of the Analytics tracking code, to track website visitors with Google Analytics.</p>', 'japieventos' );
		}
	endif; // google_verification_setting


	//	callback fn for favicon
	if ( ! function_exists( 'favicon_setting' ) ):
		function favicon_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['favicon']) && $options['favicon']) ? 'checked="checked" ' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[favicon]" value="true" ' .$checked. '/>';
			echo _e( '<p>If you plan to use a <a href="http://en.wikipedia.org/wiki/Favicon">favicon</a> for your site, place the "favicon.ico" file in the root directory of your site.</p>', 'japieventos' );
			echo _e( '<p>If the file is in the right location, you don\'t really need to select this option, browsers will automatically look there and no additional code will be added to your pages.</p>', 'japieventos' );
			echo _e( '<p>Selecting this option will add the following code to the <code>&lt;head&gt;</code> of your pages:</p>', 'japieventos' );
			echo '<code>&lt;link rel="shortcut icon" href="/favicon.ico"&gt;</code>';
		}
	endif; // favicon_setting

	//	callback fn for favicon_ithing
	if ( ! function_exists( 'favicon_ithing_setting' ) ):
		function favicon_ithing_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['favicon_ithing']) && $options['favicon_ithing']) ? 'checked="checked" ' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[favicon_ithing]" value="true" ' .$checked. '/>';
			echo _e( '<p>To allow <em>iThing(iPhone, iTouch, iPad...)</em> users to <a href="http://developer.apple.com/library/safari/#documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html">add an icon for your site to their Home screen</a>, place the "apple-touch-icon.png" file in the root directory of your site.</p>', 'japieventos' );
			echo _e( '<p>If the file is in the right location, you don\'t really need to select this option, browsers will automatically look there and no additional code will be added to your pages.</p>', 'japieventos' );
			echo _e( '<p>Selecting this option will add the following code to the <code>&lt;head&gt;</code> of your pages:</p>', 'japieventos' );
			echo '<code>&lt;link rel="apple-touch-icon" href="/apple-touch-icon.png"&gt;</code>';
			echo '<code>&lt;link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png"&gt;</code>';
			echo '<code>&lt;link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png"&gt;</code>';
			echo '<code>&lt;link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png"&gt;</code>';
		}
	endif; // favicon_ithing_setting

		//	callback fn for modernizr_js
	if ( ! function_exists( 'modernizr_js_setting' ) ):
		function modernizr_js_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['modernizr_js']) && $options['modernizr_js']) ? 'checked="checked" ' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[modernizr_js]" value="true" ' .$checked. '/>';
			echo _e('<p><a href="http://modernizr.com">Modernizr</a> is a JS library that appends classes to the <code>&lt;html&gt;</code> that indicate whether the user\'s browser is capable of handling advanced CSS, like "cssreflections" or "no-cssreflections".  It\'s a really handy way to apply varying CSS techniques, depending on the user\'s browser\'s abilities, without resorting to CSS hacks.</p>', 'japieventos' );
			echo _e('<p>Selecting this option will add the following code to the <code>&lt;head&gt;</code> of your pages (note the lack of a version, when you\'re ready to upgrade, simply copy and paste the new version into the file below, and your site is ready to go!):</p>', 'japieventos' );
			echo '<code>&lt;script src="' .BP_THEME_URL. '/js/modernizr.js"&gt;&lt;/script&gt;</code>';
			echo _e('<p><strong>Note: If you do <em>not</em> include Modernizr, the IEShiv JS <em>will</em> be added to accommodate the HTML5 elements used in Boilerplate in weaker browsers:</strong></p>', 'japieventos' );
			echo '<code>&lt;!--[if lt IE 9]&gt;</code>';
			echo '<code>	&lt;script src="//html5shiv.googlecode.com/svn/trunk/html5.js" onload="window.ieshiv=true;"&gt;&lt;/script&gt;</code>';
			echo '<code>	&lt;script&gt;!window.ieshiv && document.write(unescape(\'%3Cscript src="' .BP_THEME_URL. '/js/ieshiv.js"%3E%3C/script%3E\'))&lt;/script&gt;</code>';
			echo '<code>&lt;![endif]--&gt;</code>';
		}
	endif; // modernizr_js_setting

	//	callback fn for jquery_js
	if ( ! function_exists( 'jquery_js_setting' ) ):
		function jquery_js_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['jquery_js']) && $options['jquery_js']) ? 'checked="checked" ' : '';
			$version = (isset($options['jquery_version']) && $options['jquery_version'] && $options['jquery_version'] !== '') ? $options['jquery_version'] : '1.10.2';
			$inhead = (isset($options['jquery_head']) && $options['jquery_head']) ? 'checked="checked" ' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[jquery_js]" value="true" ' .$checked. '/>';
			echo _e('<p><a href="http://jquery.com/">jQuery</a> is a JS library that aids greatly in developing high-quality JavaScript quickly and efficiently.</p>', 'japieventos' );
			echo  _e( '<p>Selecting this option will add the following code to your pages just before the: <code>&lt;/body&gt;</code></p>', 'japieventos' );
			echo '<code>&lt;script src="//ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js">&lt;/script&gt;</code>';
			echo '<code>&lt;script&gt;window.jQuery || document.write("&lt;script src="js/jquery-1.10.2.min.js"&lt;/script&gt;")</code>';
			echo '<p><input class="check-field" type="checkbox" name="plugin_options[jquery_head]" value="true" ' .$inhead. '/>';
			echo _e('<p><strong>Note: <a href="http://developer.yahoo.com/blogs/ydn/posts/2007/07/high_performanc_5/">Best-practices</a> recommend that you load JS as close to the <code>&lt;/body&gt;</code> as possible.  If for some reason you would prefer jQuery and jQuery plug-ins to be in the <code>&lt;head&gt;</code>, please select this option.</strong></p>', 'japieventos' );
			echo _e('<p>The above code first tries to download jQuery from Google\'s CDN (which might be available via the user\'s browser cache).  If this is not successful, it uses the theme\'s version.</p>', 'japieventos' );
			echo _e('<p><strong>Note: This plug-in tries to keep current with the most recent version of jQuery.  If for some reason you would prefer to use another version, please indicate that version:</strong></p><br/>', 'japieventos' );
			echo '<input type="text" size="6" name="plugin_options[jquery_version]" value="'.$version.'"> (<a href="http://code.google.com/apis/libraries/devguide.html#jquery">see all versions available via Google\'s CDN</a>)</p>';
		}
	endif; // jquery_js_setting

	//	callback fn for plugins_js
	if ( ! function_exists( 'plugins_js_setting' ) ):
		function plugins_js_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['plugins_js']) && $options['plugins_js']) ? 'checked="checked" ' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[plugins_js]" value="true" ' .$checked. '/>';
			echo _e('<p>If you choose to use any <a href="http://plugins.jquery.com/">jQuery plug-ins</a>, I recommend downloading and concatenating them together in a single JS file, as below.  This will <a href="http://developer.yahoo.com/performance/rules.html">reduce your site\'s HTTP Requests</a>, making your site a better experience.</p>', 'japieventos' );
			echo  _e( '<p>Selecting this option will add the following code to your pages just before the: <code>&lt;/body&gt;</code></p>', 'japieventos' );
			echo '<code>&lt;script type=\'text/javascript\' src=\'' .BP_THEME_URL. '/js/plug-in.js?ver=x\'&gt;&lt;/script&gt;</code>';
			echo _e('<p><strong>Note: If you do <em>not</em> include jQuery, this file will <em>not</em> be added to the page.</strong></p>', 'japieventos' );
		}
	endif; // plugins_js_setting

	//	callback fn for site_js
	if ( ! function_exists( 'site_js_setting' ) ):
		function site_js_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['site_js']) && $options['site_js']) ? 'checked="checked" ' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[site_js]" value="true" ' .$checked. '/>';
			echo _e( '<p>If you would like to add your own site JavaScript file, we provides a starter file located in:</p>', 'japieventos' );
			echo '<code>' .BP_THEME_URL. '/js/script-starter.js</code>';
			echo _e( '<p>Add what you want to that file and select this option.</p>', 'japieventos' );
			echo  _e( '<p>Selecting this option will add the following code to your pages just before the: <code>&lt;/body&gt;</code></p>', 'japieventos' );
			echo '<code>&lt;script type=\'text/javascript\' src=\'' .BP_THEME_URL. '/js/script-starter.js?ver=x\'&gt;&lt;/script&gt;</code>';
		}
	endif; // site_js_setting

	//	callback fn for cache_buster
	if ( ! function_exists( 'cache_buster_setting' ) ):
		function cache_buster_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['cache_buster']) && $options['cache_buster']) ? 'checked="checked" ' : '';
			$version = (isset($options['cache_buster_version']) && $options['cache_buster_version']) ? $options['cache_buster_version'] : '1';
			echo '<input class="check-field" type="checkbox" name="plugin_options[cache_buster]" value="true" ' .$checked. '/>';
			echo _e( '<p>To force browsers to fetch a new version of a file, versus one it might already have cached, you can add a "cache buster" to the end of your CSS and JS files.</p>', 'japieventos' );
			echo _e( '<p>To increment the cache buster version number, type something here:</p><br />', 'japieventos' );
			echo '<input type="text" size="4" name="plugin_options[cache_buster_version]" value="'.$version.'"></p>';
			echo _e( '<p>Selecting this option will add the following code to the end of all of your CSS and JS file names on all of your pages:</p>', 'japieventos' );
			echo '<code>?ver='.$version.'</code>';
		}
	endif; // cache_buster_setting
	
	//	callback fn for google_verification
	if ( ! function_exists( 'meta_tags_setting' ) ):
		function meta_tags_setting() {
			$options = get_option('plugin_options');
			$checked = (isset($options['meta_tags']) && $options['meta_tags'] && $options['meta_tags_keys'] && $options['meta_tags_keys'] !== 'tags...') ? 'checked="checked" ' : '';
			$tags = (isset($options['meta_tags_keys']) && $options['meta_tags_keys']) ? $options['meta_tags_keys'] : 'tags...';
			$msg = ($tags === 'tags...') ? ', where </code>tags...</code> will be replaced with the code you insert above.' : '';
			echo '<input class="check-field" type="checkbox" name="plugin_options[meta_tags]" value="true" ' .$checked. '/>';
			echo _e( '<p>Add meta keywords tags for SEO, write your keywords separated by a comma, but not a space.</p>', 'japieventos' );
			echo '<input type="text" size="80" name="plugin_options[meta_tags_keys]" value="'.$tags.'" onfocus="javascript:if(this.value===\'tags...\'){this.select();}"></p>';
			echo _e('<p>Selecting this option will add the following code to the <code>&lt;head&gt;</code> of your pages, where </code>tags...</code> will be replaced with the code you insert above</p>', 'japieventos' );
			echo '<code>&lt;meta name="keywords" content="'.$tags.'"&gt;</code>';
		}
	endif; // google_verification_setting



/*	4)	Create functions to add above elements to pages */

	//	$options['google_verification']
	if ( ! function_exists( 'add_google_verification' ) ):
		function add_google_verification() {
			$options = get_option('plugin_options');
			$account = $options['google_verification_account'];
			$urlsite = $options['google_verification_domain'];
			echo '<meta name="google-site-verification" content="'.$account.'">'.PHP_EOL;
				  
			echo '<script>
			(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,"script","//www.google-analytics.com/analytics.js","ga");
			ga("create", "'.$account.'", "'.$urlsite.'");
			ga("require", "displayfeatures");
			ga("send", "pageview");
			</script>'.PHP_EOL;
		}
	endif; // add_google_verification


	//	$options['favicon']
	if ( ! function_exists( 'add_favicon' ) ):
		function add_favicon() {
			echo '<link rel="shortcut icon" href="/favicon.ico">'.PHP_EOL;
		}
	endif; // add_favicon

	//	$options['favicon_ithing']
	if ( ! function_exists( 'add_favicon_ithing' ) ):
		function add_favicon_ithing() {
			echo '<link rel="apple-touch-icon" href="/apple-touch-icon.png">'.PHP_EOL;
			echo '<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">'.PHP_EOL;
			echo '<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">'.PHP_EOL;
			echo '<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">'.PHP_EOL;
		}
	endif; // add_favicon_ithing

	//	$options['modernizr_js']
	if ( ! function_exists( 'add_modernizr_script' ) ):
		function add_modernizr_script() {
			$cache = cache_buster();
			wp_deregister_script( 'ieshiv' ); // get rid of IEShiv if it somehow got called too (IEShiv is included in Modernizr)
			wp_deregister_script( 'modernizr' ); // get rid of any native Modernizr
			echo '<script src="' .BP_THEME_URL. '/js/modernizr.js'.$cache.'"></script>'.PHP_EOL;
		}
	endif; // add_modernizr_script

	//	$options['ieshiv_script']
	if ( ! function_exists( 'add_ieshiv_script' ) ):
		function add_ieshiv_script() {
			$cache = cache_buster();
			echo '<!--[if lt IE 9]>'.PHP_EOL;
			echo '	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js" onload="window.ieshiv=true;"></script>'.PHP_EOL; // try getting from CDN
			echo '	<script>!window.ieshiv && document.write(unescape(\'%3Cscript src="' .BP_THEME_URL. '/js/ieshiv.js'.$cache.'"%3E%3C/script%3E\'))</script>'.PHP_EOL; // fallback to local if CDN fails
			echo '<![endif]-->'.PHP_EOL;
		}
	endif; // add_ieshiv_script

	//	$options['jquery_js']
	if ( ! function_exists( 'add_jquery_script' ) ):
		function add_jquery_script() {
			$cache = cache_buster();
			$options = get_option('plugin_options');
			$version = ($options['jquery_version']) ? $options['jquery_version'] : '1.10.2';
			wp_deregister_script( 'jquery' ); // get rid of WP's jQuery
			echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js"></script>'.PHP_EOL; // try getting from CDN
			echo '<script>window.jQuery || document.write(unescape(\'%3Cscript src="' .BP_THEME_URL. '/js/jquery.js'.$cache.'"%3E%3C/script%3E\'))</script>'.PHP_EOL; // fallback to local if CDN fails
		}
	endif; // add_jquery_script

	//	$options['plugins_js']
	if ( ! function_exists( 'add_plugin_script' ) ):
		function add_plugin_script() {
			$cache = cache_buster();
			echo '<script src="' .BP_THEME_URL. '/js/plugins.js'.$cache.'"></script>'.PHP_EOL;
		}
	endif; // add_plugin_script

	//	$options['site_js']
	if ( ! function_exists( 'add_site_script' ) ):
		function add_site_script() {
			$cache = cache_buster();
			echo '<script src="' .BP_THEME_URL. '/js/script-starter.js'.$cache.'"></script>'.PHP_EOL;
		}
	endif; // add_site_script

	//	$options['cache_buster']
	if ( ! function_exists( 'cache_buster' ) ):
		function cache_buster() {
			$options = get_option('plugin_options');
			return (isset($options['cache_buster']) && $options['cache_buster']) ? '?ver='.$options['cache_buster_version'] : '';
		}
	endif; // cache_buster
	
	//	$options['add_meta_tags']
	if ( ! function_exists( 'add_meta_tags' ) ):
		function add_meta_tags() {
			$options = get_option('plugin_options');
			$tags = $options['meta_tags_keys'];
			echo '<meta name="keywords" content="'.$tags.'">'.PHP_EOL;
		}
    endif; // cache_buster


/*	5)	Add Boilerplate options to page as requested */
		if (!is_admin() ) {

			// get the options
			$options = get_option('plugin_options');

			// check if each option is set (meaning it exists) and check if it is true (meaning it was checked)
			
			if (isset($options['meta_tags']) && $options['meta_tags'] && $options['meta_tags_keys'] && $options['meta_tags_keys'] !== 'tags...') {
				add_action('wp_print_styles', 'add_meta_tags');
			}

			if (isset($options['favicon']) && $options['favicon']) {
				add_action('wp_print_styles', 'add_favicon');
			}

			if (isset($options['favicon_ithing']) && $options['favicon_ithing']) {
				add_action('wp_print_styles', 'add_favicon_ithing');
			}

			if (isset($options['modernizr_js']) && $options['modernizr_js']) {
				add_action('wp_print_styles', 'add_modernizr_script');
			} else {
				// if Modernizr isn't selected, add IEShiv inside an IE Conditional Comment
				add_action('wp_print_styles', 'add_ieshiv_script');
			}

			if (isset($options['jquery_js']) && $options['jquery_js'] && isset($options['jquery_version']) && $options['jquery_version'] && $options['jquery_version'] !== '') {
				// check if should be loaded in <head> or at end of <body>
				$hook = (isset($options['jquery_head']) && $options['jquery_head']) ? 'wp_print_styles' : 'wp_footer';
				add_action($hook, 'add_jquery_script');
			}
			// for jQuery plug-ins, make sure jQuery was also set
			if (isset($options['jquery_js']) && $options['jquery_js'] && isset($options['plugins_js']) && $options['plugins_js']) {
				// check if should be loaded in <head> or at end of <body>
				$hook = (isset($options['jquery_head']) && $options['jquery_head']) ? 'wp_print_styles' : 'wp_footer';
				add_action($hook, 'add_plugin_script');
			}

			if (isset($options['site_js']) && $options['site_js']) {
				//add_action('wp_footer', 'add_site_script');
				// check if should be loaded in <head> or at end of <body>
				$hook = (isset($options['jquery_head']) && $options['jquery_head']) ? 'wp_print_styles' : 'wp_footer';
				add_action($hook, 'add_site_script');
			}
			
			if (isset($options['google_verification']) && $options['google_verification'] && $options['google_verification_account'] && $options['google_verification_account'] && $options['google_verification_domain']  && $options['google_verification_domain'] !== 'XXXXXXXXX...') {
				add_action('wp_print_styles', 'add_google_verification');
			}
			
		} // if (!is_admin() )

?>