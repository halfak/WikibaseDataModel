<?php

/**
 * Entry point for the DataModel component of Wikibase.
 *
 * @since 0.4
 *
 * @file
 * @ingroup WikibaseDataModel
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( defined( 'WIKIBASE_DATAMODEL_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

define( 'WIKIBASE_DATAMODEL_VERSION', '0.5 alpha'  );

// Attempt to include the dependencies mif one of them has not been loaded yet.
// This is the path to the autoloader generated by composer in case of a composer install.
if ( ( !defined( 'Diff_VERSION' ) || !defined( 'DataValues_VERSION' ) )
	&& is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

// Attempt to include the DataValues lib if that hasn't been done yet.
if ( !defined( 'DataValues_VERSION' ) && file_exists( __DIR__ . '/../DataValues/DataValues.php' ) ) {
	include_once( __DIR__ . '/../DataValues/DataValues.php' );
}

// Attempt to include the Diff lib if that hasn't been done yet.
if ( !defined( 'Diff_VERSION' ) && file_exists( __DIR__ . '/../Diff/Diff.php' ) ) {
	include_once( __DIR__ . '/../Diff/Diff.php' );
}

// Only initialize the extension when all dependencies are present.
if ( !defined( 'DataValues_VERSION' ) ) {
	throw new Exception( 'You need to have the DataValues library loaded in order to use WikibaseDataModel' );
}

// Only initialize the extension when all dependencies are present.
if ( !defined( 'Diff_VERSION' ) ) {
	throw new Exception( 'You need to have the Diff library loaded in order to use WikibaseDataModel' );
}

// @codeCoverageIgnoreStart
spl_autoload_register( function ( $className ) {
	static $classes = false;

	if ( $classes === false ) {
		$classes = include( __DIR__ . '/' . 'WikibaseDataModel.classes.php' );
	}

	if ( array_key_exists( $className, $classes ) ) {
		include_once __DIR__ . '/' . $classes[$className];
	}
} );

if ( defined( 'MEDIAWIKI' ) ) {
	call_user_func( function() {
		require_once __DIR__ . '/WikibaseDataModel.mw.php';
	} );
}
// @codeCoverageIgnoreEnd