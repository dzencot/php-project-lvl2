<?php

namespace Formatter\Pretty;

function prettyNode($node, $level)
{
    $status = $node['status'];
    $name = $node['name'];
    $data = $node['data'];
    $tab = str_repeat('  ', $level);

    $getPrettiedData = function ($currentData, $currentLevel) use (&$getPrettiedData): string {
        if (is_array($currentData)) {
            $tab = str_repeat('  ', $currentLevel);
            $valueArr = array_map(function ($item) use ($getPrettiedData, $currentLevel) {
                $newTab = str_repeat('  ', $currentLevel + 1);
                return "\n${newTab}  " . $getPrettiedData($item, $currentLevel + 1);
            }, $currentData);
            $value = implode("${tab}\n", $valueArr);
            return "{" . "${value}" . "\n${tab}}";
        }
        if (is_bool($currentData)) {
            return $currentData ? 'true' : 'false';
        }
        return $currentData;
    };

    switch ($status) {
        case 'added':
            $value = $getPrettiedData($data, $level);
            return "${tab}+ ${name}: ${value}";
        case 'removed':
            $value = $getPrettiedData($data, $level);
            return "${tab}- ${name}: ${value}";
        case 'updated':
            $value = $getPrettiedData($data, $level);
            $beforeData = $node['previous'];
            $valueBefore = $getPrettiedData($beforeData, $level);
            return "${tab}+ ${name}: ${value}\n${tab}- ${name}: ${valueBefore}";
        case 'unchanged':
            $value = $getPrettiedData($data, $level);
            return "${tab}  ${name}: ${value}";
        /* case 'array': */
        /*     $valueArr = array_map(function ($item) use ($level) { */
        /*         return "\n" . prettyNode($item, $level + 1); */
        /*     }, $data); */
        /*     $value = implode("${tab}\n", array_values($valueArr)); */
        /*     return "${tab}  ${name}: {" . "${value}" . "\n${tab}}"; */
        default:
            $valueArr = array_map(function ($item) use ($level) {
                return "\n" . prettyNode($item, $level + 1);
            }, $data);
            $value = implode("${tab}\n", $valueArr);
            return "${tab}  ${name}: {" . "${value}" . "\n${tab}}";
    }
}

function pretty($data)
{
    $separator = "\n";
    $prettyArray = array_map(function ($item) {
        return prettyNode($item, 1);
    }, $data);
    $prettyString = implode($separator, $prettyArray);
    return '{' . $separator . $prettyString . $separator . "}\n";
}
