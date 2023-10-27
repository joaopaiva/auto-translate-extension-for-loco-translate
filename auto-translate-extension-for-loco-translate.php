<?php
/**
 * Plugin Name: Auto-Translate Extension for Loco Translate
 * Plugin URI: https://github.com/joaopaiva/auto-translate-extension-for-loco-translate
 * Description: A powerful extension for Loco Translate, enabling seamless integration with Google Translate to automatically translate WordPress plugins and themes. Effortless, high-quality translations without the need for a Google Translate API key. Compatible with WordPress 4.6+ and PHP 5.6+.
 * Version: 1.0
 * Author: João Paiva
 * Author URI: https://github.com/joaopaiva
 */

// Include the Google Translate library.
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
use \Statickidz\GoogleTranslate;

/**
 * Initialize the translation provider.
 */
function initialize_google_translate_provider() {
    if (is_admin()) {
        add_filter('loco_api_providers', 'add_google_translate_provider', 10, 1);
        add_action('loco_api_ajax', 'init_google_translate_translation', 0, 0);
    }
}

/**
 * Add the Google Translate provider to Loco Translate.
 *
 * @param array $apis The list of translation APIs.
 * @return array The updated list of translation APIs.
 */
function add_google_translate_provider($apis) {
    $apis[] = array(
        'id' => 'auto-translate-extension-for-loco-translate',
        'url' => 'https://github.com/joaopaiva/auto-translate-extension-for-loco-translate', // Replace with your provider's URL
        'name' => 'Auto-Translate Extension for Loco Translate',
    );
    return $apis;
}

/**
 * Initialize the translation process.
 */
function init_google_translate_translation() {
    add_filter('loco_api_translate_my_google_translate', 'process_google_translate_translation', 0, 3);
}

/**
 * Handle translation using the "php-google-translate-free" library.
 *
 * @param array       $sources An array of source strings.
 * @param Loco_Locale $Locale  The target locale for translations.
 * @param array       $config  The configuration for the translation provider.
 *
 * @return array An array of translated strings.
 */
function process_google_translate_translation(array $sources, Loco_Locale $locale, array $config) {
    $translated = array();
    $trans = new GoogleTranslate();
	$source_language = 'en';
	$target_language = $locale->lang;

    foreach ($sources as $source) {
        $translated[] = $trans->translate($source_language, $target_language, $source);
    }

    return $translated;
}

// Initialize the translation provider.
initialize_google_translate_provider();
