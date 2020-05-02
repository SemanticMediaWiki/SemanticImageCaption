<?php

namespace SMW\ImageCaption;

use SMW\Schema\SchemaFactory;
use SMW\Schema\SchemaFilter;
use SMW\Schema\Rule;
use SMW\Schema\CompartmentIterator;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class RuleFinder {

	/**
	 * @var SchemaFactory
	 */
	private $schemaFactory;

	/**
	 * @since 1.0
	 *
	 * @param SchemaFactory $schemaFactory
	 */
	public function __construct( SchemaFactory $schemaFactory ) {
		$this->schemaFactory = $schemaFactory;
	}

	/**
	 * @since 1.0
	 *
	 * @param array $categories
	 *
	 * @return Rule
	 */
	public function findRule( array $categories = [] ) : Rule {

		$schemaList = $this->schemaFactory->newSchemaFinder()->getSchemaListByType(
			ImageCaption::SCHEMA_TYPE
		);

		$rules = $schemaList->newCompartmentIteratorByKey(
			'caption_rules',
			CompartmentIterator::RULE_COMPARTMENT
		);

		$categoryFilter = $this->schemaFactory->newSchemaFilterFactory()->newCategoryFilter(
			$categories
		);

		$categoryFilter->filter( $rules );

		// Use the first "best" matched rule
		foreach ( $categoryFilter->getMatches() as $rule ) {
			return $rule;
		}

		return $rules->find( 'default_rule', CompartmentIterator::MATCH_KEY )->current();
	}

}
