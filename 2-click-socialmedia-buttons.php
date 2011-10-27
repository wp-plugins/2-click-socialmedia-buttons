<?php
/**
 * Plugin Name: 2 Click Social Media Buttons
 * Plugin URI: http://blog.ppfeufer.de/wordpress-plugin-2-click-social-media-buttons/
 * Description: Fügt die Buttons für Facebook-Like (Empfehlen), Twitter, Flattr und Googleplus dem deutschen Datenschutz entsprechend in euer WordPress ein.
 * Version: 0.12
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 */

define('TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION', '0.12');
if(!defined('PPFEUFER_FLATTRSCRIPT')) {
	define('PPFEUFER_FLATTRSCRIPT', 'http://cdn.ppfeufer.de/js/flattr/flattr.js');
}

/**
 * Sidebarwidget einbinden.
 *
 * @since
 */
//include_once('2-click-socialmedia-buttons-widget.php');

/**
 * Optionen auslesen.
 * @param string $parameter
 * @since 0.4
 */
if(!function_exists('twoclick_buttons_get_option')) {
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

		if($parameter == '') {
			return $twoclick_buttons_options;
		} else {
			return $twoclick_buttons_options[$parameter];
		}
	}
}

/**
 * Button Menü zum Dashboard hinzufügen.
 *
 * @since 0.4
 */
if(!function_exists('twoclick_buttons_options')) {
	function twoclick_buttons_options() {
				add_options_page('2-Klick-Buttons', '<img src="' . plugins_url('2-click-socialmedia-buttons/images/twoclick.jpg') . '" id="2-click-icon" alt="2 Click Social Media Buttons Icon" width="16" height="16" /> 2-Klick-Buttons', 'manage_options', 'twoclick-buttons-options', 'twoclick_buttons_options_page');
//		add_options_page('2-Klick-Buttons', '2-Klick-Buttons', 'manage_options', 'twoclick-buttons-options', 'twoclick_buttons_options_page');
	}
}

/**
 * Optionsseite generieren.
 *
 * @since 0.4
 */
