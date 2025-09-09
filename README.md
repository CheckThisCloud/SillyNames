# SillyNames

A production-quality TypeScript library for generating silly, amusing, and memorable names. Perfect for placeholder names, test data, usernames, project codenames, or anywhere you need a touch of whimsy.

## Features

- 🎭 **Multiple Generation Strategies**: Choose from various patterns like "adjective-noun", "verb-noun", etc.
- 🔧 **Highly Configurable**: Customize separators, word limits, include numbers, and more
- 📝 **TypeScript First**: Full type safety with comprehensive type definitions
- 🧪 **Well Tested**: Comprehensive test suite with >95% coverage
- 📦 **Production Ready**: Includes linting, formatting, build pipeline, and CI/CD
- 🌟 **Easy to Use**: Simple API with both class-based and functional interfaces
- 🎯 **Deterministic**: Reproducible results for testing scenarios
- 💪 **Robust**: Input validation, error handling, and edge case coverage

## Installation

```bash
npm install sillynames
```

## Quick Start

```typescript
import { generateSillyName, SillyNameGenerator } from 'sillynames';

// Simple usage
const name = generateSillyName();
console.log(name); // "FluffyTurboWombat"

// Advanced usage
const generator = new SillyNameGenerator({
  separator: '-',
  includeNumbers: true,
  maxWords: 3
});

const sillyName = generator.generate('adjective-verb-noun');
console.log(sillyName.name); // "Bouncy-Dancing-Penguin-42"
console.log(sillyName.components); // ["Bouncy", "Dancing", "Penguin", "42"]
console.log(sillyName.pattern); // "adjective-verb-noun"
```

## API Reference

### Quick Functions

#### `generateSillyName(options?: SillyNameOptions): string`

Generates a single silly name with optional configuration.

```typescript
const name = generateSillyName({ separator: '_', includeNumbers: true });
// "Mighty_Flying_Dragon_123"
```

#### `generateMultipleSillyNames(count: number, options?: SillyNameOptions): string[]`

Generates multiple silly names at once.

```typescript
const names = generateMultipleSillyNames(3, { separator: '-' });
// ["Fuzzy-Ninja", "Electric-Wizard", "Dancing-Robot"]
```

### SillyNameGenerator Class

#### Constructor

```typescript
const generator = new SillyNameGenerator(options?: SillyNameOptions);
```

#### Methods

##### `generate(strategy?: NameGenerationStrategy): SillyName`

Generates a silly name using the specified strategy.

```typescript
const result = generator.generate('adjective-adjective-noun');
// {
//   name: "FluffyMagicUnicorn",
//   components: ["Fluffy", "Magic", "Unicorn"],
//   pattern: "adjective-adjective-noun"
// }
```

##### `generateMultiple(count: number, strategy?: NameGenerationStrategy): SillyName[]`

Generates multiple names with full metadata.

##### `generateUnique(strategy?: NameGenerationStrategy, attempts?: number): SillyName`

Attempts to generate a unique name (adds timestamp if necessary).

##### `updateOptions(newOptions: Partial<SillyNameOptions>): void`

Updates the generator configuration.

##### `getOptions(): SillyNameOptions`

Returns the current configuration.

##### `getWordListStats(): object`

Returns statistics about the word lists.

## Configuration Options

### SillyNameOptions

```typescript
interface SillyNameOptions {
  /** Include adjectives in the generated name (default: true) */
  includeAdjectives?: boolean;
  
  /** Include numbers in the generated name (default: false) */
  includeNumbers?: boolean;
  
  /** Separator to use between name parts (default: '') */
  separator?: string;
  
  /** Maximum number of words in the name (default: 3) */
  maxWords?: number;
  
  /** Minimum number of words in the name (default: 2) */
  minWords?: number;
  
  /** Custom word lists to use */
  customWords?: {
    adjectives?: string[];
    nouns?: string[];
    verbs?: string[];
  };
}
```

### Name Generation Strategies

- `'adjective-noun'`: "FluffyPenguin"
- `'adjective-adjective-noun'`: "FluffyMagicPenguin"
- `'verb-noun'`: "DancingPenguin"
- `'adjective-verb-noun'`: "FluffyDancingPenguin"
- `'random'`: Randomly selects one of the above strategies

## Examples

### Basic Usage

```typescript
import { SillyNameGenerator } from 'sillynames';

const generator = new SillyNameGenerator();
const name = generator.generate();
console.log(name.name); // "BouncyNinja"
```

### With Custom Configuration

```typescript
const generator = new SillyNameGenerator({
  separator: '-',
  includeNumbers: true,
  maxWords: 4,
  customWords: {
    adjectives: ['Super', 'Mega', 'Ultra'],
    nouns: ['Coder', 'Developer', 'Hacker']
  }
});

const name = generator.generate('adjective-adjective-noun');
console.log(name.name); // "Super-Mega-Developer-789"
```

### Multiple Names

```typescript
const names = generator.generateMultiple(5, 'verb-noun');
names.forEach(name => console.log(name.name));
// "RunningWombat"
// "FlyingDragon"
// "DancingRobot"
// etc.
```

### Unique Names

```typescript
const uniqueName = generator.generateUnique();
console.log(uniqueName.name); // "MysteriousFlyingLlama" (or with timestamp if collision)
```

### Statistics

```typescript
const stats = generator.getWordListStats();
console.log(stats);
// {
//   adjectives: 44,
//   nouns: 42,
//   verbs: 30,
//   totalCombinations: 1848
// }
```

## Development

### Setup

```bash
git clone https://github.com/CheckThisCloud/SillyNames.git
cd SillyNames
npm install
```

### Scripts

```bash
# Build the library
npm run build

# Run tests
npm test

# Run tests with coverage
npm run test:coverage

# Lint code
npm run lint

# Format code
npm run format

# Development mode (watch)
npm run dev
```

### Testing

This library includes comprehensive tests covering:

- All generation strategies
- Configuration options
- Error conditions
- Edge cases
- Type safety

Run tests with:

```bash
npm test
```

## License

MIT © [CheckThisCloud](https://github.com/CheckThisCloud)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Changelog

### 1.0.0

- Initial release
- Full TypeScript support
- Multiple generation strategies
- Comprehensive configuration options
- Production-ready build pipeline
- Extensive test coverage