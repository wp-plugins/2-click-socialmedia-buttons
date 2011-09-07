<?php
/**
 * Plugin Name: 2 Click Social Media Buttons
 * Plugin URI: http://blog.ppfeufer.de/wordpress-plugin-2-click-social-media-buttons/
 * Description: Fügt die Buttons für Facebook-Like (Empfehlen), Twitter und Googleplus dem deutschen Datenschutz entsprechend in euer WordPress ein.
 * Version: 0.5.2
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 */

define('TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION', '0.5.2');
if(!defined('PPFEUFER_FLATTRSCRIPT')) {
	define('PPFEUFER_FLATTRSCRIPT', 'http://cdn.ppfeufer.de/js/flattr/flattr.js');
}

/**
 * Optionen auslesen.
 * @param string $parameter
 * @since 0.4
 */
function twoclick_buttons_get_option($parameter = '') {
	/**
	 * Prüfen ob das Formular abgesendet wurde.
	 * Wenn nicht, importiere $twoclick_buttons_options,
	 * ansonsten lade sie neu.
	 */
	if(!isset($_POST)) {
		global $twoclick_buttons_options;
	} else {
		$twoclick_buttons_options = get_option('twoclick_buttons_settings');
	}

	if ($parameter == '') {
		return $twoclick_buttons_options;
	} else {
		return $twoclick_buttons_options[$parameter];
	}
}

/**
 * Button Menü zum Dashboard hinzufügen.
 *
 * @since 0.4
 */
function twoclick_buttons_options() {
//	add_options_page('2-Klick-Buttons', '<img src="' . plugins_url('2-click-socialmedia-buttons/images/icon.png') . '" id="2-click-icon" alt="2 Click Social Media Buttons Icon" /> 2-Klick-Buttons', 'manage_options', 'twoclick-buttons-options', 'twoclick_buttons_options_page');
	add_options_page('2-Klick-Buttons', '2-Klick-Buttons', 'manage_options', 'twoclick-buttons-options', 'twoclick_buttons_options_page');
}

/**
 * Optionsseite generieren.
 * @since 0.4
 */
