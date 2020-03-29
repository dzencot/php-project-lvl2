<?php

namespace Formatter\Plain;

function getPropretyName($paths)
{
    $paths = array_filter($paths, function ($name) {
        return $name;
    });
    return implode('.', $paths);
}

function iter($tree, $paths = null): string
{
    $plainLines = array_map(function ($node) use ($paths) {
        $paths[] = $node['name'];
        $propertyName = implode(".", $paths);
        switch ($node['type']) {
            case 'nested':
                return iter($node['children'], $paths);
            case 'added':
                $value = is_array($node['newValue']) ? 'complex value' : $node['newValue'];
                return "Property '{$propertyName}' was added with value: '{$value}'";
            case 'removed':
                $value = is_array($node['oldValue']) ? 'complex value' : $node['oldValue'];
                return "Property '{$propertyName}' was removed";
            case 'updated':
                $newValue = is_array($node['newValue']) ? 'complex value' : $node['newValue'];
                $oldValue = is_array($node['oldValue']) ? 'complex value' : $node['oldValue'];
                return "Property '{$propertyName}' was changed. From '{$oldValue}' to '{$newValue}'";
            default:
                return null;
        }
    }, $tree);

    $plainLines = array_filter($plainLines, function ($line) {
        return $line;
    });
    return implode("\n", $plainLines);
}

function plain($tree)
{
    $result = iter($tree, null);
    return $result;
}
