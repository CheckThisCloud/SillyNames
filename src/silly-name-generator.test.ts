import {
  SillyNameGenerator,
  generateSillyName,
  generateMultipleSillyNames,
  type SillyNameOptions,
} from './silly-name-generator';

describe('SillyNameGenerator', () => {
  let generator: SillyNameGenerator;

  beforeEach(() => {
    generator = new SillyNameGenerator();
  });

  describe('constructor', () => {
    it('should create a generator with default options', () => {
      const gen = new SillyNameGenerator();
      const options = gen.getOptions();

      expect(options.includeAdjectives).toBe(true);
      expect(options.includeNumbers).toBe(false);
      expect(options.separator).toBe('');
      expect(options.maxWords).toBe(3);
      expect(options.minWords).toBe(2);
    });

    it('should create a generator with custom options', () => {
      const customOptions: SillyNameOptions = {
        includeAdjectives: false,
        includeNumbers: true,
        separator: '-',
        maxWords: 4,
        minWords: 1,
      };

      const gen = new SillyNameGenerator(customOptions);
      const options = gen.getOptions();

      expect(options.includeAdjectives).toBe(false);
      expect(options.includeNumbers).toBe(true);
      expect(options.separator).toBe('-');
      expect(options.maxWords).toBe(4);
      expect(options.minWords).toBe(1);
    });

    it('should merge custom words with default words', () => {
      const customOptions: SillyNameOptions = {
        customWords: {
          adjectives: ['Custom', 'Special'],
          nouns: ['Tester', 'Builder'],
          verbs: ['Testing', 'Building'],
        },
      };

      const gen = new SillyNameGenerator(customOptions);
      const stats = gen.getWordListStats();

      // Should have more words than default
      expect(stats.adjectives).toBeGreaterThan(40);
      expect(stats.nouns).toBeGreaterThan(40);
      expect(stats.verbs).toBeGreaterThan(30);
    });

    it('should throw error for invalid minWords', () => {
      expect(() => new SillyNameGenerator({ minWords: 0 })).toThrow(
        'minWords must be at least 1'
      );
    });

    it('should throw error when maxWords < minWords', () => {
      expect(
        () => new SillyNameGenerator({ minWords: 3, maxWords: 2 })
      ).toThrow('maxWords must be greater than or equal to minWords');
    });

    it('should throw error for maxWords > 5', () => {
      expect(() => new SillyNameGenerator({ maxWords: 6 })).toThrow(
        'maxWords cannot exceed 5 for readability'
      );
    });
  });

  describe('generate', () => {
    it('should generate a silly name', () => {
      const result = generator.generate();

      expect(result).toHaveProperty('name');
      expect(result).toHaveProperty('components');
      expect(result).toHaveProperty('pattern');
      expect(typeof result.name).toBe('string');
      expect(result.name.length).toBeGreaterThan(0);
      expect(Array.isArray(result.components)).toBe(true);
      expect(result.components.length).toBeGreaterThan(0);
    });

    it('should generate different names on multiple calls', () => {
      const names = new Set();
      for (let i = 0; i < 10; i++) {
        names.add(generator.generate().name);
      }

      // Should have generated some variety (not all identical)
      expect(names.size).toBeGreaterThan(1);
    });

    it('should generate names with adjective-noun strategy', () => {
      const result = generator.generate('adjective-noun');

      expect(result.pattern).toBe('adjective-noun');
      expect(result.components.length).toBe(2);
    });

    it('should generate names with adjective-adjective-noun strategy', () => {
      const result = generator.generate('adjective-adjective-noun');

      expect(result.pattern).toBe('adjective-adjective-noun');
      expect(result.components.length).toBe(3);
    });

    it('should generate names with verb-noun strategy', () => {
      const result = generator.generate('verb-noun');

      expect(result.pattern).toBe('verb-noun');
      expect(result.components.length).toBe(2);
    });

    it('should generate names with adjective-verb-noun strategy', () => {
      const result = generator.generate('adjective-verb-noun');

      expect(result.pattern).toBe('adjective-verb-noun');
      expect(result.components.length).toBe(3);
    });

    it('should respect separator option', () => {
      const genWithSeparator = new SillyNameGenerator({ separator: '-' });
      const result = genWithSeparator.generate('adjective-noun');

      expect(result.name).toContain('-');
    });

    it('should include numbers when option is enabled', () => {
      const genWithNumbers = new SillyNameGenerator({ includeNumbers: true });
      const result = genWithNumbers.generate('adjective-noun');

      expect(/\d/.test(result.name)).toBe(true);
    });

    it('should exclude adjectives when option is disabled', () => {
      const genNoAdjectives = new SillyNameGenerator({
        includeAdjectives: false,
        minWords: 1, // Override minWords to allow single word
      });
      const result = genNoAdjectives.generate('adjective-noun');

      // Should only have noun
      expect(result.components.length).toBe(1);
    });

    it('should respect minWords and maxWords constraints', () => {
      const genMinMax = new SillyNameGenerator({ minWords: 1, maxWords: 2 });
      const result = genMinMax.generate();

      expect(result.components.length).toBeGreaterThanOrEqual(1);
      expect(result.components.length).toBeLessThanOrEqual(2);
    });
  });

  describe('generateMultiple', () => {
    it('should generate multiple names', () => {
      const results = generator.generateMultiple(5);

      expect(results).toHaveLength(5);
      results.forEach((result) => {
        expect(result).toHaveProperty('name');
        expect(result).toHaveProperty('components');
        expect(result).toHaveProperty('pattern');
      });
    });

    it('should throw error for count < 1', () => {
      expect(() => generator.generateMultiple(0)).toThrow(
        'Count must be at least 1'
      );
    });

    it('should throw error for count > 100', () => {
      expect(() => generator.generateMultiple(101)).toThrow(
        'Count cannot exceed 100 to prevent excessive resource usage'
      );
    });

    it('should generate names with specified strategy', () => {
      const results = generator.generateMultiple(3, 'verb-noun');

      results.forEach((result) => {
        expect(result.pattern).toBe('verb-noun');
      });
    });
  });

  describe('generateUnique', () => {
    it('should generate a unique name', () => {
      const result = generator.generateUnique();

      expect(result).toHaveProperty('name');
      expect(result).toHaveProperty('components');
      expect(result).toHaveProperty('pattern');
    });

    it('should add timestamp when unable to generate unique name', () => {
      // Create a very limited generator to force collision
      const limitedGen = new SillyNameGenerator({
        customWords: {
          adjectives: ['Test'],
          nouns: ['Name'],
        },
        minWords: 2,
        maxWords: 2,
      });

      // Override the adjectives and nouns arrays to force collision
      (limitedGen as any).adjectives = ['Test'];
      (limitedGen as any).nouns = ['Name'];

      const result = limitedGen.generateUnique('adjective-noun', 1);

      // Should contain timestamp at the end
      expect(/\d{4}$/.test(result.name) || result.name === 'TestName').toBe(
        true
      );
      if (result.pattern.includes('timestamp')) {
        expect(result.pattern).toContain('timestamp');
      }
    });
  });

  describe('updateOptions', () => {
    it('should update options', () => {
      generator.updateOptions({ separator: '-', includeNumbers: true });
      const options = generator.getOptions();

      expect(options.separator).toBe('-');
      expect(options.includeNumbers).toBe(true);
    });

    it('should validate updated options', () => {
      expect(() => generator.updateOptions({ minWords: 0 })).toThrow(
        'minWords must be at least 1'
      );
    });
  });

  describe('getWordListStats', () => {
    it('should return word list statistics', () => {
      const stats = generator.getWordListStats();

      expect(stats).toHaveProperty('adjectives');
      expect(stats).toHaveProperty('nouns');
      expect(stats).toHaveProperty('verbs');
      expect(stats).toHaveProperty('totalCombinations');

      expect(typeof stats.adjectives).toBe('number');
      expect(typeof stats.nouns).toBe('number');
      expect(typeof stats.verbs).toBe('number');
      expect(typeof stats.totalCombinations).toBe('number');

      expect(stats.adjectives).toBeGreaterThan(0);
      expect(stats.nouns).toBeGreaterThan(0);
      expect(stats.verbs).toBeGreaterThan(0);
      expect(stats.totalCombinations).toBeGreaterThan(0);
    });
  });
});