function twoclick_buttons_options_page() {
	/**
	 * JavaScript für Flattr einfügen
	 */
	if(!defined('PPFEUFER_FLATTRSCRIPT_IS_LOADED')) {
		echo '<script type="text/javascript" src="' . PPFEUFER_FLATTRSCRIPT . '"></script>';
		define('PPFEUFER_FLATTRSCRIPT_IS_LOADED', true);
	}

	/**
	 * Status von $_POST abfangen.
	 */
	if(!empty($_POST)) {
		/**
		 * Validate the nonce.
		 */
		check_admin_referer('twoclick-buttons-options');

		if($_POST['twoclick_buttons_settings']['twoclick_buttons_maintenance_reset']) {
			/**
			 * Resetting options to defaults.
			 */
//			twoclick_buttons_reset_options();
//
//			echo '<div id="message" class="updated fade">';
//			echo '<p><strong>';
//			_e('Settings resetted.', 'twoclick-buttons');
//			echo '</strong></p>';
//			echo '</div>';
		} elseif($_POST['twoclick_buttons_settings']['twoclick_buttons_maintenance_clear']) {
			/**
			 * Deleting all options from database.
			 */
//			twoclick_buttons_delete_options();
//
//			echo '<div id="message" class="updated fade">';
//			echo '<p><strong>';
//			_e('Settings deleted.', 'twoclick-buttons');
//			echo '</strong></p>';
//			echo '</div>';
		} else {
			/**
			 * Writing new options to database.
			 * @var array
			 */
			$array_Options = array(
				'twoclick_buttons_plugin_version' => (string) TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION,
				'twoclick_buttons_where' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_where']),
				'twoclick_buttons_facebook_appID' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_facebook_appID']),
				'twoclick_buttons_twitter_reply' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_twitter_reply']),
				'twoclick_buttons_display_page' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_page'])),
//				'twoclick_buttons_display_front' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_front'])),
//				'twoclick_buttons_display_archive' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_archive'])),
//				'twoclick_buttons_display_category' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_category'])),
			);

			twoclick_buttons_update_options($array_Options);

			echo '<div id="message" class="updated fade">';
			echo '<p><strong>Einstellungen gespeichert</strong></p>';
			echo '</div>';
		}
	}
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br /></div>
		<h2>Einstellungen für 2-Click Social Media Buttons</h2>
		<form method="post" action="" id="twoclick-buttons-options">
			<?php wp_nonce_field('twoclick-buttons-options'); ?>
			<div style="float:right; text-align:center; width:120px;">
				Spendier mir nen Kaffee, wenn Dir das Plugin gefällt :-)<br />
				<a class="FlattrButton" style="display:none;" href="http://blog.ppfeufer.de/wordpress-plugin-2-click-social-media-buttons/"></a>
			</div>
			<table class="form-table" style="clear:none;">
				<tr>
					<th scope="row" valign="top"><label for="twoclick_buttons_settings[twoclick_buttons_where]">Anzeige</label></th>
					<td>
						<div>
							<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_page') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_page]" id="twoclick_buttons_settings[twoclick_buttons_display_page]" group="twoclick_buttons_display" />
							<label for="twoclick_buttons_settings[twoclick_buttons_display_page]">Auch auf CMS-Seiten anzeigen</label>
						</div>
						<div>
							In den Einzelartikeln wird das Plugin per default eingebunden. Dies bedarf keiner Option.
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label for="twoclick_buttons_settings[twoclick_buttons_where]">Position</label></th>
					<td>
						<select name="twoclick_buttons_settings[twoclick_buttons_where]">
							<option <?php if(twoclick_buttons_get_option('twoclick_buttons_where') == 'before') echo 'selected="selected"'; ?> value="before">Vor dem Artikel</option>
							<option <?php if(twoclick_buttons_get_option('twoclick_buttons_where') == 'after') echo 'selected="selected"'; ?> value="after">Nach dem Artikel</option>
							<option <?php if(twoclick_buttons_get_option('twoclick_buttons_where') == 'shortcode') echo 'selected="selected"'; ?> value="shortcode">Manuell (Shortcode)</option>
						</select>
						<div>
							Ist die Option "Manuell" gewählt, so können die Buttons mittels des Shortcodes <strong>[twoclick_buttons]</strong> in den Artikel eingebunden werden.<br />
						</div>
						<div style="background-color:#ffebe8; border:1px solid #c00;">Bitte beachte unbedingt, dies nicht zu tun, wenn die Buttons dadurch auf der Startseite auftauchen könnten, da es auf Grund der Struktur des Scriptes noch Probleme mit der Einbindung auf der Startseite gibt.</div>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label for="twoclick_buttons_settings[twoclick_buttons_facebook_appID]">Facebook APP-ID</label></th>
					<td>
						<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_facebook_appID'); ?>" name="twoclick_buttons_settings[twoclick_buttons_facebook_appID]" id="twoclick_buttons_settings[twoclick_buttons_facebook_appID]" class="required" minlength="2" />
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label for="twoclick_buttons_settings[twoclick_buttons_twitter_reply]">Twittername</label></th>
					<td>
						RT @<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_twitter_reply'); ?>" name="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" id="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" class="required" minlength="2" />
						<span class="description">Bitte benutze das Format 'deinname', <strong>nicht</strong> 'RT @deinname'.</span>
					</td>
				</tr>
			</table>
			<p>Hinweis zur Facebook App-ID<br />
				<br />
				Für den "Empfehlen"-Button von Facebook benötigt man eine Facebook App-ID. Diese kann man sich mit seinem verifizierten Facebook-Konto auf den Developer-Seiten erzeugen.<br />
				<br />
				Einloggen bei Facebook<br />
				Konto verifizieren mittels Handy-Nummer (oder Kreditkartendaten)<br />
				<a href="https://www.facebook.com/settings?tab=mobile">https://www.facebook.com/settings?tab=mobile</a> Option Handy-Nr.:<br />
				Handy-Nr. eintragen und anschließend per SMS empfangenen Bestätigungscode in das Feld auf der rechten Seite eintragen.<br />
				Entwickler-Seite aufrufen<br />
				<a href="http://developers.facebook.com/docs/reference/plugins/like/">http://developers.facebook.com/docs/reference/plugins/like/</a><br />
				Dort in der Box unter "Step 1" auf "Get Code" klicken und die App-ID aus dem angezeigten Code-Teil entnehmen.<br />
			</p>
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Save Changes', 'wp-twitter-button'); ?>" />
			</p>
		</form>
	</div>
	<?php
}

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
//	if(is_singular()) {
//		if(twoclick_buttons_get_option('twoclick_buttons_where') == 'manual') {
//			return $content;
//		}
//		if(twoclick_buttons_get_option('twoclick_buttons__display_page') == null && is_page()) {
//			return $content;
//		}
//
//		if(twoclick_buttons_get_option('twoclick_buttons_where') == 'before') {
//			return $var_sHtml . $content;
//		} elseif(twoclick_buttons_get_option('twoclick_buttons_where') == 'after') {
//			return $content . $var_sHtml;
//		}
//	} else {
//		return $content;
//	}

