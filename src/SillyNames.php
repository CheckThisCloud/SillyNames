<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames;

use CheckThisCloud\SillyNames\Dictionary\DictionaryFactory;

final readonly class SillyNames
{
    /** @var array<string> */
    private array $adjectives;
    /** @var array<string> */
    private array $subjects;
    private \Random\Randomizer $randomizer;

    /**
     * @param array<string> $adjectives
     * @param array<string> $subjects
     */
    private function __construct(array $adjectives, array $subjects, ?int $seed = null)
    {
        $this->adjectives = $adjectives;
        $this->subjects = $subjects;

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
            $seed
        );
    }

    public function generate(): string
    {
        $adjective = $this->randomizer->pickArrayKeys($this->adjectives, 1)[0];
        $subject = $this->randomizer->pickArrayKeys($this->subjects, 1)[0];

        return ucfirst($this->adjectives[$adjective]) . ' ' . $this->subjects[$subject];
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
