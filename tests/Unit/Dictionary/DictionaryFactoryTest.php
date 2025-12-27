<?php

declare(strict_types=1);

namespace CheckThisCloud\SillyNames\Tests\Unit\Dictionary;

use CheckThisCloud\SillyNames\Dictionary\DictionaryFactory;
use CheckThisCloud\SillyNames\Dictionary\CzechDictionary;
use CheckThisCloud\SillyNames\Dictionary\EnglishDictionary;
use PHPUnit\Framework\TestCase;

final class DictionaryFactoryTest extends TestCase
{
    public function testCreateWithCzechLanguage(): void
    {
        $dictionary = DictionaryFactory::create('cs');

        $this->assertInstanceOf(CzechDictionary::class, $dictionary);
    }

    public function testCreateWithEnglishLanguage(): void
    {
        $dictionary = DictionaryFactory::create('en');

        $this->assertInstanceOf(EnglishDictionary::class, $dictionary);
    }

    public function testCreateWithInvalidLanguageThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Dictionary for language 'invalid' not found");

        DictionaryFactory::create('invalid');
    }

    public function testCreateWithEmptyLanguageThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Dictionary for language '' not found");

        DictionaryFactory::create('');
    }

    public function testGetSupportedLanguagesReturnsArray(): void
    {
        $languages = DictionaryFactory::getSupportedLanguages();

        $this->assertNotEmpty($languages);
    }

    public function testGetSupportedLanguagesContainsCzech(): void
    {
        $languages = DictionaryFactory::getSupportedLanguages();

        $this->assertContains('cs', $languages);
    }

    public function testGetSupportedLanguagesContainsEnglish(): void
    {
        $languages = DictionaryFactory::getSupportedLanguages();

        $this->assertContains('en', $languages);
    }

    public function testGetSupportedLanguagesContainsOnlyStrings(): void
    {
        $languages = DictionaryFactory::getSupportedLanguages();

        foreach ($languages as $language) {
            $this->assertNotEmpty($language);
        }
    }

    public function testAllSupportedLanguagesCanBeCreated(): void
    {
        $languages = DictionaryFactory::getSupportedLanguages();

        self::assertNotEmpty($languages);
    }
}