/**
	 * Manual Option
	 */
	if(twoclick_buttons_get_option('twoclick_buttons_where') == 'manual') {
		return $content;
	}

	/**
	 * Sind wir auf einer CMS-Seite?
	 */
	if(twoclick_buttons_get_option('twoclick_buttons_display_page') == null && is_page()) {
		return $content;
	}

	/**
	 * Sind wir auf der Startseite?
	 */
	if(twoclick_buttons_get_option('twoclick_buttons_display_front') == null && is_home()) {
		return $content;
	}

	/**
	 * Sind wir in der Achiveanzeige?
	 * @since 1.4.0
	 */
	if(twoclick_buttons_get_option('twoclick_buttons_display_archive') == null && is_archive()) {
		return $content;
	}

	/**
	 * Sind wir in der Kategorieanzeige?
	 * @since 1.4.0
	 */
	if(twoclick_buttons_get_option('twoclick_buttons_display_category') == null && is_category()) {
		return $content;
	}

	/**
	 * Ist es ein Feed
	 */
//	if(is_feed()) {
//		$button = twoclick_buttons_generate_html();
//		$where = 'twoclick_buttons_rss_where';
//	} else {
		$button = twoclick_buttons_generate_html();
		$where = 'twoclick_buttons_where';
//	}

	/**
	 * Soll der Button im Feed ausgeblendet werden?
	 */
	if(is_feed() && twoclick_buttons_get_option('twoclick_buttons_display_feed') == null) {
		return $content;
	}

	/**
	 * Wurde der Shortcode genutzt
	 */
	if(twoclick_buttons_get_option($where) == 'shortcode') {
		return str_replace('[twoclick_buttons]', $button, $content);
	} else {
		/**
		 * Wenn wir den Button abgeschalten haben
		 */
		if(get_post_meta($post->ID, 'twoclick_buttons') == null) {
			if(twoclick_buttons_get_option($where) == 'beforeandafter') {
				/**
				 * Vor und nach dem Beitrag einfügen
				 */
				return $button . $content . $button;
			} else if(twoclick_buttons_get_option($where) == 'before') {
				/**
				 * Vor dem Beitrag einfügen
				 */
				return $button . $content;
			} else {
				/**
				 * Nach dem Beitrag einfügen
				 */
				return $content . $button;
			}
		} else {
			/**
			 * Keinen Button einfügen
			 */
			return $content;
		}
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
		$var_sCss = plugins_url(basename(dirname(__FILE__)) . '/css/socialshareprivacy.css');
		$array_DummyIMages = array(
			'facebook-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/empfehlen.png'),
			'twitter-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/tweet.png'),
			'googleplus-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/gplusone.png')
		);
		wp_enqueue_script('jquery');
		echo '<!-- 2-Click Social Media Buttons by H.-Peter Pfeufer -->' . "\n" . '<script type="text/javascript" src="' . $var_sJavaScript . '"></script>';
		echo '<script type="text/javascript">
		jQuery(document).ready(function($){
			if($(\'.twoclick_social_bookmarks\')){
				$(\'.twoclick_social_bookmarks\').socialSharePrivacy({
					services : {
						facebook : {
							\'app_id\'		: \'' . twoclick_buttons_get_option('twoclick_buttons_facebook_appID') . '\',
							\'dummy_img\'	: \'' . $array_DummyIMages['facebook-dummy-image'] . '\'
						},
						twitter : {
							\'reply_to\'	: \'' . twoclick_buttons_get_option('twoclick_buttons_twitter_reply') . '\',
							\'dummy_img\'	: \'' . $array_DummyIMages['twitter-dummy-image'] . '\'
						},
						gplus : {
							\'dummy_img\'	: \'' . $array_DummyIMages['googleplus-dummy-image'] . '\'
						},
						\'css_path\'		: \'' . $var_sCss . '\'
					}
				});
			}
		});
		</script>';
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
 * Variablen registrieren.
 * @since 0.4
 */
function twoclick_buttons_init() {
	if(function_exists('register_setting')) {
		register_setting('twoclick_buttons-options', 'twoclick_buttons_settings');
	}

	/**
	 * Sprachdatei wählen
	 */
//	if(function_exists('load_plugin_textdomain')) {
//		load_plugin_textdomain('wp-twitter-button', false, dirname(plugin_basename( __FILE__ )) . '/languages/');
//	}
}

/**
 * Optionen updaten ...
 *
 * @param array $array_Data
 * @since 0.4
 */
function twoclick_buttons_update_options($array_Data) {
	$array_Options = array_merge((array) get_option('twoclick_buttons_settings'), $array_Data);

	update_option('twoclick_buttons_settings', $array_Options);
	wp_cache_set('twoclick_buttons_settings', $array_Options);

	return;
}

/**
 * Actions abfeuern.
 *
 * @since 0.1
 */
if(!is_admin()) {
	/**
	 * jQuery einbinden.
	 * @since 0.4
	 */
	wp_enqueue_script('jquery');

	// Aktionen
	add_action('wp_head', 'twoclick_buttons_head');
	add_action('wp_footer', 'twoclick_buttons_footer');
}
/* Nur wenn User auch der Admin ist, sind die Adminoptionen zu sehen */
if(is_admin()) {
	add_action('admin_menu', 'twoclick_buttons_options');
	add_action('admin_init', 'twoclick_buttons_init');

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