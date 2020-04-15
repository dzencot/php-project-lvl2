<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function getParser(string $contentType): callable
{
    return function ($content) use ($contentType) {
        switch ($contentType) {
            case 'json':
                return json_decode($content);
            case 'yml':
                return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            default:
                throw new \Exception("Unknown content type: {$contentType}");
        }
    };
}
