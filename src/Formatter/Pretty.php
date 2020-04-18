<?php

namespace Differ\Formatter\Pretty;

function getPrettyValue($value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (!is_object($value) && !is_array($value)) {
        return $value;
    }

    $openTab = str_repeat(' ', ($depth + 1) * 4);
    $closeTab = str_repeat(' ', $depth * 4);
    $valueArr = array_map(function ($key) use ($value) {
        $prettiedData = getPrettyValue($value->$key, 0);
        return "{$key}: {$prettiedData}";
    }, array_keys(get_object_vars($value)));
    $value = implode("\n", $valueArr);
    return "{\n{$openTab}{$value}\n{$closeTab}}";
}

function iter(array $tree, int $depth = 1): string
{
    $prettyLines = array_map(function ($node) use ($depth) {
        switch ($node['type']) {
            case 'nested':
                $tab = str_repeat(' ', $depth * 4);
                $prettyValue = iter($node['children'], $depth + 1);
                return "{$tab}{$node['name']}: {\n{$prettyValue}\n{$tab}}";
            case 'added':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettyValue = getPrettyValue($node['newValue'], $depth);
                return "{$tab}+ {$node['name']}: {$prettyValue}";
            case 'removed':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettyValue = getPrettyValue($node['oldValue'], $depth);
                return "{$tab}- {$node['name']}: {$prettyValue}";
            case 'updated':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettiedNewValue = getPrettyValue($node['newValue'], $depth);
                $prettiedOldValue = getPrettyValue($node['oldValue'], $depth);
                $prettiedData = [
                    "{$tab}+ {$node['name']}: {$prettiedNewValue}",
                    "{$tab}- {$node['name']}: {$prettiedOldValue}",
                ];
                return implode("\n", $prettiedData);
            case 'unchanged':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettyValue = getPrettyValue($node['value'], $depth);
                return "{$tab}  {$node['name']}: {$prettyValue}";
            default:
                throw new \Exception("Unknown type node: {$node['type']}");
        }
    }, $tree);

    return implode("\n", $prettyLines);
}

function render($tree): string
{
    $result = iter($tree, 1);
    return "{\n{$result}\n}";
}
