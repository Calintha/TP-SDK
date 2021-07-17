<?php

// For the environment
const ENV_PATH = '.env';
// Api constants
const URL_APP_AUTH = 'http://localhost:8081/auth';
const URL_APP_ACCESS_TOKEN = 'http://oauth-server:8081/token';
const URL_APP_API = 'http://oauth-server:8081/me';
// Facebook api constants
const URL_FB_AUTH = 'https://www.facebook.com/v7.0/dialog/oauth';
const URL_FB_ACCESS_TOKEN = 'https://graph.facebook.com/v7.0/oauth/access_token';
const URL_FB_API = 'https://graph.facebook.com/me?fields=id,name,email';
// Github api constants
const URL_GITHUB_AUTH = 'https://github.com/login/oauth/authorize';
const URL_GITHUB_ACCESS_TOKEN = 'https://github.com/login/oauth/access_token';
const URL_GITHUB_API = 'https://api.github.com/user';
// Discord api constants
const URL_DISCORD_AUTH="https://discord.com/api/oauth2/authorize?";
const URL_DISCORD_ACCESS_TOKEN="https://discord.com/api/oauth2/token?";
const URL_DISCORD_API="https://discord.com/api/users/@me";
// Google api constants
const URL_GOOGLE_AUTH = 'https://accounts.google.com/o/oauth2/v2/auth';
const URL_GOOGLE_ACCESS_TOKEN = 'https://oauth2.googleapis.com/token';
const URL_GOOGLE_API = 'https://www.googleapis.com/oauth2/v1/userinfo';