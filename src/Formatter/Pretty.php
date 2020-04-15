<?php

namespace Differ\Formatter\Pretty;

function getPrettiedValue($value, int $depth): string
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
        $prettiedData = getPrettiedValue($value->$key, 0);
        return "{$key}: {$prettiedData}";
    }, array_keys(get_object_vars($value)));
    $value = implode("\n", $valueArr);
    return "{\n{$openTab}{$value}\n{$closeTab}}";
}

function iter(array $tree, int $depth = 1): string
{
    $prettiedArray = array_map(function ($node) use ($depth) {
        switch ($node['type']) {
            case 'nested':
                $tab = str_repeat(' ', $depth * 4);
                $prettiedValue = iter($node['children'], $depth + 1);
                return "{$tab}{$node['name']}: {\n{$prettiedValue}\n{$tab}}";
            case 'added':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettiedValue = getPrettiedValue($node['newValue'], $depth);
                return "{$tab}+ {$node['name']}: {$prettiedValue}";
            case 'removed':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettiedValue = getPrettiedValue($node['oldValue'], $depth);
                return "{$tab}- {$node['name']}: {$prettiedValue}";
            case 'updated':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettiedNewValue = getPrettiedValue($node['newValue'], $depth);
                $prettiedOldValue = getPrettiedValue($node['oldValue'], $depth);
                $prettiedData = [
                    "{$tab}+ {$node['name']}: {$prettiedNewValue}",
                    "{$tab}- {$node['name']}: {$prettiedOldValue}",
                ];
                return implode("\n", $prettiedData);
            case 'unchanged':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $prettiedValue = getPrettiedValue($node['value'], $depth);
                return "{$tab}  {$node['name']}: {$prettiedValue}";
            default:
                throw new \Exception("Unknown type node: {$node['type']}");
        }
    }, $tree);

    $prettiedString = implode("\n", $prettiedArray);
    return $prettiedString;
}

function render($tree): string
{
    $prettiedString = iter($tree, 1);
    return "{\n{$prettiedString}\n}";
}
