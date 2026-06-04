<?php

namespace SMW\ImageCaption;

use File;
use MediaWiki\Title\Title;
use SMW\DataItems\Blob;
use SMW\DataItems\Property;
use SMW\DataItems\WikiPage;
use SMW\DataValues\MonolingualTextValue;
use SMW\RequestOptions;
use SMW\Services\Exception\ServiceNotFoundException;
use SMW\Store;
use SMW\Utils\Normalizer;
use WeakMap;

/**
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class ImageCaption {

	public const SCHEMA_TYPE = 'IMAGECAPTION_RULE_SCHEMA';

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
	 * Running count of embedded images, keyed by the page they appear on, used
	 * to number the `Figure N` references.
	 *
	 * @var WeakMap<Title, int>|null
	 */
	private static ?WeakMap $figureCounts = null;

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

		// Track the running count of embedded images for the page
		self::$figureCounts ??= new WeakMap();
		self::$figureCounts[$target] = ( self::$figureCounts[$target] ?? 0 ) + 1;

		$subject = WikiPage::newFromTitle(
			$file->getTitle()
		);

		$text = $this->findText( $subject, $target, $caption, $languageCode );

		if ( $text !== '' ) {
			$caption = $text;
		}
	}

	private function findText( WikiPage $subject, Title $target, string $caption, string $languageCode ): string {
		$requestOptions = new RequestOptions();
		$requestOptions->setCaller( __METHOD__ );

		$dataItems = $this->store->getPropertyValues(
			$subject,
			new Property( '_INST' ),
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

		$property = $this->get( 'caption_property', '' );

		if ( $property === '' ) {
			return '';
		}

		$property = Property::newFromUserLabel( $property );

		$text = '';
		$maxLength = $this->get( 'max_length', 200 );

		if ( $property->findPropertyValueType() === MonolingualTextValue::TYPE_ID ) {
			$text .= $this->fetchTextByLanguageCode( $subject, $property, $languageCode );
		} else {
			$dataItems = $this->store->getPropertyValues(
				$subject,
				$property,
				$requestOptions
			);

			if ( $dataItems === [] ) {
				return '';
			}

			foreach ( $dataItems as $dataItem ) {

				if ( !$dataItem instanceof Blob ) {
					continue;
				}

				$text .= $dataItem->getString();
			}
		}

		$length = mb_strlen( $text );

		// Reduces the length and finish it with a whole word
		if ( $maxLength > 0 && $length >= $maxLength ) {
			$text = Normalizer::reduceLengthTo( $text, $maxLength ) . ' …';
		}

		if ( $this->get( 'add_figures_reference', false ) ) {
			$figureNumber = self::$figureCounts[$target] ?? 1;
			$text = wfMessage( 'semantic-imagecaption-figures', $figureNumber )->parse() . "&nbsp;$text";
		}

		return $text;
	}

	private function fetchTextByLanguageCode( $subject, $property, $languageCode ) {
		try {
			$monolingualTextLookup = $this->store->service( 'MonolingualTextLookup' );
		} catch ( ServiceNotFoundException $e ) {
			return '';
		}

		if ( $monolingualTextLookup === null ) {
			return '';
		}

		$monolingualTextLookup->setCaller( __METHOD__ );

		$dataValue = $monolingualTextLookup->newDataValue(
			$subject,
			$property,
			$languageCode
		);

		if ( $dataValue === null ) {
			return '';
		}

		$dv = $dataValue->getTextValueByLanguageCode(
			$languageCode
		);

		return $dv->getShortWikiText();
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
