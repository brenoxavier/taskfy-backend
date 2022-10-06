<?php

function getEnvironmentVariable(string $key, string $default = null)
{
    return getenv($key) ? getenv($key) : env($key, $default);
}
