<?php

abstract class Provider {

    protected string $client_id;

    protected string $client_secret;

    protected string $token_url;

    protected string $redirect_uri;

    protected string $api_url;

    protected array $options;

    protected string $app_name;

    protected function __construct(string $client_id, string $client_secret, string $redirect_uri, array $options, string $app_name = "")
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->options = $options;
        $this->app_name = $app_name;
    }

    // Return token with the code and clean url with str
    protected function getToken(string $code, bool $is_post = false)
    {
        $context = $is_post ? createContext('POST', ['Content-Type: application/x-www-form-urlencoded', 'Content-Length: 0', 'Accept: application/json']) : null;
        $url = createUrl($this->token_url, [
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        ]);

        $url = str_replace('%27', '', $url);
        $url = str_replace('%2F', '/', $url);
        $url = str_replace('%3A', ':', $url);
        $url = str_replace('%3F', '?', $url);
        $url = str_replace('%3D', '=', $url);
        $url = str_replace('%3B','', $url);
        
        return httpRequest($url, $context)['access_token'];
    }

    // Create array with redirect url and the right options
    public function getAuthorizeUrl()
    {
        return createUrl($this->auth_url, array_merge([
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
        ], 
        $this->options));
    }

    // Get user data with the identification token 
    public function getUser(string $params) {
        $token = $this->getToken($params);
        return $token ? httpRequest($this->api_url, createContext('GET', "Authorization: Bearer ${token}")) : false;
    }

    public static function logout()
    {
        header('location: /');
    }
}