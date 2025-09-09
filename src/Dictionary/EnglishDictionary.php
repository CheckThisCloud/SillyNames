<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Dictionary;

final readonly class EnglishDictionary implements DictionaryInterface
{
    /** @return array<string> */
    public function getAdjectives(): array
    {
        return [
            'swift',
            'clever',
            'happy',
            'mighty',
            'brave',
            'wise',
            'quiet',
            'bright',
            'cheerful',
            'friendly',
            'creative',
            'energetic',
            'funny',
            'kind',
            'loyal',
            'honest',
            'patient',
            'calm',
            'playful',
            'graceful',
            'bold',
            'curious',
            'gentle',
            'mysterious',
        ];
    }

    /** @return array<string> */
    public function getSubjects(): array
    {
        return [
            'lion',
            'tiger',
            'bear',
            'wolf',
            'owl',
            'dolphin',
            'elephant',
            'koala',
            'unicorn',
            'dragon',
            'phoenix',
            'rabbit',
            'fox',
            'lizard',
            'turtle',
            'horse',
            'panda',
            'cat',
            'dog',
        ];
    }
}
