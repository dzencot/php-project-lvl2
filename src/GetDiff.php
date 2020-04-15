<?php

namespace Differ\GetDiff;

use function Funct\Collection\union;

function getDiff($before, $after): array
{
    $process = function ($first, $second) use (&$process) {
        $allKeys = array_unique(union(
            array_keys(get_object_vars($first)),
            array_keys(get_object_vars($second))
        ));
        $diff = array_map(function ($key) use ($first, $second, $process) {
            if (!property_exists($first, $key)) {
                return [
                    'name' => $key,
                    'type' => 'added',
                    'newValue' => $second->$key,
                ];
            } elseif (!property_exists($second, $key)) {
                return [
                    'name' => $key,
                    'type' => 'removed',
                    'oldValue' => $first->$key,
                ];
            }

            $beforeData = $first->$key;
            $afterData = $second->$key;
            $isObjects = is_object($beforeData) && is_object($afterData);

            if ($isObjects) {
                $children = $process($beforeData, $afterData);
                return [
                    'name' => $key,
                    'type' => 'nested',
                    'children' => $children,
                ];
            } elseif ($beforeData == $afterData) {
                return [
                    'name' => $key,
                    'type' => 'unchanged',
                    'value' => $beforeData,
                ];
            }
            return [
                'name' => $key,
                'type' => 'updated',
                'newValue' => $afterData,
                'oldValue' => $beforeData,
            ];
        }, $allKeys);

        return $diff;
    };

    return $process($before, $after, 1);
}
