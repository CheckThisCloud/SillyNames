<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Tests\Unit;

use CheckThisCloud\SillyNames\SillyNames;
use PHPUnit\Framework\TestCase;

final class SillyNamesTest extends TestCase
{
    public function testGetFactoryCreatesInstance(): void
    {
        $generator = SillyNames::getFactory();

        $this->assertInstanceOf(SillyNames::class, $generator);
    }

    public function testGetFactoryWithDefaultLanguage(): void
    {
        $generator = SillyNames::getFactory();
        $name = $generator->generate();

        $this->assertIsString($name);
        $this->assertNotEmpty($name);
        $this->assertMatchesRegularExpression('/^[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ][a-záčďéěíňóřšťúůýž]+ [a-záčďéěíňóřšťúůýž]+$/', $name);
    }

    public function testGetFactoryWithCzechLanguage(): void
    {
        $generator = SillyNames::getFactory('cs');
        $name = $generator->generate();

        $this->assertIsString($name);
        $this->assertNotEmpty($name);
        $this->assertMatchesRegularExpression('/^[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ][a-záčďéěíňóřšťúůýž]+ [a-záčďéěíňóřšťúůýž]+$/', $name);
    }

    public function testGetFactoryWithEnglishLanguage(): void
    {
        $generator = SillyNames::getFactory('en');
        $name = $generator->generate();

        $this->assertIsString($name);
        $this->assertNotEmpty($name);
        $this->assertMatchesRegularExpression('/^[A-Z][a-z]+ [a-z]+$/', $name);
    }

    public function testGetFactoryWithInvalidLanguage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Dictionary for language 'invalid' not found");

        SillyNames::getFactory('invalid');
    }

    public function testGenerateReturnsString(): void
    {
        $generator = SillyNames::getFactory('en');
        $name = $generator->generate();

        $this->assertIsString($name);
        $this->assertNotEmpty($name);
    }

    public function testGenerateWithSeedProducesReproducibleResults(): void
    {
        $seed = 12345;
        $generator1 = SillyNames::getFactory('en', $seed);
        $generator2 = SillyNames::getFactory('en', $seed);

        $name1 = $generator1->generate();
        $name2 = $generator2->generate();

        $this->assertSame($name1, $name2);
    }

    public function testGenerateWithDifferentSeedsProducesDifferentResults(): void
    {
        $generator1 = SillyNames::getFactory('en', 12345);
        $generator2 = SillyNames::getFactory('en', 54321);

        $names1 = $generator1->generateMultiple(10);
        $names2 = $generator2->generateMultiple(10);

        $this->assertNotSame($names1, $names2);
    }

    public function testGenerateMultipleReturnsArray(): void
    {
        $generator = SillyNames::getFactory('en');
        $names = $generator->generateMultiple(5);

        $this->assertIsArray($names);
        $this->assertCount(5, $names);
    }

    public function testGenerateMultipleWithZeroCount(): void
    {
        $generator = SillyNames::getFactory('en');
        $names = $generator->generateMultiple(0);

        $this->assertIsArray($names);
        $this->assertEmpty($names);
    }

    public function testGenerateMultipleContainsOnlyStrings(): void
    {
        $generator = SillyNames::getFactory('en');
        $names = $generator->generateMultiple(5);

        foreach ($names as $name) {
            $this->assertIsString($name);
            $this->assertNotEmpty($name);
        }
    }

    public function testGenerateMultipleWithSeedProducesReproducibleResults(): void
    {
        $seed = 12345;
        $count = 3;

        $generator1 = SillyNames::getFactory('en', $seed);
        $generator2 = SillyNames::getFactory('en', $seed);

        $names1 = $generator1->generateMultiple($count);
        $names2 = $generator2->generateMultiple($count);

        $this->assertSame($names1, $names2);
    }

    public function testGeneratedNameFormatIsPredictable(): void
    {
        $generator = SillyNames::getFactory('en');
        $name = $generator->generate();

        // Split the name into parts
        $parts = explode(' ', $name);

        $this->assertCount(2, $parts, 'Name should consist of exactly 2 words');

        // First word (adjective) should be capitalized
        $adjective = $parts[0];
        $this->assertTrue(ctype_upper($adjective[0]), 'First letter of adjective should be uppercase');
        $this->assertTrue(ctype_lower(substr($adjective, 1)), 'Rest of adjective should be lowercase');

        // Second word (subject) should be lowercase
        $subject = $parts[1];
        $this->assertTrue(ctype_lower($subject), 'Subject should be completely lowercase');
    }

    public function testGenerateProducesVarietyOfNames(): void
    {
        $generator = SillyNames::getFactory('en');
        $names = $generator->generateMultiple(20);

        // Check that we get some variety (not all names are identical)
        $uniqueNames = array_unique($names);
        $this->assertGreaterThan(1, count($uniqueNames), 'Should generate varied names');
    }

    public function testReadonlyClassCannotBeModified(): void
    {
        $generator = SillyNames::getFactory('en');

        // This test ensures the class is properly declared as readonly
        // If the class wasn't readonly, this would be a different type of test
        $reflection = new \ReflectionClass($generator);
        $this->assertTrue($reflection->isReadOnly(), 'SillyNames class should be readonly');
    }
}
