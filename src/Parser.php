<?php

namespace Parser;

use Symfony\Component\Yaml\Yaml;

function getParser(string $extension): callable
{
    $parsers = [
        'json' => function (string $content) {
            return json_decode($content, true);
        },
        'yml' => function (string $content) {
            return Yaml::parse($content);
        }
    ];

    if (!array_key_exists($extension, $parsers)) {
        throw new \Exception("Unknown extension ${extension}");
    }

    return $parsers[$extension];
}
