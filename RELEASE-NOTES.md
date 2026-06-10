This file contains the RELEASE-NOTES of the **Semantic Image Caption** (a.k.a. SIC) extension.

### 2.0.1

Released on June 8, 2026.

- Updated the `composer/installers` constraint to `^2|^1.0.1`.

### 2.0.0

Released on June 8, 2026.

- Raised the minimum supported PHP version to 8.1
- Raised the minimum supported MediaWiki version to 1.43
- Raised the minimum supported Semantic MediaWiki version to 7.0
- Updated the internal usage of Semantic MediaWiki data-item classes to their Semantic MediaWiki 7.0 namespaced names.
- Fixed caption generation from a `Monolingual text` property silently failing when the monolingual text lookup service is unavailable.
- Modernized extension registration: removed the procedural `SemanticImageCaption.php` entry point, its `initExtension` callback, and the empty extension function in favor of native `extension.json` declarations. The internal `SMW_IMAGECAPTION_VERSION` constant has been removed.
- Localization updates from https://translatewiki.net
