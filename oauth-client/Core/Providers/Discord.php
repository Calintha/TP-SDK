<?php

class Discord extends Provider
{
    public function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options = [], string $app_name = "")
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $options, $app_name);
        $this->access_token_url = URL_DISCORD_AUTH;
        $this->auth_url = URL_DISCORD_ACCESS_TOKEN;
        $this->api_url = URL_DISCORD_API;
    }
}