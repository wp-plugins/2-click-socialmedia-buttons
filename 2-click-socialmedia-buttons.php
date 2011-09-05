<?php
/**
 * Plugin Name: 2 Click Social Media Buttons
 * Plugin URI: http://blog.ppfeufer.de/wordpress-plugin-2-click-socialmedia-buttons/
 * Description: Fügt die Buttons für Facebook-Like (Empfehlen), Twitter und Googleplus dem deutschen Datenschutz entsprechend in euer WordPress ein.
 * Version: 0.3
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 */

define('TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION', '0.3');
//if(!defined('PPFEUFER_FLATTRSCRIPT')) {
//	define('PPFEUFER_FLATTRSCRIPT', 'http://cdn.ppfeufer.de/js/flattr/flattr.js');
//}

/**
 * Button Menü zum Dashboard hinzufügen.
 *
 * @since coming soon
 */
//function twoclick_buttons_options() {
//	add_options_page('2-Klick-Buttons', '<img src="' . plugins_url('2-click-socialmedia-buttons/images/icon.png') . '" id="2-click-icon" alt="2 Click Social Media Buttons Icon" /> 2-Klick-Buttons', 'manage_options', 'twoclick-buttons-options', 'twoclick_buttons_options_page');
//	add_options_page('2-Klick-Buttons', '2-Klick-Buttons', 'manage_options', 'twoclick-buttons-options', 'twoclick_buttons_options_page');
//}

/**
 * Optionsseite generieren.
 * @since coming soon
 */
//function twoclick_buttons_options_page() {
//	/**
//	 * JavaScript für Flattr einfügen
//	 */
//	if(!defined('PPFEUFER_FLATTRSCRIPT_IS_LOADED')) {
//		echo '<script type="text/javascript" src="' . PPFEUFER_FLATTRSCRIPT . '"></script>';
//		define('PPFEUFER_FLATTRSCRIPT_IS_LOADED', true);
//	}
//
//	/**
//	 * Status von $_POST abfangen.
//	 */
//	if(!empty($_POST)) {
//		/**
//		 * Validate the nonce.
//		 */
//		check_admin_referer('twoclick-buttons-options');
//
//		if($_POST['twoclick_buttons_settings']['twoclick_buttons_maintenance_reset']) {
//			/**
//			 * Resetting options to defaults.
//			 */
//			twoclick_buttons_reset_options();
//
//			echo '<div id="message" class="updated fade">';
//			echo '<p><strong>';
//			_e('Settings resetted.', 'twoclick-buttons');
//			echo '</strong></p>';
//			echo '</div>';
//		} elseif($_POST['twoclick_buttons_settings']['twoclick_buttons_maintenance_clear']) {
//			/**
//			 * Deleting all options from database.
//			 */
//			twoclick_buttons_delete_options();
//
//			echo '<div id="message" class="updated fade">';
//			echo '<p><strong>';
//			_e('Settings deleted.', 'twoclick-buttons');
//			echo '</strong></p>';
//			echo '</div>';
//		} else {
//			/**
//			 * Writing new options to database.
//			 * @var array
//			 */
//			$array_Options = array(
//				'twoclick_buttons_plugin_version' => (string) TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION,
//				'twoclick_buttons_where' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_where']),
//				'twoclick_buttons_display_page' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_page'])),
//				'twoclick_buttons_display_front' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_front'])),
//				'twoclick_buttons_display_archive' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_archive'])),
//				'twoclick_buttons_display_category' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_category'])),
//			);
//
//			twoclick_buttons_update_options($array_Options);
//
//			echo '<div id="message" class="updated fade">';
//			echo '<p><strong>';
//			_e('Settings saved.', 'twoclick-buttons');
//			echo '</strong></p>';
//			echo '</div>';
//		}
//	}
//}

/**
 * Buttons in WordPress einbauen..
 *
 * @since 0.1
 */
function twoclick_buttons($content) {
	global $post;

	$var_sHtml = twoclick_buttons_generate_html();

	/**
	 * Nach dem Beitrag (Einzelseite) einfügen.
	 */
	if(is_singular()) {
		return $content . $var_sHtml;
	} else {
		return $content;
	}
}

