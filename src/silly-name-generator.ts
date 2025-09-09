/**
 * Configuration options for silly name generation
 */
export interface SillyNameOptions {
  /** Include adjectives in the generated name */
  includeAdjectives?: boolean;
  /** Include numbers in the generated name */
  includeNumbers?: boolean;
  /** Separator to use between name parts */
  separator?: string;
  /** Maximum number of words in the name */
  maxWords?: number;
  /** Minimum number of words in the name */
  minWords?: number;
  /** Custom word lists to use */
  customWords?: {
    adjectives?: string[];
    nouns?: string[];
    verbs?: string[];
  };
}

/**
 * Represents a generated silly name with metadata
 */
export interface SillyName {
  /** The generated name */
  name: string;
  /** Individual components that make up the name */
  components: string[];
  /** The pattern used to generate this name */
  pattern: string;
}

/**
 * Strategy for generating names
 */
export type NameGenerationStrategy =
  | 'adjective-noun'
  | 'adjective-adjective-noun'
  | 'verb-noun'
  | 'adjective-verb-noun'
  | 'random';

/**
 * A production-quality silly name generator library
 *
 * Generates amusing and memorable names using various strategies
 * and customizable word lists.
 *
 * @example
 * ```typescript
 * const generator = new SillyNameGenerator();
 * const name = generator.generate();
 * console.log(name.name); // "FluffyTurboWombat"
 * ```
 */
export class SillyNameGenerator {
  private readonly adjectives: string[] = [
    'Fluffy',
    'Bouncy',
    'Wiggly',
    'Sparkly',
    'Goofy',
    'Zany',
    'Wacky',
    'Silly',
    'Funky',
    'Crazy',
    'Mad',
    'Wild',
    'Epic',
    'Mega',
    'Super',
    'Ultra',
    'Turbo',
    'Ninja',
    'Pirate',
    'Magic',
    'Electric',
    'Cosmic',
    'Atomic',
    'Mysterious',
    'Sneaky',
    'Dancing',
    'Flying',
    'Gigantic',
    'Tiny',
    'Fuzzy',
    'Shiny',
    'Glowing',
    'Invisible',
    'Mighty',
    'Speedy',
    'Lazy',
    'Happy',
    'Grumpy',
    'Sleepy',
    'Dizzy',
    'Hungry',
    'Thirsty',
    'Jolly',
    'Merry',
  ];

  private readonly nouns: string[] = [
    'Penguin',
    'Wombat',
    'Llama',
    'Unicorn',
    'Dragon',
    'Wizard',
    'Robot',
    'Ninja',
    'Pirate',
    'Dinosaur',
    'Alien',
    'Monster',
    'Ghost',
    'Vampire',
    'Zombie',
    'Warrior',
    'Knight',
    'Princess',
    'King',
    'Queen',
    'Frog',
    'Elephant',
    'Giraffe',
    'Monkey',
    'Dolphin',
    'Octopus',
    'Butterfly',
    'Tiger',
    'Lion',
    'Bear',
    'Wolf',
    'Fox',
    'Rabbit',
    'Squirrel',
    'Hamster',
    'Panda',
    'Koala',
    'Sloth',
    'Hedgehog',
    'Ferret',
    'Otter',
    'Seal',
  ];

  private readonly verbs: string[] = [
    'Jumping',
    'Running',
    'Flying',
    'Swimming',
    'Dancing',
    'Singing',
    'Laughing',
    'Crying',
    'Sleeping',
    'Eating',
    'Drinking',
    'Playing',
    'Fighting',
    'Hiding',
    'Seeking',
    'Climbing',
    'Falling',
    'Rolling',
    'Spinning',
    'Bouncing',
    'Wiggling',
    'Giggling',
    'Snoring',
    'Dreaming',
    'Wondering',
    'Thinking',
    'Smiling',
    'Frowning',
    'Winking',
    'Blinking',
  ];

  private options: Required<SillyNameOptions>;

  /**
   * Creates a new SillyNameGenerator instance
   * @param options Configuration options for name generation
   */
  constructor(options: SillyNameOptions = {}) {
    this.options = {
      includeAdjectives: true,
      includeNumbers: false,
      separator: '',
      maxWords: 3,
      minWords: 2,
      customWords: {},
      ...options,
    };

    // Merge custom words with default words
    if (this.options.customWords.adjectives) {
      this.adjectives.push(...this.options.customWords.adjectives);
    }
    if (this.options.customWords.nouns) {
      this.nouns.push(...this.options.customWords.nouns);
    }
    if (this.options.customWords.verbs) {
      this.verbs.push(...this.options.customWords.verbs);
    }

    this.validateOptions();
  }

  /**
   * Validates the configuration options
   * @private
   */
  private validateOptions(): void {
    if (this.options.minWords < 1) {
      throw new Error('minWords must be at least 1');
    }
    if (this.options.maxWords < this.options.minWords) {
      throw new Error('maxWords must be greater than or equal to minWords');
    }
    if (this.options.maxWords > 5) {
      throw new Error('maxWords cannot exceed 5 for readability');
    }
  }

  /**
   * Generates a silly name using the specified strategy
   * @param strategy The strategy to use for name generation
   * @returns A SillyName object containing the generated name and metadata
   */
  public generate(strategy: NameGenerationStrategy = 'random'): SillyName {
    const actualStrategy =
      strategy === 'random' ? this.getRandomStrategy() : strategy;
    const components = this.generateComponents(actualStrategy);
    const name = this.assembleName(components);

    return {
      name,
      components,
      pattern: actualStrategy,
    };
  }

