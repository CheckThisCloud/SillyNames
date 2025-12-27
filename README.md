# SillyNames

A PHP library for generating silly names by combining adjectives and subjects. Perfect for creating fun usernames, project codenames, or placeholder names for testing.

[![Latest Stable Version](https://poser.pugx.org/checkthiscloud/silly-names/v/stable)](https://packagist.org/packages/checkthiscloud/silly-names)
[![Total Downloads](https://poser.pugx.org/checkthiscloud/silly-names/downloads)](https://packagist.org/packages/checkthiscloud/silly-names)
[![License](https://poser.pugx.org/checkthiscloud/silly-names/license)](https://packagist.org/packages/checkthiscloud/silly-names)
[![PHP Version Require](https://poser.pugx.org/checkthiscloud/silly-names/require/php)](https://packagist.org/packages/checkthiscloud/silly-names)

## Features

- 🎯 **Simple API** - Easy to use with sensible defaults
- 🌍 **Multi-language Support** - Currently supports English and Czech
- 🎲 **Deterministic Generation** - Reproducible results with seed support
- 🔒 **Type Safe** - Full PHP type declarations and readonly classes
- ✅ **Well Tested** - Comprehensive test suite
- 📦 **Zero Dependencies** - No external runtime dependencies

## Installation

Install via Composer:

```bash
composer require checkthiscloud/silly-names
```

## Requirements

- PHP 8.3 or higher

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use CheckThisCloud\SillyNames\SillyNames;

// Generate a random silly name in English (default)
$generator = SillyNames::getFactory();
echo $generator->generate(); // e.g., "Swift Tiger"

// Generate names in Czech
$czechGenerator = SillyNames::getFactory('cs');
echo $czechGenerator->generate(); // e.g., "Rychlý Lev"

// Generate multiple names at once
$names = $generator->generateMultiple(5);
foreach ($names as $name) {
    echo $name . "\n";
}
```

## Usage

### Basic Usage

```php
use CheckThisCloud\SillyNames\SillyNames;

// Create a generator
$generator = SillyNames::getFactory('en'); // 'en' for English, 'cs' for Czech

// Generate a single name
$name = $generator->generate();
echo $name; // "Mighty Dragon"

// Generate multiple names
$names = $generator->generateMultiple(3);
// Output: ["Happy Koala", "Clever Wolf", "Brave Owl"]
```

### Supported Languages

Check available languages programmatically:

```php
// Get list of supported languages
$languages = SillyNames::getSupportedLanguages();
// Returns: ['cs', 'en']

foreach ($languages as $lang) {
    $generator = SillyNames::getFactory($lang);
    echo "$lang: " . $generator->generate() . "\n";
}
```

Currently supported:
- `en` - English (default)
- `cs` - Czech

```php
// English names
$enGenerator = SillyNames::getFactory('en');
echo $enGenerator->generate(); // "Creative Elephant"

// Czech names  
$csGenerator = SillyNames::getFactory('cs');
echo $csGenerator->generate(); // "Kreativní Slon"
```

### Reproducible Results with Seeds

Use seeds to generate reproducible sequences of names:

```php
// Using a seed for reproducible results
$seededGenerator = SillyNames::getFactory('en', 12345);

$firstRun = $seededGenerator->generateMultiple(3);
// ["Swift Lion", "Happy Bear", "Clever Fox"]

// Create another generator with the same seed
$anotherGenerator = SillyNames::getFactory('en', 12345);
$secondRun = $anotherGenerator->generateMultiple(3);
// ["Swift Lion", "Happy Bear", "Clever Fox"] - identical results!

assert($firstRun === $secondRun); // true
```

### Error Handling

```php
try {
    $generator = SillyNames::getFactory('invalid');
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // "Dictionary for language 'invalid' not found"
}
```

## Name Format

Generated names follow the pattern: `"Adjective Subject"`

- **Adjective**: Capitalized first letter, lowercase remainder
- **Subject**: All lowercase
- **Separator**: Single space

Examples:
- `"Swift tiger"`
- `"Mighty dragon"`
- `"Clever koala"`

## Available Words

### English Dictionary
- **Adjectives**: 116 words including swift, clever, happy, mighty, brave, wise, quiet, bright, cheerful, friendly, creative, energetic, funny, kind, loyal, honest, patient, calm, playful, graceful, bold, curious, gentle, mysterious, adventurous, dazzling, whimsical, zealous, and many more
- **Subjects**: 90 animals including lion, tiger, bear, wolf, owl, dolphin, elephant, koala, unicorn, dragon, phoenix, rabbit, fox, penguin, narwhal, platypus, octopus, and many more
- **Total combinations**: Over 10,000 unique silly names!

### Czech Dictionary
- **Adjectives**: rychlý, chytrý, veselý, silný, odvážný, moudrý, tichý, jasný, šťastný, přátelský, kreativní, energický, zábavný, laskavý, věrný, upřímný, trpělivý, odpočatý, hravý
- **Subjects**: lev, tygr, medvěd, vlk, sova, delfín, slon, koala, jednorožec, drak, fenix, králík, liška, ještěrka, želva, koník, panda, kočka, pes

## API Reference

### `SillyNames::getFactory(string $language = 'cs', ?int $seed = null): SillyNames`

Creates a new SillyNames generator.

**Parameters:**
- `$language` (string): Language code ('en' or 'cs'). Defaults to 'cs'.
- `$seed` (int|null): Optional seed for reproducible random generation.

**Returns:** `SillyNames` instance

**Throws:** `InvalidArgumentException` if language is not supported

### `SillyNames::getSupportedLanguages(): array<string>`

Returns an array of supported language codes.

**Returns:** Array of language code strings

### `generate(): string`

Generates a single silly name.

**Returns:** A silly name string in "Adjective subject" format

### `generateMultiple(int $count): array<string>`

Generates multiple silly names.

**Parameters:**
- `$count` (int): Number of names to generate

**Returns:** Array of silly name strings

## Development

### Running Tests

```bash
composer test
```

### Static Analysis

```bash
composer analyse
```

### Running All Checks

```bash
composer check
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Adding New Languages

To add support for a new language:

1. Create a new dictionary class implementing `DictionaryInterface`
2. Add the language code to `DictionaryFactory::create()`
3. Update `DictionaryFactory::getSupportedLanguages()`
4. Add tests for the new language

## License

This project is licensed under the GPL-3.0-or-later License - see the [LICENSE](LICENSE) file for details.

## Examples

Check out [example.php](example.php) for a complete demonstration of the library's features.