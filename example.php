<?php

require_once __DIR__ . '/vendor/autoload.php';

use CheckThisCloud\SillyNames\SillyNames;

echo "=== Silly Names Generator Demo ===\n\n";

// Generate random names in Czech
echo "Random Czech names:\n";
$generator = SillyNames::getFactory('cs');
for ($i = 0; $i < 5; $i++) {
    echo "- " . $generator->generate() . "\n";
}

echo "\nRandom English names:\n";
$generator = SillyNames::getFactory('en');
for ($i = 0; $i < 5; $i++) {
    echo "- " . $generator->generate() . "\n";
}

echo "\nRandom Slovak names:\n";
$generator = SillyNames::getFactory('sk');
for ($i = 0; $i < 5; $i++) {
    echo "- " . $generator->generate() . "\n";
}

echo "\n=== Seed Reproducibility Demo ===\n";
echo "Using seed 12345 for reproducible results:\n";

// First run with seed
$seededGenerator = SillyNames::getFactory('en', 12345);
echo "First run:\n";
$firstRun = $seededGenerator->generateMultiple(3);
foreach ($firstRun as $name) {
    echo "- $name\n";
}

// Second run with same seed - should produce identical results
$seededGenerator2 = SillyNames::getFactory('en', 12345);
echo "\nSecond run (same seed):\n";
$secondRun = $seededGenerator2->generateMultiple(3);
foreach ($secondRun as $name) {
    echo "- $name\n";
}

echo "\nResults match: " . (($firstRun === $secondRun) ? "YES" : "NO") . "\n";

echo "\n=== Different Seed Demo ===\n";
$differentSeedGenerator = SillyNames::getFactory('en', 54321);
echo "Using different seed (54321):\n";
$differentRun = $differentSeedGenerator->generateMultiple(3);
foreach ($differentRun as $name) {
    echo "- $name\n";
}
