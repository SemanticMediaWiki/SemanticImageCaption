<?php

namespace SMW\ImageCaption\Tests;

use SMW\ImageCaption\RuleFinder;

/**
 * @covers \SMW\ImageCaption\RuleFinder
 * @group semantic-image-caption
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class RuleFinderTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$schemaFactory = $this->getMockBuilder( '\SMW\Schema\SchemaFactory' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceof(
			RuleFinder::class,
			new RuleFinder( $schemaFactory )
		);
	}

	public function testFindRule_CategoryFilter() {

		$rule = $this->getMockBuilder( '\SMW\Schema\Rule' )
			->disableOriginalConstructor()
			->getMock();

		$categoryFilter = $this->getMockBuilder( '\SMW\Schema\Filters\CategoryFilter' )
			->disableOriginalConstructor()
			->getMock();

		$categoryFilter->expects( $this->any() )
			->method( 'getMatches' )
			->will( $this->returnValue( [ $rule ] ) );

		$schemaFilterFactory = $this->getMockBuilder( '\SMW\Schema\SchemaFilterFactory' )
			->disableOriginalConstructor()
			->getMock();

		$schemaFilterFactory->expects( $this->any() )
			->method( 'newCategoryFilter' )
			->will( $this->returnValue( $categoryFilter ) );

		$schemaList = $this->getMockBuilder( '\SMW\Schema\SchemaList' )
			->disableOriginalConstructor()
			->getMock();

		$schemaFinder = $this->getMockBuilder( '\SMW\Schema\SchemaFinder' )
			->disableOriginalConstructor()
			->getMock();

		$schemaFinder->expects( $this->any() )
			->method( 'getSchemaListByType' )
			->will( $this->returnValue( $schemaList ) );

		$schemaFactory = $this->getMockBuilder( '\SMW\Schema\SchemaFactory' )
			->disableOriginalConstructor()
			->getMock();

		$schemaFactory->expects( $this->any() )
			->method( 'newSchemaFinder' )
			->will( $this->returnValue( $schemaFinder ) );

		$schemaFactory->expects( $this->any() )
			->method( 'newSchemaFilterFactory' )
			->will( $this->returnValue( $schemaFilterFactory ) );

		$instance = new RuleFinder(
			$schemaFactory
		);

		$this->assertInstanceof(
			'\SMW\Schema\Compartment',
			$instance->findRule( [] )
		);
	}

}