if(!function_exists('twoclick_buttons_options_page')) {
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
//				twoclick_buttons_reset_options();

//				echo '<div id="message" class="updated fade">';
//				echo '<p><strong>';
//				_e('Settings resetted.', 'twoclick-buttons');
//				echo '</strong></p>';
//				echo '</div>';
			} elseif($_POST['twoclick_buttons_settings']['twoclick_buttons_maintenance_clear']) {
				/**
				 * Deleting all options from database.
				 */
//				twoclick_buttons_delete_options();

//				echo '<div id="message" class="updated fade">';
//				echo '<p><strong>';
//				_e('Settings deleted.', 'twoclick-buttons');
//				echo '</strong></p>';
//				echo '</div>';
			} else {
				/**
				 * Writing new options to database.
				 * @var array
				 */
				$array_Options = array(
					'twoclick_buttons_plugin_version' => (string) TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION,
					'twoclick_buttons_where' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_where']),
					'twoclick_buttons_twitter_reply' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_twitter_reply']),
					'twoclick_buttons_flattr_uid' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_flattr_uid']),
					'twoclick_buttons_display_page' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_page'])),
					'twoclick_buttons_display_facebook' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_facebook'])),
					'twoclick_buttons_display_twitter' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_twitter'])),
					'twoclick_buttons_display_flattr' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_flattr'])),
					'twoclick_buttons_display_googleplus' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_googleplus'])),
					'twoclick_buttons_display_facebook_perm' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_facebook_perm'])),
					'twoclick_buttons_display_twitter_perm' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_twitter_perm'])),
					'twoclick_buttons_display_googleplus_perm' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_googleplus_perm'])),
					'twoclick_buttons_display_flattr_perm' => (int) (!empty($_POST['twoclick_buttons_settings']['twoclick_buttons_display_flattr_perm'])),
					'twoclick_buttons_infotext_facebook' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infotext_facebook']),
					'twoclick_buttons_infotext_twitter' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infotext_twitter']),
					'twoclick_buttons_infotext_googleplus' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infotext_googleplus']),
					'twoclick_buttons_infotext_flattr' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infotext_flattr']),
					'twoclick_buttons_infotext_infobutton' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infotext_infobutton']),
					'twoclick_buttons_infotext_permaoption' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infotext_permaoption']),
					'twoclick_buttons_infolink' => (string) (@$_POST['twoclick_buttons_settings']['twoclick_buttons_infolink']),
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
			<h2><?php _e('Settings for 2-Click Social Media Buttons', 'twoclick-socialmedia'); ?></h2>
			<form method="post" action="" id="twoclick-buttons-options">
				<?php wp_nonce_field('twoclick-buttons-options'); ?>

				<table class="form-table" style="clear:none;">
					<tr>
						<th scope="row" valign="top"><?php _e('Display', 'twoclick-socialmedia'); ?></th>
						<td>
							<div style="float:right; text-align:center; width:120px;">
								<?php _e('Like this Plugin? Buy me a coffee.', 'twoclick-socialmedia'); ?><br />
								<a class="FlattrButton" style="display:none;" href="http://blog.ppfeufer.de/wordpress-plugin-2-click-social-media-buttons/"></a>
							</div>

							<!-- Welche Buttons sollen angezeigt werden -->
							<div>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_facebook') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_facebook]" id="twoclick_buttons_settings[twoclick_buttons_display_facebook]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_facebook]" style="display:inline-block; width:150px;"><?php _e('Enable Facebook', 'twoclick-socialmedia'); ?></label>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_facebook_perm') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_facebook_perm]" id="twoclick_buttons_settings[twoclick_buttons_display_facebook_perm]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_facebook_perm]"><?php _e('Option for permanent activation for Facebook', 'twoclick-socialmedia'); ?></label>
							</div>
							<div>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_twitter') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_twitter]" id="twoclick_buttons_settings[twoclick_buttons_display_twitter]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_twitter]" style="display:inline-block; width:150px;"><?php _e('Enable Twitter', 'twoclick-socialmedia'); ?></label>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_twitter_perm') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_twitter_perm]" id="twoclick_buttons_settings[twoclick_buttons_display_twitter_perm]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_twitter_perm]"><?php _e('Option for permanent activation for Twitter', 'twoclick-socialmedia'); ?></label>
							</div>
							<div>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_googleplus') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_googleplus]" id="twoclick_buttons_settings[twoclick_buttons_display_googleplus]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_googleplus]" style="display:inline-block; width:150px;"><?php _e('Enable Google+', 'twoclick-socialmedia'); ?></label>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_googleplus_perm') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_googleplus_perm]" id="twoclick_buttons_settings[twoclick_buttons_display_googleplus_perm]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_googleplus_perm]"><?php _e('Option for permanent activation for Google+', 'twoclick-socialmedia'); ?></label>
							</div>
							<div>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_flattr') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_flattr]" id="twoclick_buttons_settings[twoclick_buttons_display_flattr]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_flattr]" style="display:inline-block; width:150px;"><?php _e('Enable Flattr', 'twoclick-socialmedia'); ?></label>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_flattr_perm') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_flattr_perm]" id="twoclick_buttons_settings[twoclick_buttons_display_flattr_perm]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_flattr_perm]"><?php _e('Option for permanent activation for Flattr', 'twoclick-socialmedia'); ?></label>
							</div>

							<!-- Auf welchen Seiten sollen die Buttons angezeigt werden -->
							<div>
								<input type="checkbox" value="1" <?php if(twoclick_buttons_get_option('twoclick_buttons_display_page') == '1') echo 'checked="checked"'; ?> name="twoclick_buttons_settings[twoclick_buttons_display_page]" id="twoclick_buttons_settings[twoclick_buttons_display_page]" group="twoclick_buttons_display" />
								<label for="twoclick_buttons_settings[twoclick_buttons_display_page]"><?php _e('Display on CMS-Pages', 'twoclick-socialmedia'); ?></label>
							</div>
							<div>
								<?php _e('On singleposts the buttons will be shown by default. There is no option needed.', 'twoclick-socialmedia'); ?>
							</div>

							<!-- Position innerhalb des Artikels -->
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_where]"><?php _e('Position', 'twoclick-socialmedia'); ?></label>
								<select name="twoclick_buttons_settings[twoclick_buttons_where]">
									<option <?php if(twoclick_buttons_get_option('twoclick_buttons_where') == 'before') echo 'selected="selected"'; ?> value="before"><?php _e('Before the Post', 'twoclick-socialmedia'); ?></option>
									<option <?php if(twoclick_buttons_get_option('twoclick_buttons_where') == 'after') echo 'selected="selected"'; ?> value="after"><?php _e('After the Post', 'twoclick-socialmedia'); ?></option>
									<option <?php if(twoclick_buttons_get_option('twoclick_buttons_where') == 'shortcode') echo 'selected="selected"'; ?> value="shortcode"><?php _e('Manuall (Shortcode)', 'twoclick-socialmedia'); ?></option>
								</select>
							</div>
							<div>
								<?php _e('If you choose "Manuall (Shortcode)", you can use the shortcode <strong>[twoclick_buttons]</strong> inside your articles.', 'twoclick-socialmedia'); ?><br />
							</div>

						</td>
					</tr>

					<!--  Infotexte -->
					<tr>
						<th scope="row" valign="top"><?php _e('Infotext<br /><em>(optional)</em>', 'twoclick-socialmedia'); ?></th>
						<td>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infotext_facebook]" style="display:inline-block; width:80px;"><?php _e('Facebook:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infotext_facebook'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infotext_facebook]" id="twoclick_buttons_settings[twoclick_buttons_infotext_facebook]" minlength="2" />
							</div>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infotext_twitter]" style="display:inline-block; width:80px;"><?php _e('Twitter:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infotext_twitter'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infotext_twitter]" id="twoclick_buttons_settings[twoclick_buttons_infotext_twitter]" minlength="2" />
							</div>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infotext_googleplus]" style="display:inline-block; width:80px;"><?php _e('Google+:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infotext_googleplus'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infotext_googleplus]" id="twoclick_buttons_settings[twoclick_buttons_infotext_googleplus]" minlength="2" />
							</div>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infotext_flattr]" style="display:inline-block; width:80px;"><?php _e('Flattr:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infotext_flattr'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infotext_flattr]" id="twoclick_buttons_settings[twoclick_buttons_infotext_flattr]" cminlength="2" />
							</div>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infotext_infobutton]" style="display:inline-block; width:80px;"><?php _e('Infobutton:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infotext_infobutton'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infotext_infobutton]" id="twoclick_buttons_settings[twoclick_buttons_infotext_infobutton]" minlength="2" />
							</div>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infotext_permaoption]" style="display:inline-block; width:80px;"><?php _e('Permaoption:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infotext_permaoption'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infotext_permaoption]" id="twoclick_buttons_settings[twoclick_buttons_infotext_permaoption]" minlength="2" />
							</div>
							<div>
								<label for="twoclick_buttons_settings[twoclick_buttons_infolink]" style="display:inline-block; width:80px;"><?php _e('Infolink:', 'twoclick-socialmedia'); ?></label>
								<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_infolink'); ?>" name="twoclick_buttons_settings[twoclick_buttons_infolink]" id="twoclick_buttons_settings[twoclick_buttons_infolink]" minlength="2" />
								<span class="description"><?php _e('Links starting with http://', 'twoclick-socialmedia'); ?></span>
							</div>
						</td>
					</tr>

					<!-- Twitter -->
					<tr>
						<th scope="row" valign="top"><?php _e('Twitter', 'twoclick-socialmedia'); ?></th>
						<td>
							<label for="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" style="display:inline-block; width:80px;">RT @:</label>
							<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_twitter_reply'); ?>" name="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" id="twoclick_buttons_settings[twoclick_buttons_twitter_reply]" class="required" minlength="2" />
							<span class="description"><?php _e('Please use \'yourname\', <strong>not</strong> \'RT @yourname\'.', 'twoclick-socialmedia'); ?></span>
						</td>
					</tr>

					<!-- Flattr -->
					<tr>
						<th scope="row" valign="top"><label for="twoclick_buttons_settings[twoclick_buttons_flattr_uid]"><?php _e('Flattr', 'twoclick-socialmedia'); ?></label></th>
						<td>
							<label for="twoclick_buttons_settings[twoclick_buttons_flattr_uid]" style="display:inline-block; width:80px;"><?php _e('User:', 'twoclick-socialmedia'); ?></label>
							<input type="text" value="<?php echo twoclick_buttons_get_option('twoclick_buttons_flattr_uid'); ?>" name="twoclick_buttons_settings[twoclick_buttons_flattr_uid]" id="twoclick_buttons_settings[twoclick_buttons_flattr_uid]" class="required" minlength="2" />
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="Submit" value="<?php _e('Save Changes', 'twoclick-socialmedia'); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}

