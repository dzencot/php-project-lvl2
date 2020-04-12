<?php

namespace Differ\tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $jsonTreeBeforePath;
    protected $jsonTreeAfterPath;

    protected $ymlTreeBeforePath;
    protected $ymlTreeAfterPath;

    protected $expectedPlainPath;
    protected $expectedPrettyPath;
    protected $expectedJsonPath;

    protected function setUp(): void
    {
        $this->expectedPlainPath = './tests/fixtures/plain-diff.txt';
        $this->expectedPrettyPath = './tests/fixtures/pretty-diff.txt';
        $this->expectedJsonPath = './tests/fixtures/json-diff.txt';

        $this->jsonTreeBeforePath = './tests/fixtures/tree-before.json';
        $this->jsonTreeAfterPath = './tests/fixtures/tree-after.json';

        $this->ymlTreeBeforePath = './tests/fixtures/tree-before.yml';
        $this->ymlTreeAfterPath = './tests/fixtures/tree-after.yml';
    }

    public function testPretty(): void
    {
        $expected = trim(file_get_contents($this->expectedPrettyPath));

        $resultJson = genDiff($this->jsonTreeBeforePath, $this->jsonTreeAfterPath, 'pretty');
        $this->assertEquals($expected, $resultJson);

        $resultYml = genDiff($this->ymlTreeBeforePath, $this->ymlTreeAfterPath, 'pretty');
        $this->assertEquals($expected, $resultYml);
    }

    public function testPlain(): void
    {
        $expected = trim(file_get_contents($this->expectedPlainPath));

        $resultJson = genDiff($this->jsonTreeBeforePath, $this->jsonTreeAfterPath, 'plain');
        $this->assertEquals($expected, $resultJson);

        $resultYml = genDiff($this->ymlTreeBeforePath, $this->ymlTreeAfterPath, 'plain');
        $this->assertEquals($expected, $resultYml);
    }

    public function testJson(): void
    {
        $expected = trim(file_get_contents($this->expectedJsonPath));

        $resultJson = genDiff($this->jsonTreeBeforePath, $this->jsonTreeAfterPath, 'json');
        $this->assertEquals($expected, $resultJson);

        $resultYml = genDiff($this->ymlTreeBeforePath, $this->ymlTreeAfterPath, 'json');
        $this->assertEquals($expected, $resultYml);
    }
}
