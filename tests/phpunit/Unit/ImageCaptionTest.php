<?php

namespace SMW\ImageCaption\Tests;

use MediaWiki\Title\Title;
use PHPUnit\Framework\TestCase;
use SMW\ImageCaption\ImageCaption;

/**
 * @covers \SMW\ImageCaption\ImageCaption
 * @group semantic-image-caption
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class ImageCaptionTest extends TestCase {

	private $store;
	private $ruleFinder;

	protected function setUp(): void {
		$this->store = $this->getMockBuilder( '\SMW\Store' )
			->disableOriginalConstructor()
			->setMethods( [ 'getPropertyValues' ] )
			->getMockForAbstractClass();

		$this->ruleFinder = $this->getMockBuilder( '\SMW\ImageCaption\RuleFinder' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testCanConstruct() {
		$this->assertInstanceof(
			ImageCaption::class,
			new ImageCaption( $this->store, $this->ruleFinder )
		);
	}

	public function testModifyCaption_EmptyCaption() {
		$caption = '';

		$rule = $this->getMockBuilder( '\SMW\Schema\Rule' )
			->disableOriginalConstructor()
			->getMock();

		$rule->expects( $this->at( 1 ) )
			->method( 'has' )
			->with( $this->stringContains( 'then.caption_property' ) )
			->willReturn( true );

		$rule->expects( $this->at( 2 ) )
			->method( 'then' )
			->with( $this->stringContains( 'caption_property' ) )
			->willReturn( 'Foo' );

		$rule->expects( $this->at( 3 ) )
			->method( 'has' )
			->willReturn( true );

		$rule->expects( $this->at( 4 ) )
			->method( 'then' )
			->with( $this->stringContains( 'max_length' ) );

		$this->ruleFinder->expects( $this->any() )
			->method( 'findRule' )
			->willReturn( $rule );

		$this->store->expects( $this->any() )
			->method( 'getPropertyValues' )
			->willReturn( [] );

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

		$instance = new ImageCaption(
			$this->store,
			$this->ruleFinder
		);

		$instance->modifyCaption( $title, $file, $caption, 'en' );

		$this->assertSame(
			'',
			$caption
		);
	}

	public function testModifyCaption_PreventCaptionOverride() {
		$caption = 'Foo';

		$rule = $this->getMockBuilder( '\SMW\Schema\Rule' )
			->disableOriginalConstructor()
			->getMock();

		$rule->expects( $this->at( 1 ) )
			->method( 'has' )
			->with( $this->stringContains( 'then.allow_caption_override' ) )
			->willReturn( true );

		$rule->expects( $this->at( 2 ) )
			->method( 'then' )
			->with( $this->stringContains( 'allow_caption_override' ) )
			->willReturn( false );

		$this->ruleFinder->expects( $this->any() )
			->method( 'findRule' )
			->willReturn( $rule );

		$this->store->expects( $this->any() )
			->method( 'getPropertyValues' )
			->willReturn( [] );

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

		$instance = new ImageCaption(
			$this->store,
			$this->ruleFinder
		);

		$instance->modifyCaption( $title, $file, $caption, 'en' );

		$this->assertEquals(
			'Foo',
			$caption
		);
	}

}
