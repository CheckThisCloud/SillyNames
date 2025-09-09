<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Dictionary;

interface DictionaryInterface
{
    /** @return array<string> */
    public function getAdjectives(): array;

    /** @return array<string> */
    public function getSubjects(): array;
}
