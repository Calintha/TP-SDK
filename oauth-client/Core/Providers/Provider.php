<?php

abstract class Provider {
    protected string $client_id;
    protected string $client_secret;
    protected string $token_url;
    protected string $redirect_uri;
    protected string $api_url;

    protected function __construct(string $client_id, string $client_secret, string $redirect_uri)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
    }

    // With the params it send the token
    protected function getToken(string $params, bool $is_post = false)
    {
        $type = $is_post ? createStreamContext('POST', ['Content-Type: application/x-www-form-urlencoded', 'Content-Length: 0', 'Accept: application/json']) : null;
        $url = makeUrl($this->token_url, [
            'params' => $params,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        ]);

        return httpRequest($url, $type)['token'];
    }

    // TODO function generate
    public function getAuthorizationUrl()
    {

    }

    // Get user data with the provider
    function getUser(string $params) {
        $token = $this->getToken($params);
        $context = stream_context_create([
            'http' => [
                'method' => "GET",
                'header' => "Authorization: Bearer " . $token
            ]
        ]);
        return $token ? httpRequest($this->api_url), $context : false;
    }
}