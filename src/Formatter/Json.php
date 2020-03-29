<?php

namespace Differ\Formatter\Json;

function render($tree): string
{
    return json_encode($tree, JSON_NUMERIC_CHECK);
}
