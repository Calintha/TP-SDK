<?php

// For HTTP Request
function httpRequest(string $url, $type = null)
{
    $response = file_get_contents($url, false, $type);
    return $response ? json_decode($response, true) : null;
}

// Make url
function createUrl(string $url, array $params = [])
{
    return $url . (!empty($params) ? '?' . http_build_query($params) : '');
}

// create headers
function createContext(string $method, $header)
{
    return context([
        'http' => [
            'method' => $method,
            'header' => $header
        ]
    ]);
}

function dd(...$data)
{
    echo '<pre>';
    foreach ($data as $value) {
        if (is_array($value)) {
            print_r($value);
        } else {
            echo $value . PHP_EOL;
        }
    }
    echo '</pre>';
    die;
}