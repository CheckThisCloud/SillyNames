# SillyNames PHP Library

SillyNames is a PHP library that generates silly names by combining adjectives and subjects in multiple languages (currently Czech and English). It provides reproducible random name generation with optional seeding for testing purposes.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Bootstrap and Dependencies
- **Compatible**: This project requires PHP 8.3+ and the development environment has PHP 8.3.6
- **Network Issues**: Composer install often fails due to GitHub API rate limits and timeouts
- **NEVER CANCEL**: Composer install can take 10+ minutes due to network issues. NEVER CANCEL. Set timeout to 20+ minutes minimum.

**Primary dependency installation:**
```bash
cd /home/runner/work/SillyNames/SillyNames
composer install --no-interaction --prefer-dist
```
- Takes 5-15 minutes depending on network conditions
- Often fails with timeouts - retry if needed

**Generate autoloader only (if full install fails):**
```bash
cd /home/runner/work/SillyNames/SillyNames
composer dump-autoload
```
- Takes under 5 seconds
- Enables `example.php` to run without dev dependencies
- **Alternative if this fails**: Use manual autoloader (see Testing section)

### Build Process
- **No build step required** - This is a PHP library with no compilation
- Code can run directly with proper autoloading
- All source files are in `src/` directory with PSR-4 autoloading structure

### Testing
- **Unit Tests**: Uses PHPUnit 12.3+ located in `tests/Unit/`
- **Static Analysis**: Uses PHPStan at maximum level
- **NEVER CANCEL**: Test suite takes 2-5 minutes. Set timeout to 10+ minutes.

**Run tests with Composer installed:**
```bash
cd /home/runner/work/SillyNames/SillyNames
vendor/bin/phpunit
```

**Run static analysis:**
```bash
cd /home/runner/work/SillyNames/SillyNames  
vendor/bin/phpstan analyse
```

**Basic syntax validation (if PHPStan unavailable):**
```bash
cd /home/runner/work/SillyNames/SillyNames
find src -name "*.php" -exec php -l {} \;
```
- Takes under 5 seconds
- Validates PHP syntax only (not types or logic)

**Manual testing when Composer fails:**
If Composer install fails due to network issues, use this manual autoloader for testing:

```php
<?php
// Manual autoloader
function sillynames_autoloader($class) {
    $prefix = 'CheckThisCloud\\SillyNames\\';
    if (strpos($class, $prefix) !== 0) return;
    
    $relative_class = substr($class, strlen($prefix));
    $file = '/home/runner/work/SillyNames/SillyNames/src/' . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) require $file;
}
spl_autoload_register('sillynames_autoloader');

use CheckThisCloud\SillyNames\SillyNames;

// Test basic functionality
$generator = SillyNames::getFactory('en', 12345);
echo $generator->generate(); // Should output: "Playful owl"
```

### Running the Application
- **Demo script**: `example.php` demonstrates all functionality
- **NEVER CANCEL**: Demo runs in under 1 second

**Run demo (recommended - use generated autoloader):**
```bash
cd /home/runner/work/SillyNames/SillyNames
composer dump-autoload  # Generate autoloader if needed
php example.php
```

**Run demo (alternative with manual autoloader):**
If `composer dump-autoload` fails, use the manual autoloader approach from the Testing section.

### Validation Scenarios
**ALWAYS run these validation scenarios after making changes:**

1. **Basic Name Generation:**
   ```php
   $generator = SillyNames::getFactory('en');
   $name = $generator->generate();
   // Should return string like "Brave unicorn"
   ```

2. **Seed Reproducibility:**
   ```php
   $gen1 = SillyNames::getFactory('en', 12345);
   $gen2 = SillyNames::getFactory('en', 12345);
   // $gen1->generate() === $gen2->generate() should be true
   ```

3. **Multiple Languages:**
   ```php
   $en = SillyNames::getFactory('en')->generate(); // English
   $cs = SillyNames::getFactory('cs')->generate(); // Czech with accents
   ```