/**
 * Buttons in WordPress einbauen..
 *
 * @since 0.1
 */
if(!function_exists('twoclick_buttons')) {
	function twoclick_buttons($content) {
		global $post;

		$var_sHtml = twoclick_buttons_generate_html();
		$var_sWhere = 'twoclick_buttons_where';

		/**
		 * Manual Option
		 *
		 * @since 0.5
		 */
		if(twoclick_buttons_get_option('twoclick_buttons_where') == 'manual') {
			return $content;
		}

		/**
		 * Sind wir auf einer CMS-Seite?
		 *
		 * @since 0.5
		 */
		if(is_page() && twoclick_buttons_get_option('twoclick_buttons_display_page') == null) {
			return $content;
		}

		/**
		 * Sind wir auf der Startseite?
		 *
		 * @since 0.5
		 */
		if(is_home()) {
			return $content;
		}

		/**
		 * Sind wir in der Achiveanzeige?
		 *
		 * @since 0.5
		 */
		if(is_archive()) {
			return $content;
		}

		/**
		 * Sind wir in der Kategorieanzeige?
		 *
		 * @since 0.5
		 */
		if(is_category()) {
			return $content;
		}

		/**
		 * Sind wir in der Suche?
		 *
		 * @since 0.6
		 */
		if(is_search()) {
			return $content;
		}

		/**
		 * Soll der Button im Feed ausgeblendet werden?
		 *
		 * @since 0.5
		 */
		if(is_feed()) {
			return $content;
		}

		/**
		 * Wurde der Shortcode genutzt
		 *
		 * @since 0.5
		 */
		if(twoclick_buttons_get_option($var_sWhere) == 'shortcode') {
			return str_replace('[twoclick_buttons]', $var_sHtml, $content);
		} else {
			/**
			 * Wenn wir den Button abgeschalten haben
			 */
			if(get_post_meta($post->ID, 'twoclick_buttons') == null) {
				if(twoclick_buttons_get_option($var_sWhere) == 'before') {
					/**
					 * Vor dem Beitrag einfügen
					 */
					return $var_sHtml . '<p class="claer-after-twoclick"></p>' . $content;
				} else {
					/**
					 * Nach dem Beitrag einfügen
					 */
					return $content . $var_sHtml;
				}
			} else {
				/**
				 * Keinen Button einfügen
				 */
				return $content;
			}
		}
	}
} // END if(!function_exists('twoclick_buttons'))

