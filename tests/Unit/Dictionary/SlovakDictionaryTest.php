<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Tests\Unit\Dictionary;

use CheckThisCloud\SillyNames\Dictionary\SlovakDictionary;
use PHPUnit\Framework\TestCase;

final class SlovakDictionaryTest extends TestCase
{
    private SlovakDictionary $dictionary;

    protected function setUp(): void
    {
        $this->dictionary = new SlovakDictionary();
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

    public function testGetAdjectivesContainsExpectedSlovakWords(): void
    {
        $adjectives = $this->dictionary->getAdjectives();

        $this->assertContains('rýchly', $adjectives);
        $this->assertContains('múdry', $adjectives);
        $this->assertContains('veselý', $adjectives);
        $this->assertContains('priateľský', $adjectives);
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

    public function testGetSubjectsContainsExpectedSlovakWords(): void
    {
        $subjects = $this->dictionary->getSubjects();

        $this->assertContains('lev', $subjects);
        $this->assertContains('tiger', $subjects);
        $this->assertContains('medveď', $subjects);
        $this->assertContains('jednorožec', $subjects);
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