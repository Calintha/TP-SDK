<?php


function getLink(string $link, string $label, array $options = [])
{
    $ref = "<p><a href=${link}>${label}</a></p>";
    return $ref;
}

function welcome() //for each provider echo link and label
{
    foreach ($providers as $provider) {
        echo getLink($provider['instance']->getAuthorizeUrl(), $provider['label']);
    }
}

function getAllProviders()
{
    $redirect_uri = 'https://localhost/login';
    return [
        'app' => [
            'label' => 'Se connecter avec application',
            'instance' => new App(CLIENT_ID, SECRET, "${redirect_uri}?provider=app", ['scope' => 'userinfo', 'state' => 'state_example'])
        ],
        'facebook' => [
            'label' => 'Se connecter avec Facebook',
            'instance' => new Facebook(CLIENT_FB_CLIENT_ID, CLIENT_FB_SECRET, "${redirect_uri}?provider=facebook")
        ],
    ];
}

function handleSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    getUser([
        "grant_type" => "authorization_code",
        "code" => $code
    ]);
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
        handleResponse($provider, $_GET);
        break;
    default:
        http_response_code(404);
}
