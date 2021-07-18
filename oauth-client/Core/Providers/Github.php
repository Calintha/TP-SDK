<?php

// Get the rights constants from constants file for the provider
class Github extends Provider
{
    public function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options = [], string $app_name = "")
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $options, $app_name);
        $this->token_url = URL_GITHUB_ACCESS_TOKEN;
        $this->auth_url = URL_GITHUB_AUTH;
        $this->api_url = URL_GITHUB_API;
    }
}    