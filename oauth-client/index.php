<?php

// Include all providers and Provider class we need
require 'Core/Providers/Provider.php';
require 'Core/Providers/App.php';
require 'Core/Providers/Facebook.php';
require 'Core/Providers/Github.php';
require 'Core/Providers/Discord.php';
require 'Core/Providers/Google.php';

// Include all function from others class
require 'Core/Constant/constants.php';
require 'Core/Constant/dotenv.php';
require 'Core/Constant/helpers.php';

// Create the provider href with the right information
function getLink(string $link, string $label, array $options = [])
{
    $link = str_replace('%27', '', $link);
    $link = str_replace('%3B','', $link);
    $codeHTML = "<p>Connect with</p><button><a href=${link}>${label}</a></button><br><br>";
    return $codeHTML;
}

// Create connection link for each providers with the authorization page url
function welcome(array $providers)
{
    foreach ($providers as $provider) {
        echo getLink($provider['instance']->getAuthorizeUrl(), $provider['connect']);
    }
}

// Get the right provider redirect url and instance the right credentials for the provider
function getProviders()
{
    $redirect_uri = 'https://localhost/login';
    return [
        'app' => [
            'connect' => 'Application',
            'instance' => new App(CLIENT_ID, CLIENT_SECRET, "${redirect_uri}?provider=app", ['scope' => 'userinfo', 'state' => 'state_example'])
        ],
        'facebook' => [
            'connect' => 'Facebook',
            'instance' => new Facebook(CLIENT_FB_CLIENT_ID, CLIENT_FB_SECRET, "${redirect_uri}?provider=facebook")
        ],
        'discord' => [
            'connect' => 'Discord',
            'instance' => new Discord(CLIENT_DISCORD_CLIENT_ID, CLIENT_DISCORD_SECRET, "${redirect_uri}?provider=discord")
        ],
        'google' => [
            'connect' => 'Google',
            'instance' => new Google(CLIENT_GOOGLE_CLIENT_ID, CLIENT_GOOGLE_SECRET, "${redirect_uri}?provider=google", ['scope' => 'email'])
        ],
        'github' => [
            'connect' => 'Github',
            'instance' => new Github(CLIENT_GITHUB_CLIENT_ID, CLIENT_GITHUB_SECRET, "${redirect_uri}?provider=github", [], CLIENT_GITHUB_APP)
        ],
    ];
}

// Get user data with the function from the Provider Class
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

 // Load the env file
loadDotEnv(ENV_PATH);
// Get all providers
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
?>

