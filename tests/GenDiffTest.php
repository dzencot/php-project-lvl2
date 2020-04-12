<?php

namespace Differ\tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    private $extensions = ['json', 'yml'];
    private $formats = ['pretty', 'plain', 'json'];

    private function getFixturePath(string $fixtureName): string
    {
        $fixturesDir = __DIR__ . "/fixtures/";
        $fixturePath = $fixturesDir . $fixtureName;
        return $fixturePath;
    }

    public function providerFixtures(): array
    {
        $fixtures = [];
        foreach ($this->formats as $format) {
            foreach ($this->extensions as $extension) {
                $name = "Format: {$format}. Extension: {$extension}.";
                $beforeFixturePath = $this->getFixturePath("tree-before.{$extension}");
                $afterFixturePath = $this->getFixturePath("tree-after.{$extension}");
                $expectedFixturePath = $this->getFixturePath("{$format}-diff.txt");
                $fixtures[$name] = [
                    $format,
                    $beforeFixturePath,
                    $afterFixturePath,
                    $expectedFixturePath,
                ];
            }
        }
        return $fixtures;
    }

    /**
     * @dataProvider providerFixtures
     */
    public function testDiff(
        string $format,
        string $beforeFixturePath,
        string $afterFixturePath,
        string $expectedFixturePath
    ): void {
        $expected = trim(file_get_contents($expectedFixturePath));

        $result = genDiff($beforeFixturePath, $afterFixturePath, $format);
        $this->assertEquals($expected, $result);
    }
}
