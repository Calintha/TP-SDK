<?php

// Get the rights constants from constants file for the provider
class Facebook extends Provider {
    
    public function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options = [])
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $options);
        $this->token_url = URL_FB_ACCESS_TOKEN;
        $this->auth_url = URL_FB_AUTH;
        $this->api_url = URL_FB_API;
    }
}