/**
 * Post Excerpt generieren, wenn noch keiner da ist ...
 *
 * @since 0.10
 */
if(!function_exists('twoclick_buttons_generate_post_excerpt')) {
	function twoclick_buttons_generate_post_excerpt($excerpt, $maxlength) {
		if(function_exists('strip_shortcodes')) {
			$excerpt = strip_shortcodes($excerpt);
		}

		$excerpt = trim($excerpt);

		// Now lets strip any tags which dont have balanced ends
		// Need to put NGgallery tags in there - there are a lot of them and they are all different.
		$open_tags = "[simage,[[CP,[gallery,[imagebrowser,[slideshow,[tags,[albumtags,[singlepic,[album";
		$close_tags = "],]],],],],],],],]";
		$open_tag = explode(",", $open_tags);
		$close_tag = explode(",", $close_tags);

		foreach(array_keys($open_tag) as $key) {
			if(preg_match_all('/' . preg_quote($open_tag[$key]) . '(.*?)' . preg_quote($close_tag[$key]) . '/i', $excerpt, $matches)) {
				$excerpt = str_replace($matches[0], "", $excerpt);
			}
		} // END foreach(array_keys($open_tag) as $key)

		$excerpt = preg_replace('#(<wpg.*?>).*?(</wpg2>)#', '$1$2', $excerpt);

		// Support for qTrans
		if(function_exists('qtrans_use')) {
			global $q_config;
			$excerpt = qtrans_use($q_config['default_language'], $excerpt);
		} // END if(function_exists('qtrans_use'))
		$excerpt = strip_tags($excerpt);

		// Now lets strip off the youtube stuff.
		preg_match_all('#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=)([\w-]+)(.*?)player_embedded#i', $excerpt, $matches);
		$excerpt = str_replace($matches[0], "", $excerpt);

		preg_match_all('#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=|embed/)([\w-]+)(.*?)#i', $excerpt, $matches);
		$excerpt = str_replace($matches[0], "", $excerpt);

		if(strlen($excerpt) > $maxlength) {
			# If we've got multibyte support then we need to make sure we get the right length - Thanks to Kensuke Akai for the fix
			if(function_exists('mb_strimwidth')) {
				$excerpt = mb_strimwidth($excerpt, 0, $maxlength, " ...");
			} else {
				$excerpt = current(explode("SJA26666AJS", wordwrap($excerpt, $maxlength, "SJA26666AJS"))) . " ...";
			} // END if(function_exists('mb_strimwidth'))
		} // END if(strlen($excerpt) > $maxlength)

		return $excerpt;
	} // END function twoclick_buttons_generate_post_excerpt($excerpt, $maxlength)
} // END if(!function_exists('twoclick_buttons_generate_post_excerpt'))

