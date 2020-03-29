<?php

namespace Formatter\Json;

function json($tree): string
{
    return json_encode($tree, JSON_NUMERIC_CHECK);
}
