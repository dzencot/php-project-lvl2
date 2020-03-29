<?php

namespace Differ\Formatter;

function getFormatter(string $format): callable
{
    return function ($data) use ($format) {
        switch ($format) {
            case 'pretty':
                return Pretty\render($data);
            case 'plain':
                return Plain\render($data);
            case 'json':
                return Json\render($data);
            default:
                throw new \Exception("Unknown format: {$format}");
        }
    };
}
