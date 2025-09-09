<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Tests\Unit\Dictionary;

use CheckThisCloud\SillyNames\Dictionary\EnglishDictionary;
use PHPUnit\Framework\TestCase;

final class EnglishDictionaryTest extends TestCase
{
    private EnglishDictionary $dictionary;

    protected function setUp(): void
    {
        $this->dictionary = new EnglishDictionary();
    }

    public function testGetAdjectivesReturnsArray(): void
    {
        $adjectives = $this->dictionary->getAdjectives();

        $this->assertIsArray($adjectives);
        $this->assertNotEmpty($adjectives);
    }

    public function testGetAdjectivesContainsOnlyStrings(): void
    {
        $adjectives = $this->dictionary->getAdjectives();

        foreach ($adjectives as $adjective) {
            $this->assertIsString($adjective);
            $this->assertNotEmpty($adjective);
        }
    }

    public function testGetAdjectivesContainsExpectedEnglishWords(): void
    {
        $adjectives = $this->dictionary->getAdjectives();

        $this->assertContains('swift', $adjectives);
        $this->assertContains('clever', $adjectives);
        $this->assertContains('happy', $adjectives);
        $this->assertContains('brave', $adjectives);
    }

    public function testGetSubjectsReturnsArray(): void
    {
        $subjects = $this->dictionary->getSubjects();

        $this->assertIsArray($subjects);
        $this->assertNotEmpty($subjects);
    }

    public function testGetSubjectsContainsOnlyStrings(): void
    {
        $subjects = $this->dictionary->getSubjects();

        foreach ($subjects as $subject) {
            $this->assertIsString($subject);
            $this->assertNotEmpty($subject);
        }
    }

    public function testGetSubjectsContainsExpectedEnglishWords(): void
    {
        $subjects = $this->dictionary->getSubjects();

        $this->assertContains('lion', $subjects);
        $this->assertContains('tiger', $subjects);
        $this->assertContains('bear', $subjects);
        $this->assertContains('unicorn', $subjects);
    }

    public function testAdjectivesAndSubjectsHaveReasonableCount(): void
    {
        $adjectives = $this->dictionary->getAdjectives();
        $subjects = $this->dictionary->getSubjects();

        $this->assertGreaterThan(5, count($adjectives), 'Should have at least 6 adjectives');
        $this->assertGreaterThan(5, count($subjects), 'Should have at least 6 subjects');
    }

    public function testMultipleCallsReturnSameData(): void
    {
        $adjectives1 = $this->dictionary->getAdjectives();
        $adjectives2 = $this->dictionary->getAdjectives();
        $subjects1 = $this->dictionary->getSubjects();
        $subjects2 = $this->dictionary->getSubjects();

        $this->assertSame($adjectives1, $adjectives2);
        $this->assertSame($subjects1, $subjects2);
    }
}
