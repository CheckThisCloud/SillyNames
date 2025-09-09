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
}
