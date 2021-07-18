<?php

// Get the rights constants from constants file for the provider
class Google extends Provider
{
    public function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options = [])
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $options);
        $this->auth_url = URL_GOOGLE_AUTH;
        $this->access_token_url = URL_GOOGLE_ACCESS_TOKEN;
        $this->api_url = URL_GOOGLE_API;
    }
}