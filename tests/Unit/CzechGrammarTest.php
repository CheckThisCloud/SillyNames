<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Tests\Unit;

use CheckThisCloud\SillyNames\SillyNames;
use CheckThisCloud\SillyNames\Dictionary\CzechDictionary;
use PHPUnit\Framework\TestCase;

final class CzechGrammarTest extends TestCase
{
    public function testCzechAdjectiveInflection(): void
    {
        $dictionary = new CzechDictionary();
        
        // Test masculine adjectives remain unchanged
        $this->assertSame('silný', $dictionary->inflectAdjective('silný', 'M'));
        $this->assertSame('veselý', $dictionary->inflectAdjective('veselý', 'M'));
        
        // Test feminine adjectives get proper inflection (-ý → -á)
        $this->assertSame('silná', $dictionary->inflectAdjective('silný', 'F'));
        $this->assertSame('veselá', $dictionary->inflectAdjective('veselý', 'F'));
        $this->assertSame('rychlá', $dictionary->inflectAdjective('rychlý', 'F'));
        
        // Test that -í adjectives don't change
        $this->assertSame('kreativní', $dictionary->inflectAdjective('kreativní', 'F'));
        $this->assertSame('kreativní', $dictionary->inflectAdjective('kreativní', 'M'));
    }

    public function testCzechNounGenders(): void
    {
        $dictionary = new CzechDictionary();
        
        // Test feminine nouns
        $this->assertSame('F', $dictionary->getNounGender('sova'));
        $this->assertSame('F', $dictionary->getNounGender('ještěrka'));
        $this->assertSame('F', $dictionary->getNounGender('liška'));
        $this->assertSame('F', $dictionary->getNounGender('želva'));
        $this->assertSame('F', $dictionary->getNounGender('kočka'));
        $this->assertSame('F', $dictionary->getNounGender('panda'));
        
        // Test masculine nouns
        $this->assertSame('M', $dictionary->getNounGender('lev'));
        $this->assertSame('M', $dictionary->getNounGender('tygr'));
        $this->assertSame('M', $dictionary->getNounGender('vlk'));
        $this->assertSame('M', $dictionary->getNounGender('delfín'));
        $this->assertSame('M', $dictionary->getNounGender('pes'));
    }

    public function testCzechGrammarInGeneration(): void
    {
        // Test with specific seed to get predictable combinations
        $generator = SillyNames::getFactory('cs', 42);
        
        // Generate multiple names and check for proper grammar
        $names = $generator->generateMultiple(50);
        
        foreach ($names as $name) {
            $this->assertNotEmpty($name);
            
            $parts = explode(' ', $name);
            $this->assertCount(2, $parts, "Name should have exactly 2 parts: '$name'");
            
            $adjective = mb_strtolower($parts[0]);
            $noun = $parts[1];
            
            // Check feminine nouns have feminine adjective endings
            $feminineNouns = ['sova', 'liška', 'ještěrka', 'želva', 'kočka', 'panda'];
            if (in_array($noun, $feminineNouns, true)) {
                $this->assertTrue(
                    str_ends_with($adjective, 'á') || str_ends_with($adjective, 'í'),
                    "Feminine noun '$noun' should have feminine adjective ending (-á or -í), got: '$adjective' in '$name'"
                );
            }
        }
    }

    public function testSpecificProblemCase(): void
    {
        // Test the specific issue mentioned: "Silný ještěrka" should become "Silná ještěrka"
        $dictionary = new CzechDictionary();
        
        // Test the inflection directly
        $this->assertSame('Silná', ucfirst($dictionary->inflectAdjective('silný', 'F')));
        
        // Test that ještěrka is identified as feminine
        $this->assertSame('F', $dictionary->getNounGender('ještěrka'));
        
        // Generate many names to statistically ensure we get the right combination
        $generator = SillyNames::getFactory('cs');
        $found = false;
        
        for ($i = 0; $i < 1000 && !$found; $i++) {
            $name = $generator->generate();
            if (str_contains($name, 'ještěrka')) {
                $parts = explode(' ', $name);
                $adjective = mb_strtolower($parts[0]);
                // Should not end in -ý (masculine) but in -á or -í (feminine)
                $this->assertFalse(
                    str_ends_with($adjective, 'ý'),
                    "Found incorrect grammar: '$name' - ještěrka is feminine and should not have masculine adjective ending -ý"
                );
                $found = true;
            }
        }
    }
}