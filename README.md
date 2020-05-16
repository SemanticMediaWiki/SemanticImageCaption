# Semantic Image Caption
[![Build Status](https://travis-ci.org/SemanticMediaWiki/SemanticImageCaption.svg?branch=master)](https://travis-ci.org/SemanticMediaWiki/SemanticImageCaption)
[![Code Coverage](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticImageCaption/badges/coverage.png?s=c5563fd91abeb49b37a6ef999198530b6796dd3c)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticImageCaption/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticImageCaption/badges/quality-score.png?s=9cc8ce493f63f5c2c22db71b2061b4b8c21f43ba)](https://scrutinizer-ci.com/g/SemanticMediaWiki/SemanticImageCaption/)
[![Latest Stable Version](https://poser.pugx.org/mediawiki/semantic-image-caption/version.png)](https://packagist.org/packages/mediawiki/semantic-image-caption)
[![Packagist download count](https://poser.pugx.org/mediawiki/semantic-image-caption/d/total.png)](https://packagist.org/packages/mediawiki/semantic-image-caption)

Semantic Image Caption (a.k.a. SIC) is a [Semantic MediaWiki][smw] extension to support the generation of image captions from annotations.

## Requirements

- PHP 7.1 or later
- MediaWiki 1.32 to 1.34
- Semantic MediaWiki 3.2 or later

## Installation

The recommended way to install  Semantic Image Caption is using [Composer](https://getcomposer.org) with [MediaWiki's built-in support for Composer](https://www.mediawiki.org/wiki/Composer).

Note that Semantic MediaWiki must be installed first according to the installation instructions provided.

### Step 1

Change to the base directory of your MediaWiki installation. If you do not have a "composer.local.json" file yet, create one and add the following content to it:

```json
{
	"require": {
		"mediawiki/semantic-image-caption": "~1.0"
	}
}
```

If you already have a "composer.local.json" file add the following line to the end of the "require"
section in your file:

    "mediawiki/semantic-image-caption": "~1.0"

Remember to add a comma to the end of the preceding line in this section.

### Step 2

Run the following command in your shell:

    php composer.phar update --no-dev

Note if you have Git installed on your system add the `--prefer-source` flag to the above command.

### Step 3

Add the following line to the end of your "LocalSettings.php" file:

    wfLoadExtension( 'SemanticImageCaption' );

## Usage

<img align="right" width="250" src="https://user-images.githubusercontent.com/1245473/81570680-740ffd00-9390-11ea-9db5-06f7d23b0b69.png">

Create a [schema][schema] with the [`IMAGECAPTION_RULE_SCHEMA`](/docs/imagecaption.rule.md) type to identify the `default_rule` and possible other rules to match a specific requirement for the generation of caption information.

Add caption annotations to images with a `Text` or `Monolingual text` type property (which is assigned to the `caption_property` in the schema).

Depending on the rules defined, properties used, and captions provided, embedded images will show captions generated from image page annotations.

## Contribution and support

If you have remarks, questions, or suggestions, please send them to semediawiki-users@lists.sourceforge.net. You can subscribe to this list [here](http://sourceforge.net/mailarchive/forum.php?forum_name=semediawiki-user).

If you want to contribute work to the project please subscribe to the developers mailing list and have a look at the [contribution guildline](/docs/CONTRIBUTING.md). A list of people who have made contributions in the past can be found [here][contributors].

* [File an issue](https://github.com/SemanticMediaWiki/SemanticImageCaption/issues)
* [Submit a pull request](https://github.com/SemanticMediaWiki/SemanticImageCaption/pulls)
* Ask a question on [the mailing list](https://semantic-mediawiki.org/wiki/Mailing_list)

## Tests

This extension provides unit and integration tests that are run by a [continues integration platform][travis] but can also be executed using `composer test` from the extension base directory.

## License

[GNU General Public License 2.0 or later][licence]

[composer]: https://getcomposer.org/
[licence]: https://www.gnu.org/copyleft/gpl.html
[mwcomposer]: https://www.mediawiki.org/wiki/Composer
[smw]: https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki
[travis]: https://travis-ci.org/SemanticMediaWiki/SemanticImageCaption
[mw-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing
[mw-update]: https://www.mediawiki.org/wiki/Manual:Update.php
[mw-localsettings]: https://www.mediawiki.org/wiki/Localsettings
[contributors]: https://github.com/SemanticMediaWiki/SemanticImageCaption/graphs/contributors
[semver]: http://semver.org/
[schema]: https://www.semantic-mediawiki.org/wiki/Help:Schema
