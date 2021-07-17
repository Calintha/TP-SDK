<?php

function httpRequest(string $url, $type = null)
{
    $response = file_get_contents($url, false, $type);
    return $response ? json_decode($response, true) : null;
}