<?php
require 'Core/Providers/Provider.php';
require 'Core/Providers/App.php';
require 'Core/Providers/Facebook.php';
require 'Core/Providers/Discord.php';

require 'Core/Constant/constants.php';
require 'Core/Constant/dotenv.php';
require 'Core/Constant/helpers.php';

function getLink(string $link, string $label, array $options = [])
{
    $codeHTML = "<p><a href=${link}>${label}</a></p>";
    return $codeHTML;
}

function welcome(array $providers)
{
    foreach ($providers as $provider) {
        echo getLink($provider['instance']->getAuthorizeUrl(), $provider['connect']);
    }
}

function getProviders()
{
    $redirect_uri = 'https://localhost/login';
    return [
        'app' => [
            'connect' => 'Connect with application',
            'instance' => new App(CLIENT_ID, CLIENT_SECRET, "${redirect_uri}?provider=app", ['scope' => 'userinfo', 'state' => 'state_example'])
        ],
        'facebook' => [
            'connect' => 'Connect with Facebook',
            'instance' => new Facebook(CLIENT_FB_CLIENT_ID, CLIENT_FB_SECRET, "${redirect_uri}?provider=facebook")
        ],
        'discord' => [
            'connect' => 'Connect with Discord',
            'instance' => new Discord(CLIENT_DISCORD_CLIENT_ID, CLIENT_DISCORD_SECRET, "${redirect_uri}?provider=discord")
        ],
    ];
}

function handleResponse(Provider $provider, array $request)
{
    if (!$request['code']) die('Problem response');
    $data = $provider->getUser($request['code']);
    dd($data);
}

/**
 * AUTH_CODE WORKFLOW
 * => GET Code <- Générer le lien /auth (login)
 * => EXCHANGE Code <> Token (auth-success)
 * => GET USER by Token (auth-success)
 */
loadDotEnv(ENV_PATH);
$providers = getProviders();
$route = strtok($_SERVER["REQUEST_URI"], '?');
switch ($route) {
    case '/':
        welcome($providers);
        break;
    case '/login':
        if (!$provider = $providers[$_GET['provider']]['instance']) 
        die("The provider {$_GET['provider']} have problem");
        handleResponse($provider, $_GET);
        break;
    case '/logout':
        Provider::logout();
        break;
    case '/password':
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            echo "<form method='POST'>";
            echo "<input name='username'>";
            echo "<input name='password'>";
            echo "<input type='submit' value='Log with oauth'>";
            echo "</form>";
        } else {
            ['username' => $username, 'password' => $password] = $_POST;
            getUser([
                'grant_type' => "password",
                'username' => $username,
                'password' => $password
            ]);
        }
        break;
    default:
        http_response_code(404);
}