/**
 * HTML generieren.
 *
 * @since 0.1
 */
if(!function_exists('twoclick_buttons_generate_html')) {
	function twoclick_buttons_generate_html() {
		if(!is_singular()) {
			return '<div class="twoclick_social_bookmarks_post_' . get_the_ID() . ' social_share_privacy"></div>' . twoclick_buttons_get_js();
		} else {
			return '<div class="twoclick_social_bookmarks_post_' . get_the_ID() . ' social_share_privacy"></div>';
		}
	}
}

/**
 * CSS in den Head auslagern.
 *
 * @since 0.1
 */
if(!function_exists('twoclick_buttons_head')) {
	function twoclick_buttons_head() {
		if(!is_admin()) {
			$var_sCss = plugins_url(basename(dirname(__FILE__)) . '/css/socialshareprivacy.css');

			echo '<!-- 2-Click Social Media Buttons by H.-Peter Pfeufer -->' . "\n" . '<link rel="stylesheet" id="cfq-css"  href="' . $var_sCss . '" type="text/css" media="all" />' . "\n";
			echo twoclick_facebook_opengraph_tags();
		}
	}
}

/**
 * Schreibe OpenGraph-Tags und Artikelbild in den <head>
 *
 * @since 0.7
 */
if(!function_exists('twoclick_facebook_opengraph_tags')) {
	function twoclick_facebook_opengraph_tags() {
		global $post;

		/* Nur Frontend */
		if(is_feed() || is_trackback() || !is_singular()) {
			return;
		}

		$array_Image = '';

		/* Source */
		/**
		 * Abfrage ob das Theme Post Thumbnails unterstützt.
		 * Einige Themes tun das einfach nicht.
		 *
		 * @since 0.7.1
		 */
		if(function_exists('get_post_thumbnail_id')) {
			$array_Image = wp_get_attachment_image_src(get_post_thumbnail_id($GLOBALS['post']->ID));
		}

		if(is_array($array_Image)) {
			$var_sFaceBookThumbnail = $array_Image['0'];
		} else {
			$var_sDefaultThumbnail = '';
			$var_sOutput = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $GLOBALS['post']->post_content, $array_Matches);

			if($var_sOutput > 0) {
				$var_sFaceBookThumbnail = $array_Matches[1][0];
			} else {
				$var_sFaceBookThumbnail = false;
			}
		}

		/* Ausgabe */
		echo "\n" . '<!-- Facebook Like Thumbnail -->' . "\n";
		if($var_sFaceBookThumbnail) {
			echo sprintf('<link href="%s" rel="image_src" />%s', esc_attr($var_sFaceBookThumbnail), "\n");
		}

		/**
		 * Post Excerpt suchen und eventuell setzen, da sonst bei Facebook und G+ nichts steht.
		 * Sollte der Post keinen eigenen Excerpt haben, wird einer aus dem Artikel extrahiert.
		 * Dieser wird dann, ganz Twitterstyle, auf 140 Zeichen begrenzt.
		 *
		 * @since 0.10
		 */
		$var_sExcerpt = '';
		if(has_excerpt()) {
			$var_sExcerpt = $post->post_excerpt;
		} else {
			$var_sExcerpt = twoclick_buttons_generate_post_excerpt($post->post_content, 140);
		}

		/**
		 * Open:Graph-Tags fuer FB-Like
		 *
		 * @since 0.7
		 */
		echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
		echo '<meta property="og:type" content="article"/>' . "\n";
		echo '<meta property="og:title" content="' . get_the_title() . '"/>' . "\n";
		echo '<meta property="og:url" content="' . get_permalink() . '"/>' . "\n";
		if($var_sFaceBookThumbnail) {
			echo '<meta property="og:image" content="' . esc_attr($var_sFaceBookThumbnail) . '"/>' . "\n";
		}
		echo '<meta property="og:description" content="' . esc_attr($var_sExcerpt) . '"/>' . "\n";
		echo '<!-- Facebook Like Thumbnail -->' . "\n";
	}
}

