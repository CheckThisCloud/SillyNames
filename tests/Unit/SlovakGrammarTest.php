<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Tests\Unit;

use CheckThisCloud\SillyNames\SillyNames;
use CheckThisCloud\SillyNames\Dictionary\SlovakDictionary;
use PHPUnit\Framework\TestCase;

final class SlovakGrammarTest extends TestCase
{
    public function testSlovakAdjectiveInflection(): void
    {
        $dictionary = new SlovakDictionary();
        
        // Test masculine adjectives remain unchanged
        $this->assertSame('silný', $dictionary->inflectAdjective('silný', 'M'));
        $this->assertSame('veselý', $dictionary->inflectAdjective('veselý', 'M'));
        $this->assertSame('priateľský', $dictionary->inflectAdjective('priateľský', 'M'));
        
        // Test feminine adjectives get proper inflection (-ý → -á)
        $this->assertSame('silná', $dictionary->inflectAdjective('silný', 'F'));
        $this->assertSame('veselá', $dictionary->inflectAdjective('veselý', 'F'));
        $this->assertSame('rýchla', $dictionary->inflectAdjective('rýchly', 'F'));
        
        // Test -ský endings (-ský → -ská)
        $this->assertSame('priateľská', $dictionary->inflectAdjective('priateľský', 'F'));
        
        // Test -ný endings (-ný → -ná)
        $this->assertSame('kreatívna', $dictionary->inflectAdjective('kreatívny', 'F'));
        $this->assertSame('energická', $dictionary->inflectAdjective('energický', 'F'));
        
        // Test neuter forms
        $this->assertSame('silné', $dictionary->inflectAdjective('silný', 'N'));
        $this->assertSame('veselé', $dictionary->inflectAdjective('veselý', 'N'));
        $this->assertSame('priateľské', $dictionary->inflectAdjective('priateľský', 'N'));
    }

    public function testSlovakNounGenders(): void
    {
        $dictionary = new SlovakDictionary();
        
        // Test feminine nouns
        $this->assertSame('F', $dictionary->getNounGender('sova'));
        $this->assertSame('F', $dictionary->getNounGender('jašterica'));
        $this->assertSame('F', $dictionary->getNounGender('líška'));
        $this->assertSame('F', $dictionary->getNounGender('korytnačka'));
        $this->assertSame('F', $dictionary->getNounGender('mačka'));
        $this->assertSame('F', $dictionary->getNounGender('panda'));
        
        // Test masculine nouns
        $this->assertSame('M', $dictionary->getNounGender('lev'));
        $this->assertSame('M', $dictionary->getNounGender('tiger'));
        $this->assertSame('M', $dictionary->getNounGender('medveď'));
        $this->assertSame('M', $dictionary->getNounGender('vlk'));
        $this->assertSame('M', $dictionary->getNounGender('delfín'));
        $this->assertSame('M', $dictionary->getNounGender('slon'));
        $this->assertSame('M', $dictionary->getNounGender('pes'));
    }

    public function testGeneratedSlovakNamesHaveCorrectGrammar(): void
    {
        $generator = SillyNames::getFactory('sk');
        
        // Generate multiple names and check grammar
        for ($i = 0; $i < 50; $i++) {
            $name = $generator->generate();
            $parts = explode(' ', $name);
            
            $this->assertCount(2, $parts, "Name should have exactly 2 parts: '$name'");
            
            $adjective = mb_strtolower($parts[0]);
            $noun = $parts[1];
            
            // Check feminine nouns have feminine adjective endings
            $feminineNouns = ['sova', 'líška', 'jašterica', 'korytnačka', 'mačka', 'panda'];
            if (in_array($noun, $feminineNouns, true)) {
                $this->assertTrue(
                    str_ends_with($adjective, 'á') || str_ends_with($adjective, 'na') || str_ends_with($adjective, 'ká') || str_ends_with($adjective, 'la') || str_ends_with($adjective, 'ra'),
                    "Feminine noun '$noun' should have feminine adjective ending, got: '$adjective' in '$name'"
                );
            }
        }
    }

    public function testSpecificSlovakProblemCase(): void
    {
        // Test that feminine nouns get correct adjective agreement
        $dictionary = new SlovakDictionary();
        
        // Test the inflection directly
        $this->assertSame('Silná', ucfirst($dictionary->inflectAdjective('silný', 'F')));
        
        // Test that jašterica is identified as feminine
        $this->assertSame('F', $dictionary->getNounGender('jašterica'));
        
        // Generate many names to statistically ensure we get the right combination
        $generator = SillyNames::getFactory('sk');
        $found = false;
        
        for ($i = 0; $i < 1000 && !$found; $i++) {
            $name = $generator->generate();
            if (str_contains($name, 'jašterica')) {
                $parts = explode(' ', $name);
                $adjective = mb_strtolower($parts[0]);
                // Should not end in -ý (masculine) but in -á or other feminine endings
                $this->assertFalse(
                    str_ends_with($adjective, 'ý'),
                    "Found incorrect grammar: '$name' - jašterica is feminine and should not have masculine adjective ending -ý"
                );
                $found = true;
            }
        }
    }

    public function testSlovakLanguageFactoryIntegration(): void
    {
        // Test that Slovak is properly supported
        $languages = SillyNames::getSupportedLanguages();
        $this->assertContains('sk', $languages);
        
        // Test factory creation
        $generator = SillyNames::getFactory('sk');
        $this->assertInstanceOf(SillyNames::class, $generator);
        
        // Test name generation
        $name = $generator->generate();
        $this->assertIsString($name);
        $this->assertNotEmpty($name);
        $this->assertStringContainsString(' ', $name);
    }
}