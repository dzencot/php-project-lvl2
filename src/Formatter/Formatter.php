<?php

namespace Formatter;

use function Formatter\Pretty\pretty;

function getFormatter(string $format)
{
    switch ($format) {
        case 'pretty':
            return function ($data) {
                return pretty($data);
            };
    }
}
