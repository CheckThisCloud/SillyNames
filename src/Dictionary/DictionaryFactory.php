<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Dictionary;

final class DictionaryFactory
{
    public static function create(string $language): DictionaryInterface
    {
        return match ($language) {
            'cs' => new CzechDictionary(),
            'en' => new EnglishDictionary(),
            'sk' => new SlovakDictionary(),
            default => throw new \InvalidArgumentException("Dictionary for language '{$language}' not found"),
        };
    }

    /** @return array<string> */
    public static function getSupportedLanguages(): array
    {
        return ['cs', 'en', 'sk'];
    }
}
