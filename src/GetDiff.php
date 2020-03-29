<?php

namespace Differ\GetDiff;

function getDiff(array $before, array $after): array
{
    $helper = function ($data1, $data2) use (&$helper) {
        $getComparedData = function ($name, $beforeData, $afterData) use ($helper): array {
            $isArrays = is_array($beforeData) && is_array($afterData);
            if ($isArrays) {
                $children = $helper($beforeData, $afterData);
                return [
                    'name' => $name,
                    'type' => 'nested',
                    'children' => $children,
                ];
            }

            if ($beforeData === $afterData) {
                return [
                    'name' => $name,
                    'type' => 'unchanged',
                    'value' => $beforeData,
                ];
            } elseif (!$beforeData) {
                return [
                    'name' => $name,
                    'type' => 'added',
                    'newValue' => $afterData,
                ];
            } elseif (!$afterData) {
                return [
                    'name' => $name,
                    'type' => 'removed',
                    'oldValue' => $beforeData,
                ];
            } elseif ($beforeData !== $afterData) {
                return [
                    'name' => $name,
                    'type' => 'updated',
                    'newValue' => $afterData,
                    'oldValue' => $beforeData,
                ];
            }
        };

        $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
        $diff = array_map(function ($key) use ($getComparedData, $data1, $data2) {
            $beforeValue = $data1[$key] ?? null;
            $afterValue = $data2[$key] ?? null;
            $comparedData = $getComparedData($key, $beforeValue, $afterValue);
            return $comparedData;
        }, $allKeys);
        return $diff;
    };

    return $helper($before, $after, 1);
}
