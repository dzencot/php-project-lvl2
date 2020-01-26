<?php

namespace GetDiff;

function getDiff(array $before, array $after): array
{
    $helper = function ($data1, $data2) use (&$helper) {
        $getComparedData = function ($beforeData, $afterData) use ($helper): array {
            if ($beforeData === $afterData) {
                return [
                    'status' => 'unchanged',
                    'data' => $beforeData,
                    'previous' => null,
                ];
            } elseif (!$beforeData) {
                return [
                    'status' => 'added',
                    'data' => $afterData,
                    'previous' => null,
                ];
            } elseif (!$afterData) {
                return [
                    'status' => 'removed',
                    'data' => $beforeData,
                    'previous' => null,
                ];
            } elseif (is_array($beforeData)) {
                return [
                    'status' => 'array',
                    'data' => $helper($beforeData, $afterData),
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
    };

    return $helper($before, $after, 1);
}
