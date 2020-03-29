<?php

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $jsonPlainBeforePath;
    protected $jsonPlainAfterPath;

    protected $jsonTreeBeforePath;
    protected $jsonTreeAfterPath;

    protected $ymlPlainBeforePath;
    protected $ymlPlainAfterPath;

    protected $ymlTreeBeforePath;
    protected $ymlTreeAfterPath;

    protected $resultPlain;

    protected function setUp(): void
    {
        $this->resultPlain = trim(file_get_contents('./tests/fixtures/plain-diff.txt'));
        $this->resultTree = trim(file_get_contents('./tests/fixtures/tree-diff.txt'));

        $this->jsonPlainBeforePath = './tests/fixtures/plain-before.json';
        $this->jsonPlainAfterPath = './tests/fixtures/plain-after.json';

        $this->jsonTreeBeforePath = './tests/fixtures/tree-before.json';
        $this->jsonTreeAfterPath = './tests/fixtures/tree-after.json';

        $this->ymlPlainBeforePath = './tests/fixtures/plain-before.yml';
        $this->ymlPlainAfterPath = './tests/fixtures/plain-after.yml';

        $this->ymlTreeBeforePath = './tests/fixtures/tree-before.yml';
        $this->ymlTreeAfterPath = './tests/fixtures/tree-after.yml';
    }

    public function testPlainJsonData(): void
    {
        $expectPlainJson = genDiff($this->jsonPlainBeforePath, $this->jsonPlainAfterPath, 'pretty');
        $this->assertEquals($expectPlainJson, $this->resultPlain);
    }

    public function testPlainYmlData(): void
    {
        $expectPlainYml = genDiff($this->ymlPlainBeforePath, $this->ymlPlainAfterPath, 'pretty');
        $this->assertEquals($expectPlainYml, $this->resultPlain);
    }

    public function testTreeJsonData(): void
    {
        $expectTreeJson = genDiff($this->jsonTreeBeforePath, $this->jsonTreeAfterPath, 'pretty');
        $this->assertEquals($expectTreeJson, $this->resultTree);
    }
}
