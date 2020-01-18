<?php

namespace GetDiff;

function getDiff(array $data1, array $data2): array
{
    $getComparedData = function ($beforeData, $afterData): array {
        if ($beforeData === $afterData) {
            return [
                'status' => 'unchanged',
                'data' => $beforeData,
            ];
        } elseif (!$beforeData) {
            return [
                'status' => 'added',
                'data' => $afterData,
            ];
        } elseif (!$afterData) {
            return [
                'status' => 'removed',
                'data' => $beforeData,
            ];
        } elseif (is_array($beforeData)) {
            return [
                'status' => 'array',
                'data' => getDiff($beforeData, $afterData),
            ];
        } else {
            return [
                'status' => 'updated',
                'data' => $afterData,
                'previous' => $beforeData,
            ];
        }
    };

    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $diff = array_map(function ($key) use ($getComparedData, $data1, $data2) {
        $beforeValue = $data1[$key] ?? null;
        $afterValue = $data2[$key] ?? null;
        $comparedData = $getComparedData($beforeValue, $afterValue);
        return array_merge(['name' => $key], $comparedData);
    }, $allKeys);
    return $diff;
}
