<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Dictionary;

final readonly class CzechDictionary implements DictionaryInterface
{
    /** @return array<string> */
    public function getAdjectives(): array
    {
        return [
            'rychlý',
            'chytrý',
            'veselý',
            'silný',
            'odvážný',
            'moudrý',
            'tichý',
            'jasný',
            'šťastný',
            'přátelský',
            'kreativní',
            'energický',
            'zábavný',
            'laskavý',
            'věrný',
            'upřímný',
            'trpělivý',
            'odpočatý',
            'hravý',
        ];
    }

    /** @return array<string> */
    public function getSubjects(): array
    {
        return [
            'lev',
            'tygr',
            'medvěd',
            'vlk',
            'sova',
            'delfín',
            'slon',
            'koala',
            'jednorožec',
            'drak',
            'fenix',
            'králík',
            'liška',
            'ještěrka',
            'želva',
            'koník',
            'panda',
            'kočka',
            'pes',
        ];
    }

    /**
     * Get the gender of a Czech noun
     * @return 'M'|'F' for masculine or feminine
     */
    public function getNounGender(string $noun): string
    {
        $feminineNouns = [
            'sova',      // owl
            'liška',     // fox
            'ještěrka',  // lizard
            'želva',     // turtle
            'kočka',     // cat
            'panda',     // panda
        ];

        return in_array($noun, $feminineNouns, true) ? 'F' : 'M';
    }

    /**
     * Inflect adjective to match noun gender
     */
    public function inflectAdjective(string $adjective, string $gender): string
    {
        if ($gender === 'F') {
            // Convert masculine -ý to feminine -á
            if (str_ends_with($adjective, 'ý')) {
                return mb_substr($adjective, 0, -1) . 'á';
            }
            // Handle -í adjectives (they don't change)
            if (str_ends_with($adjective, 'í')) {
                return $adjective;
            }
        }

        // Return masculine form for masculine nouns or unknown cases
        return $adjective;
    }
}
