<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames;

use CheckThisCloud\SillyNames\Dictionary\DictionaryFactory;
use CheckThisCloud\SillyNames\Dictionary\CzechDictionary;
use CheckThisCloud\SillyNames\Dictionary\SlovakDictionary;

final readonly class SillyNames
{
    /** @var array<string> */
    private array $adjectives;
    /** @var array<string> */
    private array $subjects;
    private \Random\Randomizer $randomizer;
    private string $language;

    /**
     * @param array<string> $adjectives
     * @param array<string> $subjects
     */
    private function __construct(array $adjectives, array $subjects, string $language, ?int $seed = null)
    {
        $this->adjectives = $adjectives;
        $this->subjects = $subjects;
        $this->language = $language;

        if ($seed !== null) {
            $engine = new \Random\Engine\Mt19937($seed);
            $this->randomizer = new \Random\Randomizer($engine);
        } else {
            $this->randomizer = new \Random\Randomizer();
        }
    }

    public static function getFactory(string $language = 'cs', ?int $seed = null): self
    {
        $dictionary = DictionaryFactory::create($language);

        return new self(
            $dictionary->getAdjectives(),
            $dictionary->getSubjects(),
            $language,
            $seed
        );
    }

    /** @return array<string> */
    public static function getSupportedLanguages(): array
    {
        return DictionaryFactory::getSupportedLanguages();
    }

    public function generate(): string
    {
        $adjective = $this->randomizer->pickArrayKeys($this->adjectives, 1)[0];
        $subject = $this->randomizer->pickArrayKeys($this->subjects, 1)[0];

        $adjectiveText = $this->adjectives[$adjective];
        $subjectText = $this->subjects[$subject];
        
        // Apply Czech gender agreement if needed
        if ($this->language === 'cs') {
            $czechDictionary = DictionaryFactory::create('cs');
            if ($czechDictionary instanceof CzechDictionary) {
                $gender = $czechDictionary->getNounGender($subjectText);
                $adjectiveText = $czechDictionary->inflectAdjective($adjectiveText, $gender);
            }
        }
        
        // Apply Slovak gender agreement if needed
        if ($this->language === 'sk') {
            $slovakDictionary = DictionaryFactory::create('sk');
            if ($slovakDictionary instanceof SlovakDictionary) {
                $gender = $slovakDictionary->getNounGender($subjectText);
                $adjectiveText = $slovakDictionary->inflectAdjective($adjectiveText, $gender);
            }
        }
        
        // Use mb_strtoupper for proper Unicode support
        $capitalizedAdjective = mb_strtoupper(mb_substr($adjectiveText, 0, 1)) . mb_substr($adjectiveText, 1);

        return $capitalizedAdjective . ' ' . $subjectText;
    }

    /** @return array<string> */
    public function generateMultiple(int $count): array
    {
        $names = [];
        for ($i = 0; $i < $count; $i++) {
            $names[] = $this->generate();
        }
        return $names;
    }
}
