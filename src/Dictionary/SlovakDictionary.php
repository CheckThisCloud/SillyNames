<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Dictionary;

final readonly class SlovakDictionary implements DictionaryInterface
{
    /** @return array<string> */
    public function getAdjectives(): array
    {
        return [
            'rýchly',
            'múdry',
            'veselý',
            'silný',
            'odvážny',
            'tichý',
            'jasný',
            'šťastný',
            'priateľský',
            'kreatívny',
            'energický',
            'zábavný',
            'laskavý',
            'verný',
            'úprimný',
            'trpezlivý',
            'pokojný',
            'hravý',
            'graciezny',
            'odvážny',
            'zvedavý',
            'jemný',
            'tajomný',
        ];
    }

    /** @return array<string> */
    public function getSubjects(): array
    {
        return [
            'lev',
            'tiger',
            'medveď',
            'vlk',
            'sova',
            'delfín',
            'slon',
            'koala',
            'jednorožec',
            'drak',
            'fénix',
            'zajac',
            'líška',
            'jašterica',
            'korytnačka',
            'kôň',
            'panda',
            'mačka',
            'pes',
        ];
    }

    /**
     * Get the gender of a Slovak noun
     * @return 'M'|'F'|'N' for masculine, feminine, or neuter
     */
    public function getNounGender(string $noun): string
    {
        $feminineNouns = [
            'sova',        // owl
            'líška',       // fox
            'jašterica',   // lizard
            'korytnačka',  // turtle
            'mačka',       // cat
            'panda',       // panda
        ];

        $neuterNouns = [
            // Slovak has fewer neuter animals, but including some possibilities
        ];

        if (in_array($noun, $feminineNouns, true)) {
            return 'F';
        }
        
        if (in_array($noun, $neuterNouns, true)) {
            return 'N';
        }
        
        return 'M';
    }

    /**
     * Inflect adjective to match noun gender
     */
    public function inflectAdjective(string $adjective, string $gender): string
    {
        if ($gender === 'F') {
            // Handle specific endings first (most specific to least specific)
            // Use mb_substr for proper UTF-8 handling
            
            // Handle -ický ending (energický -> energická)
            if (mb_substr($adjective, -4) === 'ický') {
                return mb_substr($adjective, 0, -4) . 'ická';
            }
            // Handle -ský ending (priateľský -> priateľská)
            if (mb_substr($adjective, -3) === 'ský') {
                return mb_substr($adjective, 0, -3) . 'ská';
            }
            // Handle -ny ending (graciezny -> graciezna, kreatívny -> kreatívna)
            if (mb_substr($adjective, -2) === 'ny') {
                return mb_substr($adjective, 0, -2) . 'na';
            }
            // Handle words ending in 'ly' where previous char is accented (rýchly -> rýchla)
            if (mb_substr($adjective, -2) === 'ly') {
                return mb_substr($adjective, 0, -2) . 'la';
            }
            // Handle words ending in 'ry' where previous char is accented (múdry -> múdra)
            if (mb_substr($adjective, -2) === 'ry') {
                return mb_substr($adjective, 0, -2) . 'ra';
            }
            // Convert masculine -ý to feminine -á (for words actually ending in ý with accent)
            if (mb_substr($adjective, -1) === 'ý') {
                return mb_substr($adjective, 0, -1) . 'á';
            }
        }
        
        if ($gender === 'N') {
            // Handle specific endings first (most specific to least specific)
            // Use mb_substr for proper UTF-8 handling
            
            // Handle -ický ending (energický -> energické)
            if (mb_substr($adjective, -4) === 'ický') {
                return mb_substr($adjective, 0, -4) . 'ické';
            }
            // Handle -ský ending (priateľský -> priateľské)
            if (mb_substr($adjective, -3) === 'ský') {
                return mb_substr($adjective, 0, -3) . 'ské';
            }
            // Handle -ny ending (graciezny -> graciezne, kreatívny -> kreatívne)
            if (mb_substr($adjective, -2) === 'ny') {
                return mb_substr($adjective, 0, -2) . 'ne';
            }
            // Handle words ending in 'ly' where previous char is accented (rýchly -> rýchle)
            if (mb_substr($adjective, -2) === 'ly') {
                return mb_substr($adjective, 0, -2) . 'le';
            }
            // Handle words ending in 'ry' where previous char is accented (múdry -> múdre)
            if (mb_substr($adjective, -2) === 'ry') {
                return mb_substr($adjective, 0, -2) . 're';
            }
            // Convert masculine -ý to neuter -é (for words actually ending in ý with accent)
            if (mb_substr($adjective, -1) === 'ý') {
                return mb_substr($adjective, 0, -1) . 'é';
            }
        }

        // Return masculine form for masculine nouns or unknown cases
        return $adjective;
    }
}