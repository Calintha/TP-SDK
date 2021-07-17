<?php

// For HTTP Request
function httpRequest(string $url, $type = null)
{
    $response = file_get_contents($url, false, $type);
    return $response ? json_decode($response, true) : null;
}

// Make url
function getUrl(string $url, array $params = [])
{
    return $url . (!empty($params) ? '?' . http_build_query($params) : '');
}

// create headers
function createStreamContext(string $method, $header)
{
    return stream_context_create([
        'http' => [
            'method' => $method,
            'header' => $header
        ]
    ]);
}