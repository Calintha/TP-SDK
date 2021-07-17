<?php

abstract class Provider {
    protected string $client_id;
    protected string $client_secret;
    protected string $token_url;
    protected string $redirect_uri;
    protected string $api_url;
    protected string $api_name;
    protected array $options;

    protected function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options, string $api_name = "")
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->api_name = $api_name;
        $this->options = $options;
    }

    // With the params it send the token
    protected function getToken(string $params, bool $is_post = false)
    {
        $context = $is_post ? createStreamContext('POST', ['Content-Type: application/x-www-form-urlencoded', 'Content-Length: 0', 'Accept: application/json']) : null;
        $url = createUrl($this->token_url, [
            'params' => $params,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        ]);

        return httpRequest($url, $context)['token'];
    }

    // TODO function generate
    public function getAuthorizationUrl()
    {
        return createUrl($this->auth_url, array_merge([
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
        ], $this->options));
    }

    // Get user data with the provider
    public function getUser(string $params) {
        $token = $this->getToken($params);
        return $token ? httpRequest($this->api_url, createStreamContext('GET', "Authorization: Bearer ${token}")) : false;
    }

    public function getAuthorizeUrl()
    {
        return createUrl($this->auth_url, array_merge([
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
        ], 
        $this->options));
    }
}