## Objective

The `IMAGECAPTION_RULE_SCHEMA` schema type defines a set of rules for how and when image captions should be generated.

### Naming convention

To easily identify pages that contain an `IMAGECAPTION_RULE_SCHEMA` schema it is suggested to use `smw/schema:Caption:...` as naming convention.

## Properties

- `type` defines the type and is fixed to `IMAGECAPTION_RULE_SCHEMA`
- `caption_rules` the section that contains the rule definitions
- `tags` simple tags to categorize a schema

### Example

<pre>
{
    "type": "IMAGECAPTION_RULE_SCHEMA",
    "caption_rules": {
        "default_rule": {
            "max_length": 200,
            "allow_caption_override": false,
            "add_figures_reference": true,
            "caption_property": "Has text"
        },
        "z_caption_rule": {
            "if": {
                "category": "Z category"
            },
            "then": {
                "add_figures_reference": false,
                "caption_property": "Language dependent caption",
                "max_length": 200,
                "allow_caption_override": true
            }
        }
    },
    "tags": [
        "image",
        "image caption"
    ]
}
</pre>

### Default rule

The `default_rule` (without any conditional) applies when no other rule could be matched to an image context.

- `caption_property` defines the property from where the text for a caption is fetched
- `max_length` maximum length of characters for a caption
- `allow_caption_override` to define that an auto generated caption is allowed to be used even though the `[[File::Image.png|some text]]` provides its own local text
- `add_figures_reference` to define that captions will use a `Figure` identification (see https://rmit.libguides.com/harvardvisual/figures)

### Conditional rule

A conditional rule is identified by a `..._rule` name and contains a set of properties including:

- `if` defines the conditional as to when the rule applies and supports the following filters:
  - `category` identifies the category when the rule is expected to be used (supports a simple string, use of `oneOf` and `allOf`)
- `then` defines the operational consequence in case the rule is selected and has the following properties:
  - `caption_property` defines the property from where the text for a caption is fetched
  - `max_length` maximum length of characters for a caption
  - `allow_caption_override` to define that an auto generated caption is allowed to be used even though the `[[File::Image.png|some text]]` provides its own local text
  - `add_figures_reference` to define that captions will use a `Figure` identification (see https://rmit.libguides.com/harvardvisual/figures)

## Note

In case the `caption_property` references a `Monolingual text` type property then the generated caption will be bound to the selected user language otherwise only a text type property will be recognized for the generation of caption information.

## Validation

`/data/schema/imagecaption-rule-schema.v1.json`
