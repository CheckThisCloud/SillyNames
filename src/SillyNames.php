<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames;

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
        $dictionaryPath = __DIR__ . "/dictionary/{$language}.php";

        if (!file_exists($dictionaryPath)) {
            throw new \InvalidArgumentException("Dictionary for language '{$language}' not found");
        }

        $adjectives = [];
        $subjects = [];

        include $dictionaryPath;

        if ($adjectives === [] || $subjects === []) {
            throw new \RuntimeException("Invalid dictionary format for language '{$language}': missing adjectives or subjects");
        }

        return new self($adjectives, $subjects, $seed);
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