4. **Error Handling:**
   ```php
   try {
       SillyNames::getFactory('invalid');
   } catch (InvalidArgumentException $e) {
       // Should throw InvalidArgumentException
   }
   ```

5. **Multiple Generation:**
   ```php
   $names = SillyNames::getFactory('en')->generateMultiple(5);
   // Should return array of exactly 5 strings
   ```

## Common Tasks

### Repository Structure
```
.
├── LICENSE                 # MIT license
├── README.md              # Basic project info
├── composer.json          # Dependencies and autoloading
├── composer.lock          # Locked dependency versions
├── example.php            # Demo script showcasing functionality
├── phpstan.neon          # Static analysis configuration (max level)
├── phpunit.xml           # PHPUnit test configuration
├── src/                  # Main source code
│   ├── Dictionary/       # Language dictionaries
│   │   ├── DictionaryInterface.php
│   │   ├── DictionaryFactory.php
│   │   ├── EnglishDictionary.php
│   │   └── CzechDictionary.php
│   └── SillyNames.php    # Main class
└── tests/                # Test suite
    └── Unit/
        ├── Dictionary/   # Dictionary tests
        └── SillyNamesTest.php
```

### Key Source Files
- **`src/SillyNames.php`**: Main class with `generate()` and `generateMultiple()` methods
- **`src/Dictionary/DictionaryFactory.php`**: Language selection (`cs`, `en`)
- **`src/Dictionary/EnglishDictionary.php`**: English adjectives and subjects
- **`src/Dictionary/CzechDictionary.php`**: Czech adjectives and subjects with proper diacritics

### Composer Scripts (if installed)
```bash
# Install dependencies (often fails due to network)
composer install

# Run tests (requires successful install)
composer test    # Runs PHPUnit
composer analyse # Runs PHPStan

# Update dependencies (use with caution)
composer update
```

## Troubleshooting

### Network/Composer Issues
- **Problem**: `composer install` times out or fails with GitHub API errors
- **Solution**: Use manual autoloader for testing (see Testing section)
- **Alternative**: Try `composer update` instead of install

### Missing Autoloader
- **Problem**: "Failed opening required 'vendor/autoload.php'"
- **Solution**: Run `composer dump-autoload` to generate autoloader
- **Alternative**: If dump-autoload fails, use manual autoloader

### Missing Vendor Directory
- **Problem**: No `vendor/` directory exists
- **Solution**: Run `composer dump-autoload` (creates vendor/autoload.php without dependencies)
- **Note**: This enables basic functionality without dev dependencies (PHPUnit, PHPStan)

## Code Quality Guidelines
- All files use `declare(strict_types=1);`
- Classes are `readonly` where appropriate
- Full type hints on all methods and properties
- PHPStan analysis at maximum level
- PSR-4 autoloading standards
- Comprehensive test coverage

## Expected Timing
- **Composer install**: 5-15 minutes (often fails due to network)
- **Composer dump-autoload**: Under 5 seconds (recommended)
- **PHPUnit tests**: 2-5 minutes (NEVER CANCEL - set 10+ minute timeout)
- **PHPStan analysis**: 1-2 minutes
- **Manual testing**: Under 1 second
- **Example demo**: Under 1 second
- **PHP syntax check**: Under 5 seconds

Always wait for commands to complete - build processes may appear to hang but are working.

## Quick Reference

**Essential Commands:**
```bash
# Generate autoloader (most important)
composer dump-autoload

# Run demo
php example.php

# Basic validation
find src -name "*.php" -exec php -l {} \;
```

**Manual Autoloader Template:**
```php
<?php
function sillynames_autoloader($class) {
    $prefix = 'CheckThisCloud\\SillyNames\\';
    if (strpos($class, $prefix) !== 0) return;
    $relative_class = substr($class, strlen($prefix));
    $file = '/path/to/SillyNames/src/' . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
}
spl_autoload_register('sillynames_autoloader');
```

**Expected Seeded Output:**
- `SillyNames::getFactory('en', 12345)->generate()` → `"Playful owl"`