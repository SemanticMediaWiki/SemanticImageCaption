<?php

namespace SMW\ImageCaption;

use SMW\Store;
use SMW\DIWikiPage;
use SMW\DIProperty;
use SMW\DataValues\MonolingualTextValue;
use SMW\DataValueFactory;
use SMWDIBlob as DIBlob;
use SMW\Utils\Normalizer;
use SMW\RequestOptions;
use Title;
use File;
use Html;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class ImageCaption {

	const SCHEMA_TYPE = 'IMAGECAPTION_RULE_SCHEMA';

	/**
	 * @var Store
	 */
	private $store;

	/**
	 * @var RuleFinder
	 */
	private $ruleFinder;

	/**
	 * @var Rule
	 */
	private $rule;

	/**
	 * @since 1.0
	 *
	 * @param Store $store
	 * @param RuleFinder $ruleFinder
	 */
	public function __construct( Store $store, RuleFinder $ruleFinder ) {
		$this->store = $store;
		$this->ruleFinder = $ruleFinder;
	}

	/**
	 * @since 1.0
	 *
	 * @param Title $target
	 * @param File|false $file
	 * @param string &$caption
	 * @param string $languageCode
	 */
	public function modifyCaption( Title $target, $file, string &$caption, string $languageCode ) {

		if ( !$file instanceof File ) {
			return;
		}

		// Track count of embedded images
		if ( isset( $target->semanticimagecaptioncount ) ) {
			$target->semanticimagecaptioncount++;
		} else {
			$target->semanticimagecaptioncount = 1;
		}

		$subject = DIWikiPage::newFromTitle(
			$file->getTitle()
		);

		$text = $this->findText( $subject, $target, $caption, $languageCode );

		if ( $text !== '' ) {
			$caption = $text;
		}
	}

	private function findText( DIWikiPage $subject, Title $target, string $caption, string $languageCode ) : string {

		$requestOptions = new RequestOptions();
		$requestOptions->setCaller( __METHOD__ );

		$dataItems = $this->store->getPropertyValues(
			$subject,
			new DIProperty( '_INST' ),
			$requestOptions
		);

		$categories = [];

		foreach ( $dataItems as $dataItem ) {
			$categories[] = $dataItem->getDBKey();
		}

		$this->rule = $this->ruleFinder->findRule( $categories );

		if ( $this->rule->isEmpty() ) {
			return '';
		}

		if ( $caption !== '' && !$this->get( 'allow_caption_override', false ) ) {
			return '';
		}

		if ( ( $property = $this->get( 'caption_property', '' ) ) === '' ) {
			return '';
		}

		$property = DIProperty::newFromUserLabel( $property );
		$isMonolingualTextValue = $property->findPropertyValueType() === MonolingualTextValue::TYPE_ID;

		$dataItems = $this->store->getPropertyValues(
			$subject,
			$property,
			$requestOptions
		);

		$text = '';
		$maxLength = $this->get( 'max_length', 200 );

		if ( $dataItems === [] ) {
			return '';
		}

		foreach ( $dataItems as $dataItem ) {

			$monolingualText = null;

			if ( $dataItem instanceof DIWikiPage && $isMonolingualTextValue ) {
				$monolingualText = $this->findMonolingualText( $property, $dataItem, $languageCode );
			}

			if ( $dataItem instanceof DIBlob || $monolingualText !== null ) {
				$text .= $monolingualText ?? $dataItem->getString();
			}
		}

		$length = mb_strlen( $text );

		// Reduces the length and finish it with a whole word
		if ( $maxLength > 0 && $length >= $maxLength ) {
			$text = Normalizer::reduceLengthTo( $text, $maxLength ) . ' …';
		}

		if ( $this->get( 'add_figures_reference', false ) ) {
			$text = wfMessage( 'semantic-imagecaption-figures', $target->semanticimagecaptioncount )->parse() . "&nbsp;$text";
		}

		return $text;
	}

	private function findMonolingualText( $property, $dataItem, $languageCode ) {

		$text  = '';

		$dataValue = DataValueFactory::getInstance()->newDataValueByItem(
			$dataItem,
			$property
		);

		if ( !$dataValue->isValid() ) {
			return $text;
		}

		$textValueByLanguageCode = $dataValue->getTextValueByLanguageCode( $languageCode );

		if ( $textValueByLanguageCode !== '' ) {
			$text = $textValueByLanguageCode;
		}

		return $text;
	}

	private function get( $key, $default ) {

		if ( $this->rule->has( "then.$key" ) ) {
			return $this->rule->then( $key, $default );
		}

		if ( $this->rule->has( "$key" ) ) {
			return $this->rule->get( $key, $default );
		}

		return $default;
	}

}
