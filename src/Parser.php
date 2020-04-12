<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function getParser(string $contentType): callable
{
    return function ($content) use ($contentType) {
        switch ($contentType) {
            case 'json':
                return json_decode($content, true);
            case 'yml':
                return Yaml::parse($content);
            default:
                throw new \Exception("Unknown content type: {$contentType}");
        }
    };
}
