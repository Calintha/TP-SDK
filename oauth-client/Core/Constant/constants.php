<?php

// For the environment
const ENV_PATH = '.env';
// Api constants
const URL_APP_AUTH = 'http://localhost:8081/auth';
const URL_APP_ACCESS_TOKEN = 'http://oauth-server:8081/token';
const URL_APP_API = 'http://oauth-server:8081/me';
// Facebook api constants
const URL_FB_AUTH = 'https://www.facebook.com/v7.0/dialog/oauth';
const URL_FB_ACCESS_TOKEN = 'https://graph.facebook.com/v7.0/oauth/access_token?';
const URL_FB_API = 'https://graph.facebook.com/me?fields=id,name,email';