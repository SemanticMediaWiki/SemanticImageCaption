<?php

use SMW\ImageCaption\Hooks;

/**
 * Complementary extension to Semantic MediaWiki to support auto-caption of
 * images.
 *
 * @see https://github.com/SemanticMediaWiki/SemanticImageCaption
 *
 * @defgroup SemanticImageCaption Semantic ImageCaption
 */
SemanticImageCaption::load();

/**
 * @codeCoverageIgnore
 */
class SemanticImageCaption {

	/**
	 * @since 1.0
	 *
	 * @note It is expected that this function is loaded before LocalSettings.php
	 * to ensure that settings and global functions are available by the time
	 * the extension is activated.
	 */
	public static function load() {

		if ( !defined( 'MEDIAWIKI' ) ) {
			return;
		}

		if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
			include_once __DIR__ . '/vendor/autoload.php';
		}
	}

	/**
	 * @since 1.0
	 * @see https://www.mediawiki.org/wiki/Manual:Extension.json/Schema#callback
	 */
	public static function initExtension( $credits = [] ) {

		$version = 'UNKNOWN' ;

		// See https://phabricator.wikimedia.org/T151136
		if ( isset( $credits['version'] ) ) {
			$version = $credits['version'];
		}

		define( 'SMW_IMAGECAPTION_VERSION', $version );

		$GLOBALS['wgMessagesDirs']['SemanticImageCaption'] = __DIR__ . '/i18n';
	}

	/**
	 * @since 1.0
	 */
	public static function onExtensionFunction() {

	}

}
