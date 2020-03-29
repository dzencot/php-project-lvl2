<?php

namespace Formatter;

use function Formatter\Pretty\pretty;
use function Formatter\Plain\plain;

function getFormatter(string $format)
{
    switch ($format) {
        case 'pretty':
            return function ($data) {
                return pretty($data);
            };
        case 'plain':
            return function ($data) {
                return plain($data);
            };
        default:
            throw new \Exception("Unknown format: {$format}");
    }
}