/**
 * HTML generieren.
 *
 * @since 0.1
 */
function twoclick_buttons_generate_html() {
	$var_sHtml = '<div class="twoclick_social_bookmarks"></div>';

	return $var_sHtml;
}

/**
 * CSS in den Head auslagern.
 *
 * @since 0.1
 */
function twoclick_buttons_head() {
	if(!is_admin()) {
		$var_sCss = plugins_url(basename(dirname(__FILE__)) . '/css/socialshareprivacy.css');

		echo '<!-- 2-Click Social Media Buttons by H.-Peter Pfeufer -->' . "\n" . '<link rel="stylesheet" id="cfq-css"  href="' . $var_sCss . '" type="text/css" media="all" />';
	}
}

/**
 * JavaScript in den Footer auslagern.
 *
 * @since 0.1
 */
function twoclick_buttons_footer() {
	if(!is_admin()) {
		$var_sJavaScript = plugins_url(basename(dirname(__FILE__)) . '/js/social_bookmarks.js');
		wp_enqueue_script('jquery');
		echo '<!-- 2-Click Social Media Buttons by H.-Peter Pfeufer -->' . "\n" . '<script type="text/javascript" src="' . $var_sJavaScript . '"></script>';
	}
}

/**
 * Changelog bei Pluginupdate ausgeben.
 *
 * @since 1.9.0
 */
if(!function_exists('twoclick_buttons_update_notice')) {
	function twoclick_buttons_update_notice() {
		$url = 'http://plugins.trac.wordpress.org/browser/2-click-socialmedia-buttons/trunk/readme.txt?format=txt';
		$data = '';

		if(ini_get('allow_url_fopen')) {
			$data = file_get_contents($url);
		} else {
			if(function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				$data = curl_exec($ch);
				curl_close($ch);
			} // END if(function_exists('curl_init'))
		} // END if(ini_get('allow_url_fopen'))


		if($data) {
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote(TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION) . '\s*=|$)~Uis';

			if(preg_match($regexp, $data, $matches)) {
				$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

				echo '</div><div class="update-message" style="font-weight: normal;"><strong>What\'s new:</strong>';
				$ul = false;
				$version = 99;

				foreach($changelog as $index => $line) {
					if(version_compare($version, TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION,">")) {
						if(preg_match('~^\s*\*\s*~', $line)) {
							if(!$ul) {
								echo '<ul style="list-style: disc; margin-left: 20px;">';
								$ul = true;
							} // END if(!$ul)

							$line = preg_replace('~^\s*\*\s*~', '', $line);
							echo '<li>' . $line . '</li>';
						} else {
							if($ul) {
								echo '</ul>';
								$ul = false;
							} // END if($ul)

							$version = trim($line, " =");
							echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
						} // END if(preg_match('~^\s*\*\s*~', $line))
					} // END if(version_compare($version, TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION,">"))
				} // END foreach($changelog as $index => $line)

				if($ul) {
					echo '</ul><div style="clear: left;"></div>';
				} // END if($ul)

				echo '</div>';
			} // END if(preg_match($regexp, $data, $matches))
		} // END if($data)
	} // END function twoclick_buttons_update_notice()
} // END if(!function_exists('twoclick_buttons_update_notice'))

/**
 * Actions abfeuern.
 *
 * @since 0.1
 */
//if(!is_admin()) {
//	wp_enqueue_script('jquery');
//}
add_action('wp_head', 'twoclick_buttons_head');
add_action('wp_footer', 'twoclick_buttons_footer');
/* Nur wenn User auch der Admin ist, sind die Adminoptionen zu sehen */
if(is_admin()) {
//	add_action('admin_menu', 'twoclick_buttons_options');
//	add_action('admin_init', 'twoclick_buttons_init');

	// Updatemeldung
	if(ini_get('allow_url_fopen') || function_exists('curl_init')) {
		add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'twoclick_buttons_update_notice');
	}
}

/**
 * Filter zum Blog hinzufügen.
 *
 * @since 0.1
 */
add_filter('the_content', 'twoclick_buttons', 8);
?>