<?php

namespace Parser;

function getParser(string $extension): callable
{
    $parsers = [
        'json' => function (string $content) {
            return json_decode($content, true);
        },
    ];

    return $parsers[$extension];
}
