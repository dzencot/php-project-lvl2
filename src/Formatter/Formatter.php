<?php

namespace Formatter;

use function Formatter\Pretty\pretty;
use function Formatter\Plain\plain;
use function Formatter\Json\json;

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
        case 'json':
            return function ($data) {
                return json($data);
            };
        default:
            throw new \Exception("Unknown format: {$format}");
    }
}
