<?php

namespace Differ;

use function Parser\getParser;
use function GetDiff\getDiff;
use function Formatter\getFormatter;

function genDiff(string $pathToFile1, string $pathToFile2, string $format): string
{
    $file1Content = file_get_contents($pathToFile1);
    $file2Content = file_get_contents($pathToFile2);
    $extension = 'json';
    $parser = getParser($extension);

    $file1Parsed = $parser($file1Content);
    $file2Parsed = $parser($file2Content);

    $diff = getDiff($file1Parsed, $file2Parsed);

    $formatter = getFormatter($format);

    $formattedDiff = $formatter($diff);
    echo ($formattedDiff);

    return '';
}
