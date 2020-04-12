<?php

namespace Differ\GetDiff;

function getDiff(array $before, array $after): array
{
    $process = function ($first, $second) use (&$process) {
        $allKeys = array_unique(array_merge(array_keys($first), array_keys($second)));
        $diff = array_map(function ($key) use ($first, $second, $process) {
            if (!array_key_exists($key, $first)) {
                return [
                    'name' => $key,
                    'type' => 'added',
                    'newValue' => $second[$key],
                ];
            } elseif (!array_key_exists($key, $second)) {
                return [
                    'name' => $key,
                    'type' => 'removed',
                    'oldValue' => $first[$key],
                ];
            }

            $beforeData = $first[$key];
            $afterData = $second[$key];
            $isArrays = is_array($beforeData) && is_array($afterData);

            if ($isArrays) {
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
