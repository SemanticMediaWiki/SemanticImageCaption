<?php

namespace SMW\ImageCaption;

use SMW\Schema\SchemaFilterFactory;
use SMW\Schema\SchemaFinder;
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
	 * @var SchemaFinder
	 */
	private $schemaFinder;

	/**
	 * @var SchemaFilterFactory
	 */
	private $schemaFilterFactory;

	/**
	 * @since 1.0
	 *
	 * @param SchemaFinder $schemaFinder
	 * @param SchemaFilterFactory $schemaFilterFactory
	 */
	public function __construct( SchemaFinder $schemaFinder, SchemaFilterFactory $schemaFilterFactory ) {
		$this->schemaFinder = $schemaFinder;
		$this->schemaFilterFactory = $schemaFilterFactory;
	}

	/**
	 * @since 1.0
	 *
	 * @param array $categories
	 *
	 * @return Rule
	 */
	public function findRule( array $categories = [] ) : Rule {

		$schemaList = $this->schemaFinder->getSchemaListByType(
			ImageCaption::SCHEMA_TYPE
		);

		$rules = $schemaList->newCompartmentIteratorByKey(
			'caption_rules',
			CompartmentIterator::RULE_COMPARTMENT
		);

		$categoryFilter = $this->schemaFilterFactory->newCategoryFilter(
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
