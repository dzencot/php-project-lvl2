<?php

namespace Formatter\Pretty;

function pretty($data)
{
    $formatNode = function ($node) {
        switch ($node['status']) {
            case 'added':
                return [
                    "+ ${node['name']}" => $node['data'],
                ];
            case 'removed':
                return [
                    "- ${node['name']}" => $node['data'],
                ];
            case 'updated':
                return [
                    "+ ${node['name']}" => $node['data'],
                    "- ${node['name']}" => $node['previous'],
                ];
            default:
                return [
                    $node['name'] => $node['data'],
                ];
        }
    };

    $getOutput = function ($carry, $node) use (&$getOutput, $formatNode) {
        if ($node['status'] === 'array') {
            $carry[$node['name']] = array_reduce($node['data'], $getOutput, []);
        } else {
            $carry = array_merge($carry, $formatNode($node));
        }
        return $carry;
    };

    $prettyArray = array_reduce($data, $getOutput, []);
    $result = json_encode($prettyArray, JSON_PRETTY_PRINT);
    return $result;
}
