<?php

namespace SMW\ImageCaption\Tests;

use SMW\ImageCaption\Hooks;

/**
 * @covers \SMW\ImageCaption\Hooks
 * @group semantic-image-caption
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class HooksTest extends \PHPUnit_Framework_TestCase {

	public function testOnRegisterSchemaTypes() {

		$schemaTypes = $this->getMockBuilder( '\SMW\Schema\SchemaTypes' )
			->disableOriginalConstructor()
			->getMock();

		$schemaTypes->expects( $this->once() )
			->method( 'registerSchemaType' )
			->with( $this->stringContains( 'IMAGECAPTION_RULE_SCHEMA' ) );

		$this->assertTrue(
			Hooks::onRegisterSchemaTypes( $schemaTypes )
		);
	}

	public function testOnImageBeforeProduceHTML() {

		$title = $this->getMockBuilder( '\Title' )
			->disableOriginalConstructor()
			->getMock();

		$title->expects( $this->any() )
			->method( 'getNamespace' )
			->will( $this->returnValue( NS_MAIN ) );

		$file = $this->getMockBuilder( '\File' )
			->disableOriginalConstructor()
			->getMock();

		$file->expects( $this->any() )
			->method( 'getTitle' )
			->will( $this->returnValue( $title ) );

		$parserOptions = $this->getMockBuilder( '\ParserOptions' )
			->disableOriginalConstructor()
			->getMock();

		$parserOptions->expects( $this->any() )
			->method( 'getUserLang' )
			->will( $this->returnValue( 'en' ) );

		$parser = $this->getMockBuilder( '\Parser' )
			->disableOriginalConstructor()
			->getMock();

		$parser->expects( $this->any() )
			->method( 'getTitle' )
			->will( $this->returnValue( $title ) );

		$parser->expects( $this->any() )
			->method( 'getOptions' )
			->will( $this->returnValue( $parserOptions ) );

		$frameParams['caption'] = '';
		$handlerParams = [];
		$time = '';
		$res = null;
		$query = '';
		$widthOption = '';

		$this->assertTrue(
			Hooks::onImageBeforeProduceHTML( $dummy, $title, $file, $frameParams, $handlerParams, $time, $res, $parser, $query, $widthOption )
		);
	}

}
