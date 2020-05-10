<?php

namespace SMW\ImageCaption;

use SMW\ApplicationFactory;
use SMW\Schema\SchemaTypes;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class Hooks {

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
	 * @param DummyLinker &$linker
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
