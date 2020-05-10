<!-- Begin of generated contents by readmeContentsBuilder.php -->

## List of tests

- Files: 2 (includes 4 tests)
- Last update: 2020-05-09

### A
* [sic-01.json](https://github.com/SemanticMediaWiki/SemanticImageCaption/tree/master/tests/phpunit/Integration/JSONScript/TestCases/a-01.json) Test generation of image caption from `default_rule`
* [sic-02.json](https://github.com/SemanticMediaWiki/SemanticImageCaption/tree/master/tests/phpunit/Integration/JSONScript/TestCases/a-02.json) Test generation of image caption from category/language dependent rules

<!-- End of generated contents by readmeContentsBuilder.php -->

## Writing a test case

### Assertions

Integration tests aim to prove that the "integration" between MediaWiki and Semantic MediaWiki works at a sufficient level therefore assertion may only check or verify a specific part of an output or data to avoid that system information (DB ID, article url etc.) distort to overall test results.

### Add a new test case

- Follow the `sic-01.json` example on how to structure the JSON file (setup,  test etc.)
- Add example pages with content (including value annotations `[[SomeProperty::SomeValue]]`) expected to be tested
- If necessary add a new lua module file to the Fixtures directory and import the content for the test (see `sic-01.json`)
