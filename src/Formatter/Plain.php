<?php

namespace Differ\Formatter\Plain;

function getPlainValue($value): string
{
    $plainValue = is_object($value) ? 'complex value' : $value;
    return $plainValue;
}
function iter($tree, $property = ''): string
{
    $plainLines = array_map(function ($node) use ($property) {
        $propertyName = $property ? "{$property}.{$node['name']}" : $node['name'];
        switch ($node['type']) {
            case 'nested':
                return iter($node['children'], $propertyName);
            case 'added':
                $value = getPlainValue($node['newValue']);
                return "Property '{$propertyName}' was added with value: '{$value}'";
            case 'removed':
                $value = getPlainValue($node['oldValue']);
                return "Property '{$propertyName}' was removed";
            case 'updated':
                $newValue = getPlainValue($node['newValue']);
                $oldValue = getPlainValue($node['oldValue']);
                return "Property '{$propertyName}' was changed. From '{$oldValue}' to '{$newValue}'";
            case 'unchanged':
                return null;
            default:
                throw new \Exception("Unknown type: {$node['type']}");
        }
    }, $tree);

    $plainLines = array_filter($plainLines, fn($line) => $line);
    return implode("\n", $plainLines);
}

function render($tree): string
{
    $result = iter($tree, null);
    return $result;
}
