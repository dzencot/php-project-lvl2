<?php

namespace Formatter\Pretty;

function getPrettiedValue($value, int $depth): string
{
    if (is_array($value) || is_object($value)) {
        $openTab = str_repeat(' ', ($depth + 1) * 4);
        $closeTab = str_repeat(' ', $depth * 4);
        $valueArr = array_map(function ($key) use ($value) {
            $prettiedData = getPrettiedValue($value[$key], 0);
            return "{$key}: {$prettiedData}";
        }, array_keys($value));
        $value = implode("\n", $valueArr);
        return "{\n{$openTab}{$value}\n{$closeTab}}";
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function iter(array $tree, int $depth = 1): string
{
    $prettiedArray = array_map(function ($node) use ($depth) {
        switch ($node['type']) {
            case 'nested':
                $tab = str_repeat(' ', $depth * 4);
                $prettiedValue = iter($node['children'], $depth + 1);
                $name = $node['name'];
                return "{$tab}{$name}: {\n{$prettiedValue}\n{$tab}}";
                break;
            case 'added':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $value = $node['newValue'];
                $name = $node['name'];
                $prettiedValue = getPrettiedValue($value, $depth);
                return "{$tab}+ {$name}: {$prettiedValue}";
                break;
            case 'removed':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $value = $node['oldValue'];
                $name = $node['name'];
                $prettiedValue = getPrettiedValue($value, $depth);
                return "{$tab}- {$name}: {$prettiedValue}";
                break;
            case 'updated':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $newValue = $node['newValue'];
                $oldValue = $node['oldValue'];
                $name = $node['name'];
                $prettiedNewValue = getPrettiedValue($newValue, $depth);
                $prettiedOldValue = getPrettiedValue($oldValue, $depth);
                $prettiedData = [
                    "{$tab}+ {$name}: {$prettiedNewValue}",
                    "{$tab}- {$name}: {$prettiedOldValue}",
                ];
                return implode("\n", $prettiedData);
                break;
            case 'unchanged':
                $tab = str_repeat(' ', $depth * 4 - 2);
                $value = $node['value'];
                $name = $node['name'];
                $prettiedValue = getPrettiedValue($value, $depth);
                return "{$tab}  {$name}: {$prettiedValue}";
                break;
            default:
                throw new \Exception("Unknown type node: {$node['type']}");
        }
    }, $tree);

    $prettiedString = implode("\n", $prettiedArray);
    return $prettiedString;
}

function pretty($tree): string
{
    $prettiedString = iter($tree, 1);
    return "{\n{$prettiedString}\n}";
}
