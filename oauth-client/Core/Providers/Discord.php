<?php

// Get the rights constants from constants file for the provider
class Discord extends Provider
{
    public function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options = [], string $app_name = "")
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $options, $app_name);
        $this->auth_url = URL_DISCORD_AUTH;
        $this->token_url = URL_DISCORD_ACCESS_TOKEN;
        $this->api_url = URL_DISCORD_API;
    }

    public function getUser(string $params)
    {
        $token = $this->getToken($params, true);
        return $token ? httpRequest($this->api_url, createContext('GET', ["Authorization: Bearer ${token}", "User-Agent: $this->app_name"])) : false;
    }
}