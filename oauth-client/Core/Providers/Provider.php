<?php

abstract class Provider {
    protected string $client_id;
    protected string $client_secret;

    protected function __construct(string $client_id, string $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }
    
    function getUser(string $params) {
        $token = $this->getToken($params);
        return $token;
    }
}