/**
 * JavaScript in den Footer auslagern.
 *
 * @since 0.1
 */
if(!function_exists('twoclick_buttons_footer')) {
	function twoclick_buttons_footer() {
		if(!is_admin()) {
			$var_sJavaScript = plugins_url(basename(dirname(__FILE__)) . '/js/social_bookmarks.js');

			echo '<!-- 2-Click Social Media Buttons by H.-Peter Pfeufer -->' . "\n" . '<script type="text/javascript" src="' . $var_sJavaScript . '"></script>';
			if(is_singular()) {
				twoclick_buttons_get_js();
			}
		}
	}
}

/**
 * JavaScript für aus Ausgabe generieren.
 *
 * @since 0.4
 */
if(!function_exists('twoclick_buttons_get_js')) {
	function twoclick_buttons_get_js() {
		global $post;

		if(!is_admin()) {
			$var_sPostId = get_the_ID();
			$var_sPermalink = get_permalink($var_sPostId);
			$var_sTitle = rawurlencode(get_the_title($var_sPostId));
			$var_sShowFacebook = (twoclick_buttons_get_option('twoclick_buttons_display_facebook')) ? 'on' : 'off';
			$var_sShowFacebookPerm = (twoclick_buttons_get_option('twoclick_buttons_display_facebook_perm')) ? 'on' : 'off';
			$var_sShowTwitter = (twoclick_buttons_get_option('twoclick_buttons_display_twitter')) ? 'on' : 'off';
			$var_sShowFlattr = (twoclick_buttons_get_option('twoclick_buttons_display_flattr')) ? 'on' : 'off';
			$var_sShowTwitterPerm = (twoclick_buttons_get_option('twoclick_buttons_display_twitter_perm')) ? 'on' : 'off';
			$var_sShowGoogleplus = (twoclick_buttons_get_option('twoclick_buttons_display_googleplus')) ? 'on' : 'off';
			$var_sShowGoogleplusPerm = (twoclick_buttons_get_option('twoclick_buttons_display_googleplus_perm')) ? 'on' : 'off';
			$var_sShowFlattrPerm = (twoclick_buttons_get_option('twoclick_buttons_display_flattr_perm')) ? 'on' : 'off';
			$var_sCss = plugins_url(basename(dirname(__FILE__)) . '/css/socialshareprivacy.css');
			$array_DummyImages = array(
				'facebook-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/empfehlen.png'),
				'twitter-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/tweet.png'),
				'googleplus-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/gplusone.png'),
				'flattr-dummy-image' => plugins_url(basename(dirname(__FILE__)) . '/images/flattr.png')
			);

			$var_sExcerpt = '';
			if(has_excerpt()) {
				$var_sExcerpt = rawurlencode($post->post_excerpt);
			} else {
				$var_sExcerpt = rawurlencode(twoclick_buttons_generate_post_excerpt($post->post_content, 250));
			}

			$var_sInfotextFacebook = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infotext_facebook') != '') {
				$var_sInfotextFacebook = '\'txt_info\' : \'' . twoclick_buttons_get_option('twoclick_buttons_infotext_facebook') . '\',';
			}

			$var_sInfotextTwitter = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infotext_twitter') != '') {
				$var_sInfotextTwitter = '\'txt_info\' : \'' . twoclick_buttons_get_option('twoclick_buttons_infotext_twitter') . '\',';
			}

			$var_sInfotextGoogleplus = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infotext_googleplus') != '') {
				$var_sInfotextGoogleplus = '\'txt_info\' : \'' . twoclick_buttons_get_option('twoclick_buttons_infotext_googleplus') . '\',';
			}

			$var_sInfotextFlattr = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infotext_flattr') != '') {
				$var_sInfotextFlattr = '\'txt_info\' : \'' . twoclick_buttons_get_option('twoclick_buttons_infotext_flattr') . '\',';
			}

			$var_sInfotextInfobutton = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infotext_infobutton') != '') {
				$var_sInfotextInfobutton = '\'txt_help\' : \'' . twoclick_buttons_get_option('twoclick_buttons_infotext_infobutton') . '\',';
			}

			$var_sInfotextPermaoption = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infotext_permaoption') != '') {
				$var_sInfotextPermaoption = '\'settings_perma\' : \'' . twoclick_buttons_get_option('twoclick_buttons_infotext_permaoption') . '\',';
			}

			$var_sInfolink = '';
			if(twoclick_buttons_get_option('twoclick_buttons_infolink') != '') {
				$var_sInfolink = '\'info_link\' : \'' . trim(twoclick_buttons_get_option('twoclick_buttons_infolink')) . '\',';
			}

			$var_sJavaScript = '<script type="text/javascript">
			jQuery(document).ready(function($){
				if($(\'.twoclick_social_bookmarks_post_' . $var_sPostId . '\')){
					$(\'.twoclick_social_bookmarks_post_' . $var_sPostId . '\').socialSharePrivacy({
						services : {
							facebook : {
								\'dummy_img\'		: \'' . $array_DummyImages['facebook-dummy-image'] . '\',
								\'the_permalink\'	: \'' . $var_sPermalink . '\',
								\'status\'			: \'' . $var_sShowFacebook . '\',
								' . $var_sInfotextFacebook . '
								\'perma_option\'	: \'' . $var_sShowFacebookPerm . '\'
							},
							twitter : {
								\'reply_to\'		: \'' . twoclick_buttons_get_option('twoclick_buttons_twitter_reply') . '\',
								\'dummy_img\'		: \'' . $array_DummyImages['twitter-dummy-image'] . '\',
								\'the_permalink\'	: \'' . $var_sPermalink . '\',
								\'tweet_text\'		: \'' . $var_sTitle . '\',
								\'status\'			: \'' . $var_sShowTwitter . '\',
								' . $var_sInfotextTwitter . '
								\'perma_option\'	: \'' . $var_sShowTwitterPerm . '\'
							},
							gplus : {
								\'dummy_img\'		: \'' . $array_DummyImages['googleplus-dummy-image'] . '\',
								\'the_permalink\'	: \'' . $var_sPermalink . '\',
								\'status\'			: \'' . $var_sShowGoogleplus . '\',
								' . $var_sInfotextGoogleplus . '
								\'perma_option\'	: \'' . $var_sShowGoogleplusPerm . '\'
							},
							flattr : {
								\'uid\'				: \'' . twoclick_buttons_get_option('twoclick_buttons_flattr_uid') . '\',
								\'dummy_img\'		: \'' . $array_DummyImages['flattr-dummy-image'] . '\',
								\'status\'			: \'' . $var_sShowFlattr . '\',
								\'the_permalink\'	: \'' . $var_sPermalink . '\',
								\'the_title\'		: \'' . $var_sTitle . '\',
								\'the_excerpt\'		: \'' . $var_sExcerpt . '\',
								' . $var_sInfotextFlattr . '
								\'perma_option\'	: \'' . $var_sShowFlattrPerm . '\'
							},
						},
							' . $var_sInfotextInfobutton . '
							' . $var_sInfotextPermaoption . '
							' . $var_sInfolink . '
							\'css_path\'		: \'' . $var_sCss . '\'
					});
				}
			});
			</script>';

			/**
			 * Abfrage, wo wir sind. Ob Einzelseite oder Index.
			 *
			 * since 0.6
			 */
			if(is_singular()) {
				echo $var_sJavaScript;
			} else {
				return $var_sJavaScript;
			}
		}
	}
}

/**
 * Changelog bei Pluginupdate ausgeben.
 *
 * @since 0.1
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
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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
					if(version_compare($version, TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION, ">")) {
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
 *
 * @since 0.4
 */
if(!function_exists('twoclick_buttons_init')) {
	function twoclick_buttons_init() {
		if(function_exists('register_setting')) {
			register_setting('twoclick_buttons-options', 'twoclick_buttons_settings');
		}

	/**
	 * Sprachdatei wählen
	 */
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('twoclick-socialmedia', false, dirname(plugin_basename( __FILE__ )) . '/l10n/');
		}
	}
}

/**
 * Optionen updaten ...
 *
 * @param array $array_Data
 * @since 0.4
 */
if(!function_exists('twoclick_buttons_update_options')) {
	function twoclick_buttons_update_options($array_Data) {
		$array_Options = array_merge((array) get_option('twoclick_buttons_settings'), $array_Data);

		update_option('twoclick_buttons_settings', $array_Options);
		wp_cache_set('twoclick_buttons_settings', $array_Options);

		return;
	}
}

/**
 * Actions abfeuern.
 *
 * @since 0.1
 */
if(!is_admin()) {
	/**
	 * jQuery anfordern und bei Bedarf einbinden.
	 *
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