  /**
   * Generates multiple silly names
   * @param count Number of names to generate
   * @param strategy Strategy to use for generation
   * @returns Array of SillyName objects
   */
  public generateMultiple(
    count: number,
    strategy: NameGenerationStrategy = 'random'
  ): SillyName[] {
    if (count < 1) {
      throw new Error('Count must be at least 1');
    }
    if (count > 100) {
      throw new Error(
        'Count cannot exceed 100 to prevent excessive resource usage'
      );
    }

    const names: SillyName[] = [];
    for (let i = 0; i < count; i++) {
      names.push(this.generate(strategy));
    }
    return names;
  }

  /**
   * Generates a unique silly name (not guaranteed to be globally unique)
   * @param strategy Strategy to use for generation
   * @param attempts Number of attempts to make before giving up
   * @returns A SillyName object
   */
  public generateUnique(
    strategy: NameGenerationStrategy = 'random',
    attempts: number = 10
  ): SillyName {
    const seenNames = new Set<string>();

    for (let i = 0; i < attempts; i++) {
      const sillyName = this.generate(strategy);
      if (!seenNames.has(sillyName.name)) {
        return sillyName;
      }
      seenNames.add(sillyName.name);
    }

    // If we couldn't generate a unique name, add a timestamp
    const fallbackName = this.generate(strategy);
    const timestamp = Date.now().toString().slice(-4);

    return {
      name: `${fallbackName.name}${this.options.separator}${timestamp}`,
      components: [...fallbackName.components, timestamp],
      pattern: `${fallbackName.pattern}-timestamp`,
    };
  }

  /**
   * Gets a random strategy for name generation
   * @private
   */
  private getRandomStrategy(): NameGenerationStrategy {
    const strategies: NameGenerationStrategy[] = [
      'adjective-noun',
      'adjective-adjective-noun',
      'verb-noun',
      'adjective-verb-noun',
    ];
    return strategies[Math.floor(Math.random() * strategies.length)];
  }

  /**
   * Generates name components based on the strategy
   * @private
   */
  private generateComponents(strategy: NameGenerationStrategy): string[] {
    const components: string[] = [];

    switch (strategy) {
      case 'adjective-noun':
        if (this.options.includeAdjectives) {
          components.push(this.getRandomFromArray(this.adjectives));
        }
        components.push(this.getRandomFromArray(this.nouns));
        break;

      case 'adjective-adjective-noun':
        if (this.options.includeAdjectives) {
          components.push(this.getRandomFromArray(this.adjectives));
          components.push(this.getRandomFromArray(this.adjectives));
        }
        components.push(this.getRandomFromArray(this.nouns));
        break;

      case 'verb-noun':
        components.push(this.getRandomFromArray(this.verbs));
        components.push(this.getRandomFromArray(this.nouns));
        break;

      case 'adjective-verb-noun':
        if (this.options.includeAdjectives) {
          components.push(this.getRandomFromArray(this.adjectives));
        }
        components.push(this.getRandomFromArray(this.verbs));
        components.push(this.getRandomFromArray(this.nouns));
        break;

      default:
        throw new Error(`Unknown strategy: ${strategy}`);
    }

    // Ensure we respect minWords and maxWords
    while (components.length < this.options.minWords) {
      components.unshift(this.getRandomFromArray(this.adjectives));
    }
    while (components.length > this.options.maxWords) {
      components.splice(1, 1); // Remove middle elements, keep first and last
    }

    return components;
  }

  /**
   * Assembles the final name from components
   * @private
   */
  private assembleName(components: string[]): string {
    let name = components.join(this.options.separator);

    if (this.options.includeNumbers) {
      const number = Math.floor(Math.random() * 1000);
      name += `${this.options.separator}${number}`;
    }

    return name;
  }

  /**
   * Gets a random element from an array
   * @private
   */
  private getRandomFromArray<T>(array: T[]): T {
    if (array.length === 0) {
      throw new Error('Cannot get random element from empty array');
    }
    return array[Math.floor(Math.random() * array.length)];
  }

  /**
   * Gets the current configuration options
   * @returns A copy of the current options
   */
  public getOptions(): SillyNameOptions {
    return { ...this.options };
  }

  /**
   * Updates the configuration options
   * @param newOptions Partial options to update
   */
  public updateOptions(newOptions: Partial<SillyNameOptions>): void {
    this.options = { ...this.options, ...newOptions };
    this.validateOptions();
  }

  /**
   * Gets statistics about the word lists
   * @returns Object containing word list statistics
   */
  public getWordListStats(): {
    adjectives: number;
    nouns: number;
    verbs: number;
    totalCombinations: number;
  } {
    const adjectives = this.adjectives.length;
    const nouns = this.nouns.length;
    const verbs = this.verbs.length;

    // Calculate approximate total combinations for adjective-noun pattern
    const totalCombinations = adjectives * nouns;

    return {
      adjectives,
      nouns,
      verbs,
      totalCombinations,
    };
  }
}

/**
 * Default export for easy importing
 */
export default SillyNameGenerator;

/**
 * Convenience function to quickly generate a silly name
 * @param options Optional configuration
 * @returns A silly name string
 */
export function generateSillyName(options?: SillyNameOptions): string {
  const generator = new SillyNameGenerator(options);
  return generator.generate().name;
}

/**
 * Convenience function to generate multiple silly names
 * @param count Number of names to generate
 * @param options Optional configuration
 * @returns Array of silly name strings
 */
export function generateMultipleSillyNames(
  count: number,
  options?: SillyNameOptions
): string[] {
  const generator = new SillyNameGenerator(options);
  return generator.generateMultiple(count).map((sillyName) => sillyName.name);
}
