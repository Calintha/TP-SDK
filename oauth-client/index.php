<?php

/**
 * "client_id":"client_6070546c6aba63.16480463"
 * "client_secret":"38201ad253c323a79d9108f4588bbc62d2e1a5c6"
 */
const CLIENT_ID = "client_6070546c6aba63.16480463";
const CLIENT_FBID = "520525662460323";
const CLIENT_SECRET = "38201ad253c323a79d9108f4588bbc62d2e1a5c6";
const CLIENT_FBSECRET = "96772e4d50f196966d966d4080507dc8";
const CLIENT_GITHUBID = "cfe032b750cd65acf337";
const CLIENT_GITHUBSECRET = "17dfd10cf3c0c810ceb8f6d277f9213ee198c992";
const CLIENT_DISCORDID = "client_id";
const CLIENT_DISCORDSECRET = "secret";

function getUser($params)
{
    $result = file_get_contents("http://oauth-server:8081/token?"
        . "client_id=" . CLIENT_ID
        . "&client_secret=" . CLIENT_SECRET
        . "&" . http_build_query($params));
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("http://oauth-server:8081/me", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}

function handleLogin()
{
    echo '<h1>Login with Auth-Code</h1>';
    echo "<a href='http://localhost:8081/auth?"
        . "response_type=code"
        . "&client_id=" . CLIENT_ID
        . "&scope=basic&state=dsdsfsfds'>Login with oauth-server</a>";
    echo "<a href='https://www.facebook.com/v2.10/dialog/oauth?"
        . "response_type=code"
        . "&client_id=" . CLIENT_FBID
        . "&scope=email&state=dsdsfsfds&redirect_uri=https://localhost/fbauth-success'>Login with Facebook</a>";
    echo "<a href='https://github.com/login/oauth/authorize?"
    . "client_id=" . CLIENT_GITHUBID
    . "&scope=user&state=dsdsfsfds&redirect_uri=https://localhost/githubauth-success'>Login with GitHub</a>";
    echo "<a href='https://discord.com/api/oauth2/authorize?"
    . "response_type=code"
    . "&client_id=" . CLIENT_DISCORDID
    . "&scope=identify%20email&state=dsdsfsfds&redirect_uri=https://localhost/discordauth-success'>Login with Discord</a>";
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

function handleFBSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    // ECHANGE CODE => TOKEN
    $result = file_get_contents("https://graph.facebook.com/oauth/access_token?"
        . "client_id=" . CLIENT_FBID
        . "&client_secret=" . CLIENT_FBSECRET
        . "&redirect_uri=https://localhost/fbauth-success"
        . "&grant_type=authorization_code&code={$code}");
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("https://graph.facebook.com/me?fields=id,name,email", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
}

function handleGitHubSuccess()
{
    ["code" => $code] = $_GET;
    $contextEchangeCode = stream_context_create([
        'http'=> [
            'method' => "GET",
            'header' => "Accept: application/json"
        ]]);
    $result = file_get_contents("https://github.com/login/oauth/access_token?"
        . "client_id=" . CLIENT_GITHUBID
        . "&client_secret=" . CLIENT_GITHUBSECRET
        . "&code={$code}"
        . "&redirect_uri=https://localhost/githubauth-success", false, $contextEchangeCode);
    $token = json_decode($result, true)["access_token"];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.github.com/user',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: token '. $token,
            'User-Agent: PHP'
        ),
    ));
    $result = curl_exec($curl);
    curl_close($curl);
    $user = json_decode($result, true);
    var_dump($user);
}

function handleDiscordSuccess()
{
    ["code" => $code, "state" => $state] = $_GET;
    var_dump($code);
    $data = http_build_query([
        "client_id"=> CLIENT_DISCORDID,
        "client_secret"=> CLIENT_DISCORDSECRET,
        "redirect_uri"=> "https://localhost/discordauth-success",
        "grant_type"=> "authorization_code",
        "code"=>$code
    ]);
    var_dump($data);
    // ECHANGE CODE => TOKEN
    $contextToken = stream_context_create([
        'http' => [
            'method' => "POST",
            'header' => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data),
            'content' => $data,
        ]
    ]);
    $result = file_get_contents("https://discord.com/api/oauth2/token", false, $contextToken);
    $token = json_decode($result, true)["access_token"];
    // GET USER by TOKEN
    $context = stream_context_create([
        'http' => [
            'method' => "GET",
            'header' => "Authorization: Bearer " . $token
        ]
    ]);
    $result = file_get_contents("https://discord.com/api/users/@me", false, $context);
    $user = json_decode($result, true);
    var_dump($user);
} 

function handleError()
{
    echo "refusé";
}

/**
 * AUTH_CODE WORKFLOW
 * => GET Code <- Générer le lien /auth (login)
 * => EXCHANGE Code <> Token (auth-success)
 * => GET USER by Token (auth-success)
 */
$route = strtok($_SERVER["REQUEST_URI"], '?');
switch ($route) {
    case '/login':
        handleLogin();
        break;
    case '/auth-success':
        handleSuccess();
        break;
    case '/githubauth-success':
        handleGitHubSuccess();
        break;
    case '/discordauth-success':
        handleDiscordSuccess();
        break;
    case '/fbauth-success':
        handleFBSuccess();
        break;
    case '/auth-error':
        handleError();
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