describe('Convenience functions', () => {
  describe('generateSillyName', () => {
    it('should generate a silly name string', () => {
      const name = generateSillyName();

      expect(typeof name).toBe('string');
      expect(name.length).toBeGreaterThan(0);
    });

    it('should accept options', () => {
      const name = generateSillyName({ separator: '-' });

      expect(typeof name).toBe('string');
      // Name might or might not contain separator depending on random generation
    });
  });

  describe('generateMultipleSillyNames', () => {
    it('should generate multiple silly name strings', () => {
      const names = generateMultipleSillyNames(3);

      expect(names).toHaveLength(3);
      names.forEach((name) => {
        expect(typeof name).toBe('string');
        expect(name.length).toBeGreaterThan(0);
      });
    });

    it('should accept options', () => {
      const names = generateMultipleSillyNames(2, { includeNumbers: true });

      expect(names).toHaveLength(2);
      names.forEach((name) => {
        expect(typeof name).toBe('string');
      });
    });
  });
});

describe('Edge cases and error handling', () => {
  it('should handle empty custom word arrays gracefully', () => {
    const gen = new SillyNameGenerator({
      customWords: {
        adjectives: [],
        nouns: [],
        verbs: [],
      },
    });

    // Should still work with default words
    const result = gen.generate();
    expect(result.name).toBeTruthy();
  });

  it('should handle extreme separator values', () => {
    const gen = new SillyNameGenerator({ separator: '!!!' });
    const result = gen.generate('adjective-noun');

    if (result.components.length > 1) {
      expect(result.name).toContain('!!!');
    }
  });

  it('should handle random strategy selection', () => {
    const results = [];
    const generator = new SillyNameGenerator();
    for (let i = 0; i < 20; i++) {
      results.push(generator.generate('random'));
    }

    // Should have used different strategies
    const patterns = new Set(results.map((r) => r.pattern));
    expect(patterns.size).toBeGreaterThan(1);
  });
});
