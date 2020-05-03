<?php

namespace SMW\ImageCaption\Tests;

use SMW\ImageCaption\ImageCaption;

/**
 * @covers \SMW\ImageCaption\ImageCaption
 * @group semantic-image-caption
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class ImageCaptionTest extends \PHPUnit_Framework_TestCase {

	private $store;
	private $ruleFinder;

	protected function setUp() : void {

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
			->will( $this->returnValue( true ) );

		$rule->expects( $this->at( 2 ) )
			->method( 'then' )
			->with( $this->stringContains( 'caption_property' ) )
			->will( $this->returnValue( 'Foo' ) );

		$rule->expects( $this->at( 3 ) )
			->method( 'has' )
			->will( $this->returnValue( true ) );

		$rule->expects( $this->at( 4 ) )
			->method( 'then' )
			->with( $this->stringContains( 'max_length' ) );

		$this->ruleFinder->expects( $this->any() )
			->method( 'findRule' )
			->will( $this->returnValue( $rule ) );

		$this->store->expects( $this->any() )
			->method( 'getPropertyValues' )
			->will( $this->returnValue( [] ) );

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

		$instance = new ImageCaption(
			$this->store,
			$this->ruleFinder
		);

		$instance->modifyCaption( $title, $file, $caption, 'en' );

		$this->assertEquals(
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
			->will( $this->returnValue( true ) );

		$rule->expects( $this->at( 2 ) )
			->method( 'then' )
			->with( $this->stringContains( 'allow_caption_override' ) )
			->will( $this->returnValue( false ) );

		$this->ruleFinder->expects( $this->any() )
			->method( 'findRule' )
			->will( $this->returnValue( $rule ) );

		$this->store->expects( $this->any() )
			->method( 'getPropertyValues' )
			->will( $this->returnValue( [] ) );

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
