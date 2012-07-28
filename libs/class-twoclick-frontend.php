<?php
/**
 * Avoid direct calls to this file
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');

	exit();
} // END if(!function_exists('add_action'))

/**
 * The Frontend Class
 *
 * @since 1.0
 * @author ppfeufer
 *
 * @package 2 Click Social Media Buttons
 */
if(!class_exists('Twoclick_Social_Media_Buttons_Frontend')) {
	class Twoclick_Social_Media_Buttons_Frontend {
		private $var_sOptionsName = 'twoclick_buttons_settings';
		private $var_sPostExcerpt;

		private $array_TwoclickButtonsOptions;

		/**
		 * PHP 4 Constructor
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function Twoclick_Social_Media_Buttons_Frontend() {
			Twoclick_Social_Media_Buttons_Frontend::__construct();
		} // END function Twoclick_Social_Media_Buttons_Frontend()

		/**
		 * PHP 5 Constructor
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function __construct() {
			$this->array_TwoclickButtonsOptions = get_option($this->var_sOptionsName);

			if(!is_admin()) {
				/**
				 * Plugin initialisieren
				 *
				 * @since 0.1
				 * @author ppfeufer
				 */
				add_action('init', array(
					$this, '_enqueue'
				));

				/**
				 * Sidebarwidget, wenn es angezeigt werden soll
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'] == true) {
					require_once(plugin_dir_path(__FILE__) . 'class-twoclick-sidebar-widget.php');
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'])

				/**
				 * Wenn die OpenGraph Tags nicht abgeschalten werden sollen, OpenGraph-Klasse laden
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_opengraph_disable'] == false) {
// 					require_once(plugin_dir_path(__FILE__) . 'class-twoclick-opengraph.php');
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_sidebar_widget'])

				/**
				 * Aktionen in den Header des Frontends schreiben
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				add_action('wp_head', array(
					$this,
					'_enqueue_head'
				));

				/**
				 * Kurzbeschreibung über den Buttons anzeigen, sofern ausgefüllt
				 *
				 * @since 1.0
				 * @author ppfeufer
				 */
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext'])) {
					add_action('twoclick_intro', array(
						$this,
						'_get_the_intro'
					));
				}

				/**
				 * Buttons an den Content übergeben
				 *
				 * @since 0.1
				 * @author ppfeufer
				 */
				add_filter('the_content', array(
					$this,
					'_get_buttons'
				), 8);
			} // END if(!is_admin())
		} // END function __construct()

		/**
		 * <[ Helper ]>
		 * Das jQuery-Plugin zu Wordpress hinzufügen.
		 * Das CSS zu WordPress hinzufügen.
		 *
		 * Das CSS wird durch einen Filter an WordPress übergeben.
		 * Dieser trägt den Namen 'twoclick-css' und kann beeinflusst werden.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue() {
			if(!is_admin()) {
// 				$var_sJavaScript = plugins_url('/js/social_bookmarks.js', dirname(__FILE__));
				$var_sJavaScript = plugins_url('/js/social_bookmarks-min.js', dirname(__FILE__));
// 				$var_sCss = apply_filters('twoclick-css', plugins_url('/css/socialshareprivacy.css', dirname(__FILE__)));
				$var_sCss = apply_filters('twoclick-css', plugins_url('/css/socialshareprivacy-min.css', dirname(__FILE__)));

				/**
				 * jQuery Plugin
				 */
				wp_register_script('twoclick-social-media-buttons-jquery', $var_sJavaScript, array(
					'jquery'
				), $this->_get_plugin_version(), true);

				wp_enqueue_script('twoclick-social-media-buttons-jquery');

				/**
				 * CSS
				 */
				wp_register_style('twoclick-social-media-buttons', $var_sCss, '', $this->_get_plugin_version());
				wp_enqueue_style('twoclick-social-media-buttons');
			} // END if(!is_admin())
		} // END function _enqueue()

		/**
		 * <[ Helper ]>
		 * Daten in den <head>-Bereich des HTML vom Frontend schreiben
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		function _enqueue_head() {
			/**
			 * OpenGraph-Tags in den <head> des Frontends schreiben, sofern dies gewünscht ist.
			 *
			 * @since 0.7
			 * @author ppfeufer
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_opengraph_disable'] == false) {
				$this->_get_opengraph_tags();
			}

			/**
			 * Custom CSS
			 * Benutzerdefiniertes CSS in den <head> des Frontends einfügen, sofern ausgefüllt.
			 *
			 * @since 1.0
			 * @author ppfeufer
			 */
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css'])) {
				$this->_get_custom_css();
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css']))
		} // END function _enqueue_head()

		/**
		 * <[ Helper ]>
		 * Benutzerdefiniertes CSS an die Action wp_head übergeben.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 */
		private function _get_custom_css() {
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css'])) {
				?>
				<!-- Custom CSS (added by 2-Click Social Media Buttons) -->
				<style type="text/css">
				<?php echo $this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css'] . "\n"; ?>
				</style>
				<!-- /Custom CSS -->
				<?php
			} else {
				return false;
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_custom_css']))
		} // END private function _get_custom_css()

		/**
		 * <[ Helper ]>
		 * OpenGraph-Tags an die Action wp_head übergeben.
		 *
		 * @since 0.7
		 * @author ppfeufer
		 */
		private function _get_opengraph_tags() {
			global $post;

			// Nur Einzelartikel
			if(is_feed() || is_trackback() || !is_singular()) {
				return;
			} // END if(is_feed() || is_trackback() || !is_singular())

			$var_sPostThumbnail = $this->_get_article_image();
			if($var_sPostThumbnail) {
				echo "\n" . '<!-- Article Thumbnail -->' . "\n";
				echo sprintf('<link href="%s" rel="image_src" />%s', esc_url($var_sPostThumbnail), "\n");
			}

			/**
			 * Post Excerpt suchen und eventuell setzen, da sonst bei Facebook und G+ nichts steht.
			 * Sollte der Post keinen eigenen Excerpt haben, wird einer aus dem Artikel extrahiert.
			 * Dieser wird dann, ganz Twitterstyle, auf 140 Zeichen begrenzt.
			 */
			if(has_excerpt()) {
				$this->var_sPostExcerpt = $post->post_excerpt;
// 				define('TWOCLICK_POST_EXCERPT', $post->post_excerpt);
			} else {
				$this->var_sPostExcerpt = $this->_get_post_excerpt($post->post_content, 400);
// 				define('TWOCLICK_POST_EXCERPT', twoclick_buttons_generate_post_excerpt($post->post_content, 400));
			} // END if(has_excerpt())

			/**
			 * Beschreibung und Titel
			 * Hier wird geprüft ob SEO Plugins diese verändert haben.
			 * Berücksichtigt werden wpSEO und All in One SEO Pack
			 *
			 * @since 1.0
			 * @author ppfeufer
			 */
			$var_sTitle = get_the_title();
			$var_sDescription = esc_attr($this->var_sPostExcerpt);

			// Title durch wpSEO
			if(class_exists('wpSEO_Base') && (trim(get_post_meta(get_the_ID(), '_wpseo_edit_title', true)))) {
				$var_sTitle = trim(get_post_meta(get_the_ID(), '_wpseo_edit_title', true));
			} // END if(class_exists('wpSEO_Base'))

			// Title durch All in One SEO Pack
			if(function_exists('aiosp_meta') && (trim(get_post_meta(get_the_ID(), '_aioseop_title', true)))) {
				$var_sTitle = trim(get_post_meta(get_the_ID(), '_aioseop_title', true));
			} // END if(function_exists('aiosp_meta'))

			// Beschreibung durch wpSEO
			if(class_exists('wpSEO_Base') && (trim(get_post_meta(get_the_ID(), '_wpseo_edit_description', true)))) {
				$var_sDescription = trim(get_post_meta(get_the_ID(), '_wpseo_edit_description', true));
			} // END if(class_exists('wpSEO_Base'))

			// Bescheibung durch All in One SEO Pack
			if(function_exists('aiosp_meta') && (trim(get_post_meta(get_the_ID(), '_aioseop_description', true)))) {
				$var_sDescription = trim(get_post_meta(get_the_ID(), '_aioseop_description', true));
			} // END if(function_exists('aiosp_meta'))

			/**
			 * OpenGraph-Tags
			 *
			 * @since 0.7
			 */
			echo "\n" . '<!-- OpenGraph Tags (added by 2-Click Social Media Buttons) -->' . "\n";
			echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '"/>' . "\n";
			echo '<meta property="og:locale" content="' . strtolower(get_locale()) . '"/>' . "\n";
			echo '<meta property="og:type" content="article"/>' . "\n";
			echo '<meta property="og:title" content="' . strip_tags($var_sTitle) . '"/>' . "\n";
			echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '"/>' . "\n";

			if($var_sPostThumbnail) {
				echo '<meta property="og:image" content="' . esc_url($var_sPostThumbnail) . '"/>' . "\n";
			} // END if($var_sPostThumbnail)

			echo '<meta property="og:description" content="' . strip_tags($var_sDescription) . '"/>' . "\n";
			echo '<!-- /OpenGraph Tags -->' . "\n\n";
		} // END private function _get_opengraph_tags()

		/**
		 * <[ Helper ]>
		 * Returning the current pluginversion
		 *
		 * @author ppfeufer
		 * @since 1.0
		 *
		 * @return string
		 */
		private function _get_plugin_version() {
			$array_PluginData = $this->_get_plugin_data();

			return $array_PluginData['Version'];
		} // END private function _get_plugin_version()

		/**
		 * <[ Helper ]>
		 * Returning the plugindata
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return array
		 */
		private function _get_plugin_data() {
			$array_DefaultHeaders = array(
				'Name' => 'Plugin Name',
				'PluginURI' => 'Plugin URI',
				'Version' => 'Version',
				'Description' => 'Description',
				'Author' => 'Author',
				'AuthorURI' => 'Author URI',
				'TextDomain' => 'Text Domain',
				'DomainPath' => 'Domain Path',
			);

			$array_PluginData = get_file_data(TWOCLICK_PLUGIN_DIR . '2-click-socialmedia-buttons.php', $array_DefaultHeaders, 'plugin');

			$array_PluginData['Title'] = $array_PluginData['Name'];
			$array_PluginData['AuthorName'] = $array_PluginData['Author'];

			return $array_PluginData;
		} // END private function _get_plugin_data()

		/**
		 * <[ Helper ]>
		 * Getting an excerpt to use for the buttons
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sExcerpt
		 * @param int $var_iMaxLength
		 * @return string
		 */
		private function _get_post_excerpt($var_sExcerpt, $var_iMaxLength) {
			if(function_exists('strip_shortcodes')) {
				$var_sExcerpt = strip_shortcodes($var_sExcerpt);
			} // END if(function_exists('strip_shortcodes'))

			$var_sExcerpt = trim($var_sExcerpt);

			// Now lets strip any tags which dont have balanced ends
			// Need to put NGgallery tags in there - there are a lot of them and they are all different.
// 			$open_tags = "[simage,[[CP,[gallery,[imagebrowser,[slideshow,[tags,[albumtags,[singlepic,[album";
// 			$close_tags = "],]],],],],],],],]";
// 			$open_tag = explode(",", $open_tags);
// 			$close_tag = explode(",", $close_tags);

			$array_OpenTag = array(
				'[simage',
				'[[CP',
				'[gallery',
				'[imagebrowser',
				'[slideshow',
				'[tags',
				'[albumtags',
				'[singlepic',
				'[album'
			);

			$array_CloseTag = array(
				']',
				']]',
				']',
				']',
				']',
				']',
				']',
				']',
				']'
			);

			foreach(array_keys($array_OpenTag) as $var_sKey) {
				if(preg_match_all('/' . preg_quote($array_OpenTag[$var_sKey]) . '(.*?)' . preg_quote($array_CloseTag[$var_sKey]) . '/i', $var_sExcerpt, $array_Matches)) {
					$var_sExcerpt = str_replace($array_Matches[0], "", $var_sExcerpt);
				} // END if(preg_match_all('/' . preg_quote($array_OpenTag[$var_sKey]) . '(.*?)' . preg_quote($array_CloseTag[$var_sKey]) . '/i', $var_sExcerpt, $array_Matches))
			} // END foreach(array_keys($array_OpenTag) as $var_sKey)

			$var_sExcerpt = preg_replace('#(<wpg.*?>).*?(</wpg2>)#', '$1$2', $var_sExcerpt);

			// Support for qTrans
			if(function_exists('qtrans_use')) {
				global $q_config;

				$var_sExcerpt = qtrans_use($q_config['default_language'], $var_sExcerpt);
			} // END if(function_exists('qtrans_use'))

			$var_sExcerpt = strip_tags($var_sExcerpt);

			// Now lets strip off the youtube stuff.
			preg_match_all('#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=)([\w-]+)(.*?)player_embedded#i', $var_sExcerpt, $array_Matches);
			$var_sExcerpt = str_replace($array_Matches[0], '', $var_sExcerpt);

			preg_match_all('#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=|embed/)([\w-]+)(.*?)#i', $var_sExcerpt, $array_Matches);
			$var_sExcerpt = str_replace($array_Matches[0], '', $var_sExcerpt);

			if(strlen($var_sExcerpt) > $var_iMaxLength) {
				# If we've got multibyte support then we need to make sure we get the right length - Thanks to Kensuke Akai for the fix
				if(function_exists('mb_strimwidth')) {
					$var_sExcerpt = mb_strimwidth($var_sExcerpt, 0, $var_iMaxLength, ' ...');
				} else {
					$var_sExcerpt = current(explode('SJA26666AJS', wordwrap($var_sExcerpt, $var_iMaxLength, 'SJA26666AJSÄ'))) . ' ...';
				} // END if(function_exists('mb_strimwidth'))
			} // END if(strlen($var_sExcerpt) > $var_iMaxLength)

			return strip_tags($var_sExcerpt);
		} // END private function _get_post_excerpt($var_sExcerpt, $var_iMaxLength)

		/**
		 * <[ Helper ]>
		 * Tweettext einbinden
		 *
		 * @since 0.14
		 * @author ppfeufer
		 */
		private function _get_tweettext() {
			$twitter_hashtags = $this->_get_hashtags();
			$var_sTweettext = '';

			if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own') {
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own' && strlen($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']) == 0) {
					$var_sTweettext = get_the_title(get_the_ID()) . ' » ' . get_bloginfo('name') . $twitter_hashtags;
				} else {
					$var_sTweettext = $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext'] . $twitter_hashtags;
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own' && strlen($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_owntext']) == 0)
			} else {
				if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle-blogtitle') {
					$var_sTweettext = get_the_title(get_the_ID()) . ' » ' . get_bloginfo('name') . $twitter_hashtags;
				} elseif($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle') {
					$var_sTweettext = get_the_title(get_the_ID()) . $twitter_hashtags;
				} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext_default_as'] == 'posttitle-blogtitle')
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_tweettext'] == 'own')

			return $this->_shorten_tweettext(html_entity_decode($var_sTweettext, ENT_QUOTES, get_bloginfo('charset')));
		} // END private function _get_tweettext()

		/**
		 * <[ Helper ]>
		 * Tweettext kürzen
		 *
		 * @since 0.14
		 * @author ppfeufer
		 *
		 * @param string $var_sTweettext
		 * @return string
		 */
		private function _shorten_tweettext($var_sTweettext) {
			$array_TweettextData = array(
				'length_tweettext_maximal' => 140,
				'length_tweettext' => strlen($var_sTweettext),
				'length_twitter_name' => (!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply'])) ? strlen(' via @' . $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply']) : 0,
				'length_tweetlink' => 20,
				'length_more' => strlen(' [...]')
			);

			$length_new_tweettext = $array_TweettextData['length_tweettext_maximal'] - $array_TweettextData['length_twitter_name'] - $array_TweettextData['length_tweetlink'] - $array_TweettextData['length_more'];

			if($array_TweettextData['length_tweettext'] > $length_new_tweettext) {
				$var_sTweettext = substr($var_sTweettext, 0, $length_new_tweettext) . ' [...]';
			} // END if($array_TweettextData['length_tweettext'] > $length_new_tweettext)

			return $var_sTweettext;
		} // END private function _shorten_tweettext($var_sTweettext)

		/**
		 * Tags des Artikels in #Hashtags umwandeln
		 *
		 * @since 0.14
		 * @author ppfeufer
		 */
		private function _get_hashtags() {
			/**
			 * Sollen #Hashtags angezeigt werden?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_hashtags'] == true) {
				$var_sHashtags = strip_tags(get_the_tag_list(' #', ' #', ''));
			} else {
				$var_sHashtags = '';
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_hashtags'] == true)

			return $var_sHashtags;
		} // END private function _get_hashtags()

		/**
		 * Description for Pinterest
		 *
		 * @since 0.32
		 * @author ppfeufer
		 */
		function _get_pinterest_description() {
			$var_sPinterestDescription = '';

			switch($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description']) {
				case 'posttitle-tags':
					$var_sPinterestDescription = strip_tags(get_the_title(get_the_ID())) . ' ' . strip_tags(get_the_tag_list(' #', ' #', ''));
					break;

				case 'posttitle-excerpt':
					$var_sPinterestDescription = strip_tags(get_the_title(get_the_ID())) . ' &raquo; ' . $this->_get_post_excerpt(get_the_content(), 70);
					break;

				default:
					$var_sPinterestDescription = strip_tags(get_the_title(get_the_ID()));
					break;
			} // END switch($this->array_TwoclickButtonsOptions['twoclick_buttons_pinterest_description'])

			return rawurlencode($var_sPinterestDescription);
		} // END function _get_pinterest_description()


		/**
		 * <[ Helper ]>
		 * Filter und Container für das Intro bereit stellen
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return string
		 */
		private function _get_intro() {
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext'])) {
				return '<div class="twoclick-intro">' . apply_filters('twoclick_intro', '') . '</div>';
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_intro']))

			return false;
		} // END private function _get_intro()

		/**
		 * <[ Helper ]>
		 * Infotext über den Buttons im Artikel anzeigen.
		 *
		 * @since 1.0
		 * @author ppfeufer
		 *
		 * @return Ambigous <string, mixed>
		 */
		function _get_the_intro() {
			if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext'])) {
				return wpautop($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext']);
			} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_introtext']))
		} // END function _get_the_intro()

		/**
		 * <[ Helper ]>
		 * Dummybilder bereit stellen.
		 *
		 * Je nach Sprache des Blogs werden verschiedene Dummybilder bereit gestellt.
		 * Im Moment stehen Bilder für Deutsch und Englisch zur Verfügung.
		 * Sollte kein Bild für die jeweilige Sprache gefunden werden, so wird das Bild ohne Sprachcode hergenommen.
		 *
		 * @since 0.14
		 * @since 0.32 (modified)
		 * @author ppfeufer
		 */
		private function _get_dummy_images($var_sLang = '') {
			if(empty($var_sLang)) {
				$var_sLang = get_locale();
			} // END if(empty($var_sLang))

			$array_DummyImages = array(
				'facebook-recommend' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/facebook-dummy-image-recommend-' . $var_sLang . '.png')) ? plugins_url('/images/facebook-dummy-image-recommend-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/facebook-dummy-image-recommend.png', dirname(__FILE__)),
					'width' => '82'
				),
				'facebook-like' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/facebook-dummy-image-like-' . $var_sLang . '.png')) ? plugins_url('/images/facebook-dummy-image-like-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/facebook-dummy-image-like.png', dirname(__FILE__)),
					'width' => '72'
				),
				'twitter' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/twitter-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/twitter-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/twitter-dummy-image.png', dirname(__FILE__)),
					'width' => '62'
				),
				'googleplus' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/googleplus-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/googleplus-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/googleplus-dummy-image.png', dirname(__FILE__)),
					'width' => '32'
				),
				'flattr' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/flattr-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/flattr-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/flattr-dummy-image.png', dirname(__FILE__)),
					'width' => '54'
				),
				'xing' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/xing-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/xing-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/xing-dummy-image.png', dirname(__FILE__)),
					'width' => '55'
				),
				'pinterest' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/pinterest-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/pinterest-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/pinterest-dummy-image.png', dirname(__FILE__)),
					'width' => '63'
				),
				't3n' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/t3n-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/t3n-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/t3n-dummy-image.png', dirname(__FILE__)),
					'width' => '63'
				),
				'linkedin' => array(
					'image' => (is_readable(TWOCLICK_PLUGIN_DIR . 'images/linkedin-dummy-image-' . $var_sLang . '.png')) ? plugins_url('/images/linkedin-dummy-image-' . $var_sLang . '.png', dirname(__FILE__)) : plugins_url('/images/linkedin-dummy-image.png', dirname(__FILE__)),
					'width' => '63'
				)
			);

			return $array_DummyImages;
		} // END private function _get_dummy_images($var_sLang = '')

		/**
		 * <[ Helper ]>
		 * Artikelbild aus dem Artikel extrahieren,
		 * sofern überhaupt ein Bild vorhanden ist.
		 *
		 * @since 0.32
		 * @author ppfeufer
		 */
		private function _get_article_image() {
			global $post;

			$array_Image = '';

			/**
			 * Abfrage ob das Theme Post Thumbnails unterstützt.
			 * Einige Themes tun das einfach nicht.
			 *
			 * @since 0.7.1
			 */
			if(function_exists('get_post_thumbnail_id')) {
				$array_Image = wp_get_attachment_image_src(get_post_thumbnail_id($GLOBALS['post']->ID));
			} // END if(function_exists('get_post_thumbnail_id'))

			if(is_array($array_Image)) {
				$var_sPostThumbnail = $array_Image['0'];
			} else {
				$var_sDefaultThumbnail = '';
				$var_sOutput = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $GLOBALS['post']->post_content, $array_Matches);

				if($var_sOutput > 0) {
					$var_sPostThumbnail = $array_Matches[1][0];
				} else {
					if($this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'] != '') {
						$var_sPostThumbnail = $this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'];
					} else {
						$var_sPostThumbnail = false;
					} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_postthumbnail'] != '')
				} // END if($var_sOutput > 0)
			} // END if(is_array($array_Image))

			return $var_sPostThumbnail;
		} // END private function _get_article_image()

		/**
		 * <[ Helper ]>
		 * JavaScript für Ausgabe generieren.
		 *
		 * @since 0.4
		 * @author ppfeufer
		 */
		function _get_js($var_sPostID = '') {
			if(!is_admin()) {
				if(empty($this->var_sPostExcerpt)) {
					$this->var_sPostExcerpt = rawurlencode($this->_get_post_excerpt(get_the_content(), 400));
				} // END if(empty($this->var_sPostExcerpt))

				if(!empty($var_sPostID)) {
					$var_sPostID = get_the_ID();
				} // END if(!empty($var_sPostID))

				$var_sTitle = rawurlencode(get_the_title($var_sPostID));
				$var_sTweettext = rawurlencode($this->_get_tweettext());
				$var_sArticleImage = $this->_get_article_image();

				$var_sShowFacebook = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_facebook']) ? 'on' : 'off';
				$var_sShowFacebookPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_facebook_perm']) ? 'on' : 'off';
				$var_sShowTwitter = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_twitter']) ? 'on' : 'off';
				$var_sShowFlattr = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_flattr']) ? 'on' : 'off';
				$var_sShowXing = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_xing']) ? 'on' : 'off';
				$var_sShowPinterest = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_pinterest'] && $var_sArticleImage != false) ? 'on' : 'off';
				$var_sShowT3n = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_t3n']) ? 'on' : 'off';
				$var_sShowLinkedin = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_linkedin']) ? 'on' : 'off';

				$var_sShowTwitterPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_twitter_perm']) ? 'on' : 'off';
				$var_sShowGoogleplus = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_googleplus']) ? 'on' : 'off';
				$var_sShowGoogleplusPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_googleplus_perm']) ? 'on' : 'off';
				$var_sShowFlattrPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_flattr_perm']) ? 'on' : 'off';
				$var_sShowXingPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_xing_perm']) ? 'on' : 'off';
				$var_sShowPinterestPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_pinterest_perm']) ? 'on' : 'off';
				$var_sShowT3nPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_t3n_perm']) ? 'on' : 'off';
				$var_sShowLinkedinPerm = ($this->array_TwoclickButtonsOptions['twoclick_buttons_display_linkedin_perm']) ? 'on' : 'off';

// 				$var_sCss = plugins_url(basename(dirname(__FILE__)) . '/css/socialshareprivacy.css');

				/**
				 * Helperfiles
				 */
				$var_sXingLib = plugin_dir_url(__FILE__) . 'helper-button-xing.php';
				$var_sPinterestLib = plugin_dir_url(__FILE__) . 'helper-button-pinterest.php';
				$var_sT3nLib = plugin_dir_url(__FILE__) . 'helper-button-t3n.php';
				$var_sLinkedinLib = plugin_dir_url(__FILE__) . 'helper-button-linkedin.php';

				/**
				 * Settings for singular
				 */
				if(!is_singular()) {
					$var_sShowFacebookPerm = 'off';
					$var_sShowTwitterPerm = 'off';
					$var_sShowGoogleplusPerm = 'off';
					$var_sShowFlattrPerm = 'off';
					$var_sShowXingPerm = 'off';
					$var_sShowPinterestPerm = 'off';
					$var_sShowT3nPerm = 'off';
					$var_sShowLinkedinPerm = 'off';
				} // END if(!is_singular())

				/**
				 * Link zusammenbauen, auch wenn Optionen übergeben werden.
				 *
				 * @since 0.16
				 */
				if(isset($_GET) && count($_GET) != '0') {
					$var_sPermalink = (isset($_SERVER['HTTPS'])?'https':'http').'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				} else {
					$var_sPermalink = get_permalink($var_sPostID);
				} // END if(isset($_GET) && count($_GET) != '0')

				/**
				 * Infotexte erstellen
				 */
				$var_sInfotextFacebook = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Facebook senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_facebook'])) {
					$var_sInfotextFacebook = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_facebook'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_facebook']))

				$var_sInfotextTwitter = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Twitter senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_twitter'])) {
					$var_sInfotextTwitter = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_twitter'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_twitter']))

				$var_sInfotextGoogleplus = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Google+ senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_googleplus'])) {
					$var_sInfotextGoogleplus = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_googleplus'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_googleplus']))

				$var_sInfotextFlattr = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Flattr senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_flattr'])) {
					$var_sInfotextFlattr = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_flattr'];
				} // END f(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_flattr']))

				$var_sInfotextXing = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Xing senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_xing'])) {
					$var_sInfotextXing = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_xing'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_xing']))

				$var_sInfotextPinterest = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an Pinterest senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_pinterest'])) {
					$var_sInfotextPinterest = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_pinterest'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_pinterest']))

				$var_sInfotextT3n = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an t3n senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_t3n'])) {
					$var_sInfotextT3n = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_t3n'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_pinterest']))

				$var_sInfotextLinkedin = '2 Klicks für mehr Datenschutz: Erst wenn Sie hier klicken, wird der Button aktiv und Sie können Ihre Empfehlung an LinkedIn senden. Schon beim Aktivieren werden Daten an Dritte übertragen - siehe <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_linkedin'])) {
					$var_sInfotextLinkedin = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_linkedin'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_pinterest']))

				$var_sInfotextInfobutton = 'Wenn Sie diese Felder durch einen Klick aktivieren, werden Informationen an Facebook, Twitter, Flattr oder Google ins Ausland übertragen und unter Umständen auch dort gespeichert. Näheres erfahren Sie durch einen Klick auf das <em>i</em>.';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_infobutton'])) {
					$var_sInfotextInfobutton = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_infobutton'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_infobutton']))

				$var_sInfotextPermaoption = 'Dauerhaft aktivieren und Datenüber-tragung zustimmen:';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_permaoption'])) {
					$var_sInfotextPermaoption = $this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_permaoption'];
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infotext_permaoption']))

				$var_sInfolink = 'http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html';
				if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infolink'])) {
					$var_sInfolink = trim($this->array_TwoclickButtonsOptions['twoclick_buttons_infolink']);
				} // END if(!empty($this->array_TwoclickButtonsOptions['twoclick_buttons_infolink']))

				// Dummybilder holen.
				$array_DummyImages = $this->_get_dummy_images(get_locale());

				/**
				 * Sprache für Xing und Twitter
				 * Diese nutzen leider keine Lingua-Codes :-(
				 */
				$var_sButtonLanguage = 'de';
				if(get_locale() != 'de_DE') {
					$var_sButtonLanguage = 'en';
				} // END if(get_locale() != 'de_DE')

				$var_sFacebookAction = ($this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action']) ? $this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action'] : 'recommend';

				$array_ButtonData = array(
					'services' => array(
						'facebook' => array(
							'dummy_img' => $array_DummyImages['facebook-' . $var_sFacebookAction]['image'],
							'dummy_img_width' => $array_DummyImages['facebook-' . $var_sFacebookAction]['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowFacebook,
							'txt_info' => $var_sInfotextFacebook,
							'perma_option' => $var_sShowFacebookPerm,
							'action' => $this->array_TwoclickButtonsOptions['twoclick_buttons_facebook_action'],
							'language' => get_locale()
						),
						'twitter' => array(
							'reply_to' => $this->array_TwoclickButtonsOptions['twoclick_buttons_twitter_reply'],
							'dummy_img' => $array_DummyImages['twitter']['image'],
							'dummy_img_width' => $array_DummyImages['twitter']['width'],
							'dummy_img_height' => '20',
							'tweet_text' => rawurlencode($this->_get_tweettext()),
							'status' => $var_sShowTwitter,
							'txt_info' => $var_sInfotextTwitter,
							'perma_option' => $var_sShowTwitterPerm,
							'language' => $var_sButtonLanguage
						),
						'gplus' => array(
							'dummy_img' => $array_DummyImages['googleplus']['image'],
							'dummy_img_width' => $array_DummyImages['googleplus']['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowGoogleplus,
							'txt_info' => $var_sInfotextGoogleplus,
							'perma_option' => $var_sShowGoogleplusPerm
						),
						'flattr' => array(
							'uid' => $this->array_TwoclickButtonsOptions['twoclick_buttons_flattr_uid'],
							'dummy_img' => $array_DummyImages['flattr']['image'],
							'dummy_img_width' => $array_DummyImages['flattr']['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowFlattr,
							'the_title' => $var_sTitle,
							'the_excerpt' => $this->var_sPostExcerpt,
							'txt_info' => $var_sInfotextFlattr,
							'perma_option' => $var_sShowFlattrPerm
						),
						'xing' => array(
							'dummy_img' => $array_DummyImages['xing']['image'],
							'dummy_img_width' => $array_DummyImages['xing']['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowXing,
							'txt_info' => $var_sInfotextXing,
							'perma_option' => $var_sShowXingPerm,
							'language' => $var_sButtonLanguage,
							'xing_lib' => $var_sXingLib
						),
						'pinterest' => array(
							'dummy_img' => $array_DummyImages['pinterest']['image'],
							'dummy_img_width' => $array_DummyImages['pinterest']['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowPinterest,
							'the_excerpt' => $this->_get_pinterest_description(),
							'txt_info' => $var_sInfotextPinterest,
							'perma_option' => $var_sShowPinterestPerm,
							'pinterest_lib' => $var_sPinterestLib,
							'media' => $var_sArticleImage
						),
						't3n' => array(
							'dummy_img' => $array_DummyImages['t3n']['image'],
							'dummy_img_width' => $array_DummyImages['t3n']['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowT3n,
							'txt_info' => $var_sInfotextT3n,
							'perma_option' => $var_sShowT3nPerm,
							't3n_lib' => $var_sT3nLib
						),
						'linkedin' => array(
							'dummy_img' => $array_DummyImages['linkedin']['image'],
							'dummy_img_width' => $array_DummyImages['linkedin']['width'],
							'dummy_img_height' => '20',
							'status' => $var_sShowLinkedin,
							'txt_info' => $var_sInfotextLinkedin,
							'perma_option' => $var_sShowLinkedinPerm,
							'linkedin_lib' => $var_sLinkedinLib
						)
					),
					'txt_help' => $var_sInfotextInfobutton,
					'settings_perma' => $var_sInfotextPermaoption,
					'info_link' => $var_sInfolink,
// 					'css_path' => apply_filters('twoclick-css', $var_sCss),
					'uri' => esc_url($var_sPermalink),
					'post_id' => $var_sPostID
				);

				$var_sJavaScript = '/* <![CDATA[ */' . "\n" . '// WP-Language = ' . get_locale() . "\n" . 'jQuery(document).ready(function($){if($(\'.twoclick_social_bookmarks_post_' . $var_sPostID . '\')){$(\'.twoclick_social_bookmarks_post_' . $var_sPostID . '\').socialSharePrivacy(' . json_encode($array_ButtonData) . ');}});' . "\n" . '/* ]]> */';

				return $this->_get_intro() . '<div class="twoclick_social_bookmarks_post_' . $var_sPostID . ' social_share_privacy clearfix"></div><div class="twoclick-js-v-' . $this->_get_plugin_version() . '"><script type="text/javascript">' . $var_sJavaScript . '</script></div>';
			} // END if(!is_admin())
		} // END function _get_js($var_sPostID = '')

		/**
		 * <[ Helper ]>
		 * Buttons in WordPress einbauen.
		 *
		 * @since 0.1
		 * @since 0.22 (modified)
		 * @author ppfeufer
		 */
		function _get_buttons($content) {
			global $post;

			/**
			 * Manual Option
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'template') {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_where'] == 'template')

			/**
			 * Sind wir auf einer CMS-Seite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_page'] == null && is_page()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_page'] == null && is_page())

			/**
			 * Sind wir auf der Startseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_index'] == null && is_home()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_index'] == null && is_home())

			/**
			 * Sind wir im Jahresarchiv?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_year'] == null && is_year()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_year'] == null && is_year())

			/**
			 * Sind wir im Monatsarchiv?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_month'] == null && is_month()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_month'] == null && is_month())

			/**
			 * Sind wir im Tagesarchiv?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_day'] == null && is_day()) {
				return $content;
			}

			/**
			 * Sind wir auf der Suchseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_search'] == null && is_search()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_search'] == null && is_search())

			/**
			 * Sind wir auf der Tagseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_tag'] == null && is_tag()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_tag'] == null && is_tag())

			/**
			 * Sind wir auf der Kategorieseite?
			 */
			if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_category'] == null && is_category()) {
				return $content;
			} // END if($this->array_TwoclickButtonsOptions['twoclick_buttons_display_category'] == null && is_category())

			/**
			 * Soll der Button im Feed ausgeblendet werden?
			 */
			if(is_feed()) {
				return $content;
			} // END if(is_feed())

			$button = $this->generate_html(get_the_ID());
			$var_sWhere = 'twoclick_buttons_where';

			if($post->post_status == 'private' && $this->array_TwoclickButtonsOptions['twoclick_buttons_display_private'] == false) {
				return $content;
			}

			if(post_password_required() && $this->array_TwoclickButtonsOptions['twoclick_buttons_display_password'] == false) {
				return $content;
			}

			/**
			 * Wurde der Shortcode genutzt
			 */
			if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'shortcode') {
				return str_replace('[twoclick_buttons]', $button, $content);
			} else {
				/**
				 * In den Content einbinden
				 */
				if((is_array($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && (array_key_exists($post->ID, $this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && ($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'][$post->ID] == true)) {
					return $content;
				} // END if((is_array($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && (array_key_exists($post->ID, $this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'])) && ($this->array_TwoclickButtonsOptions['twoclick_buttons_exclude_page'][$post->ID] == true))

				if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'beforeandafter') {
					/**
					 * Vor und nach dem Beitrag einfügen
					 */
					return $button . $content . $button;
				} else if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'before') {
					/**
					 * Vor dem Beitrag einfügen
					 */
					return $button . $content;
				} else {
					/**
					 * Nach dem Beitrag einfügen
					 */
					return $content . $button;
				} // END if($this->array_TwoclickButtonsOptions[$var_sWhere] == 'beforeandafter')
// 				} else {
// 					/**
// 					 * Keinen Button einfügen
// 					 */
// 					return $content;
// 				}
			}
		} // END function _get_buttons($content)

		/**
		 * Template-Tag
		 *
		 * Bindet die Buttons via Funktionsaufruf direkt im Template ein.
		 *
		 * Einbindung:
		 * 		<?php if(function_exists('get_twoclick_buttons')) {get_twoclick_buttons(get_the_ID());}?>
		 *
		 * @since 0.18
		 * @author ppfeufer
		 *
		 * @param int $var_iId
		 */
		function generate_html($var_sPostID = null) {
			if($var_sPostID == '') {
				$var_sPostID = get_the_ID();
			} // END if($var_sPostID == '')

			return $this->_get_js($var_sPostID);
		} // END function generate_html($var_sPostID = null)
	} // END class Twoclick_Social_Media_Buttons_Frontend

	/**
	 * Frontendklasse starten
	 */
	$obj_TwoclickFrontend = new Twoclick_Social_Media_Buttons_Frontend();

	/**
	 * Template-Tag
	 *
	 * Bindet die Buttons via Funktionsaufruf direkt im Template ein.
	 *
	 * Einbindung:
	 * 		<?php if(function_exists('get_twoclick_buttons')) {get_twoclick_buttons(get_the_ID());} ?>
	 *
	 * @since 0.18
	 * @author ppfeufer
	 *
	 * @param int $var_iId
	 */
	function get_twoclick_buttons($var_sPostID = null) {
		if($var_sPostID == '') {
			$var_sPostID = get_the_ID();
		}

		if(!empty($var_sPostID)) {
			global $obj_TwoclickFrontend;

			echo $obj_TwoclickFrontend->generate_html($var_sPostID);
		} else {
			return false;
		} // END if(!empty($var_iId))
	} // END function get_twoclick_buttons($var_iId = null)
} // END if(!class_exists('Twoclick_Social_Media_Buttons_Frontend'))