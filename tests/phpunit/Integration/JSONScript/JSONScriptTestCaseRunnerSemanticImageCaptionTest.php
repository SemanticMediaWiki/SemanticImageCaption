<?php

namespace SMW\ImageCaption\Tests\Integration\JSONScript;

use SMW\Tests\JSONScriptServicesTestCaseRunner;

/**
 * @see https://github.com/SemanticMediaWiki/SemanticMediaWiki/blob/master/tests/phpunit/Integration/JSONScript/docs/extension.md
 *
 * @group semantic-image-caption
 * @group Database
 * @group medium
 *
 * @license GPL-2.0-or-later
 * @since 1.0
 *
 * @author mwjames
 */
class JSONScriptTestCaseRunnerSemanticImageCaptionTest extends JSONScriptServicesTestCaseRunner {

	/**
	 * @see JSONScriptServicesTestCaseRunner::runTestAssertionForType
	 */
	protected function runTestAssertionForType( string $type ): bool {
		return $type === 'parser';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getTestCaseLocation
	 * @return string
	 */
	protected function getTestCaseLocation(): string {
		return __DIR__ . '/TestCases';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getRequiredJsonTestCaseMinVersion
	 * @return string
	 */
	protected function getRequiredJsonTestCaseMinVersion(): string {
		return '1';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getPermittedSettings
	 */
	protected function getPermittedSettings(): array {
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
