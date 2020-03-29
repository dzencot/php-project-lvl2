<?php

namespace Differ;

use function Parser\getParser;
use function GetDiff\getDiff;
use function Formatter\getFormatter;

function genDiff(string $pathToFile1, string $pathToFile2, string $format): string
{
    $file1Content = file_get_contents($pathToFile1);
    $file2Content = file_get_contents($pathToFile2);

    $file1PathInfo = pathinfo($pathToFile1);
    $file2PathInfo = pathinfo($pathToFile2);

    $file1Parser = getParser($file1PathInfo['extension']);
    $file2Parser = getParser($file2PathInfo['extension']);

    $file1Parsed = $file1Parser($file1Content);
    $file2Parsed = $file2Parser($file2Content);

    if (!$file1Content || !$file2Content) {
        throw new \Exception("Content is empty");
    }

    $diff = getDiff($file1Parsed, $file2Parsed);

    $formatter = getFormatter($format);

    $formattedDiff = $formatter($diff);

    return $formattedDiff;
}
