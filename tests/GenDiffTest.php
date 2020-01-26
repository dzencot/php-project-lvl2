<?php

use PHPUnit\Framework\TestCase;
use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $beforePlainPath;
    protected $afterPlainPath;
    protected $resultPlain;

    protected function setUp(): void
    {
        $this->resultPlain = file_get_contents('./tests/fixtures/plain-diff.txt');
        $this->beforePlainPath = './tests/fixtures/plain-before.json';
        $this->afterPlainPath = './tests/fixtures/plain-after.json';
    }

    public function testPlainData(): void
    {
        $expect = genDiff($this->beforePlainPath, $this->afterPlainPath, 'pretty');
        $this->assertEquals($expect, $this->resultPlain);
    }
}
