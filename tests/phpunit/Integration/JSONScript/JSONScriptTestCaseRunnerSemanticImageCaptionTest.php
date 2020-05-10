<?php

namespace SMW\ImageCaption\Tests\Integration\JSONScript;

use SMW\Tests\JSONScriptServicesTestCaseRunner;
use SMW\ImageCaption\Hooks;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticMediaWiki/blob/master/tests/phpunit/Integration/JSONScript/docs/extension.md
 *
 * @group semantic-image-caption
 * @group medium
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class JSONScriptTestCaseRunnerSemanticImageCaptionTest extends JSONScriptServicesTestCaseRunner {

	/**
	 * @var Hooks
	 */
	private $hooks;

	protected function setUp() : void {
		parent::setUp();

		$this->hooks = new Hooks();
		$this->hooks->register();
	}

	/**
	 * @see JSONScriptServicesTestCaseRunner::runTestAssertionForType
	 */
	protected function runTestAssertionForType( string $type ) : bool {
		return $type === 'parser';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getTestCaseLocation
	 * @return string
	 */
	protected function getTestCaseLocation() {
		return __DIR__ . '/TestCases';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getRequiredJsonTestCaseMinVersion
	 * @return string
	 */
	protected function getRequiredJsonTestCaseMinVersion() {
		return '1';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getPermittedSettings
	 */
	protected function getPermittedSettings() {
		$settings = parent::getPermittedSettings();

		return array_merge( $settings, [
			'smwgNamespacesWithSemanticLinks',
			'smwgPageSpecialProperties',
			'wgLanguageCode',
			'wgContLang',
			'wgLang'
		] );
	}

}
