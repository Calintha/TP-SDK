<?php

function loadDotEnv(string $path)
{
    if (!file_exists($path)) die("${path} doesn't exist");
    $env = fopen($path, 'r');
    if (empty($env)) die("Can't open ${path}");
    
    while (!feof($env))
    {
        $line = trim(fgets($env));
        $preg_results = [];
        if (preg_match('/([^=]*)=([^#]*)/', $line, $preg_results) && !empty($preg_results[1]) && !empty($preg_results[2]))
        {
            define($preg_results[1], $preg_results[2]);
        }
    }
}