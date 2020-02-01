<?php

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $jsonPlainBeforePath;
    protected $jsonPlainAfterPath;

    protected $ymlPlainBeforePath;
    protected $ymlPlainAfterPath;

    protected $resultPlain;

    protected function setUp(): void
    {
        $this->resultPlain = file_get_contents('./tests/fixtures/plain-diff.txt');

        $this->jsonPlainBeforePath = './tests/fixtures/plain-before.json';
        $this->jsonPlainAfterPath = './tests/fixtures/plain-after.json';

        $this->ymlPlainBeforePath = './tests/fixtures/plain-before.yml';
        $this->ymlPlainAfterPath = './tests/fixtures/plain-after.yml';
    }

    public function testPlainData(): void
    {
        $expectPlainJson = genDiff($this->jsonPlainBeforePath, $this->jsonPlainAfterPath, 'pretty');
        $this->assertEquals($expectPlainJson, $this->resultPlain);

        $expectPlainYml = genDiff($this->ymlPlainBeforePath, $this->ymlPlainAfterPath, 'pretty');
        $this->assertEquals($expectPlainYml, $this->resultPlain);
    }
}
