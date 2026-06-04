<?php

namespace SMW\ImageCaption;

use MediaWiki\MediaWikiServices;
use SMW\Schema\SchemaTypes;
use SMW\Services\ServicesFactory as ApplicationFactory;

/**
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class Hooks {

	/**
	 * @since  1.0
	 */
	public function register() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			return;
		}

		$handlers = [
			'SMW::Schema::RegisterSchemaTypes' => [ $this, 'onRegisterSchemaTypes' ],
			'ImageBeforeProduceHTML' => [ $this, 'onImageBeforeProduceHTML' ]
		];

		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();

		foreach ( $handlers as $name => $callback ) {
			$hookContainer->register( $name, $callback );
		}
	}

	/**
	 * @see https://www.semantic-mediawiki.org/wiki/Hooks#SMW::Schema::RegisterSchemaTypes
	 *
	 * @since 1.0
	 *
	 * @param SchemaTypes $schemaTypes
	 */
	public static function onRegisterSchemaTypes( SchemaTypes $schemaTypes ) {
		$params = [
			'group' => 'schema/group/imagecaption',
			'validation_schema' => __DIR__ . '/../data/schema/imagecaption-rule-schema.v1.json',
			'type_description' => 'semantic-imagecaption-rule-schema-description'
		];

		$schemaTypes->registerSchemaType( ImageCaption::SCHEMA_TYPE, $params );

		return true;
	}

	/**
	 * Hook: Called before producing the HTML created by a wiki image insertion
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ImageBeforeProduceHTML
	 *
	 * @param null &$dummy
	 * @param Title &$title
	 * @param &$file
	 * @param array &$frameParams
	 * @param array &$handlerParams
	 * @param &$time
	 * @param &$res
	 * @param Parser $parser
	 * @param string &$query
	 * @param &$widthOption
	 *
	 * @return bool
	 */
	public static function onImageBeforeProduceHTML( &$dummy, &$title, &$file, &$frameParams, &$handlerParams, &$time, &$res, $parser, &$query, &$widthOption ) {
		$applicationFactory = ApplicationFactory::getInstance();
		$schemaFactory = $applicationFactory->singleton( 'SchemaFactory' );

		$ruleFinder = new RuleFinder(
			$schemaFactory->newSchemaFinder(),
			$schemaFactory->newSchemaFilterFactory()
		);

		$imageCaption = new ImageCaption(
			$applicationFactory->getStore(),
			$ruleFinder
		);

		$target = $parser->getTitle();
		$languageCode = $parser->getOptions()->getUserLang();

		$imageCaption->modifyCaption( $target, $file, $frameParams['caption'], $languageCode );

		return true;
	}

}
