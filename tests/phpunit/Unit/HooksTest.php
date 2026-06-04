<?php

namespace SMW\ImageCaption\Tests;

use MediaWiki\Title\Title;
use PHPUnit\Framework\TestCase;
use SMW\ImageCaption\Hooks;

/**
 * @covers \SMW\ImageCaption\Hooks
 * @group semantic-image-caption
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class HooksTest extends TestCase {

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
		$title = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->getMock();

		$title->expects( $this->any() )
			->method( 'getNamespace' )
			->willReturn( NS_MAIN );

		$file = $this->getMockBuilder( '\File' )
			->disableOriginalConstructor()
			->getMock();

		$file->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( $title );

		$parserOptions = $this->getMockBuilder( '\ParserOptions' )
			->disableOriginalConstructor()
			->getMock();

		$parserOptions->expects( $this->any() )
			->method( 'getUserLang' )
			->willReturn( 'en' );

		$parser = $this->getMockBuilder( '\Parser' )
			->disableOriginalConstructor()
			->getMock();

		$parser->expects( $this->any() )
			->method( 'getTitle' )
			->willReturn( $title );

		$parser->expects( $this->any() )
			->method( 'getOptions' )
			->willReturn( $parserOptions );

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
