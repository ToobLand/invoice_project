<?php

use function views\header\render_header;
use Google\Client;
use Google\Service\Docs;
/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */
echo render_header('inloggen Google');
?>
<div class="container">

<?php

$client = new Client();
    $client->setApplicationName('Google Docs API PHP Quickstart');
    //$client->setScopes('https://www.googleapis.com/auth/documents.readonly      ');
    $client->setAuthConfig(APP_ROOT . '/config/invoiceapp-credentials.json');
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->addScope(Google\Service\Drive::DRIVE);

$values= str_replace("?","",$values);
$values= explode('&',$values);
$code=explode('=',$values[0]);
$authCode= $code[1];

$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
$refreshToken = $client->getRefreshToken();

$credentialsPath = APP_ROOT . '/config/' . $_SESSION['user']->id . 'token.json';
$credentialsPath2 = APP_ROOT . '/config/' . $_SESSION['user']->id . 'refreshtoken.json';

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        file_put_contents($credentialsPath2, json_encode($refreshToken));
        echo "<h2>Connectie met Google spreadsheets gelukt!</h2>";
        echo "Je kunt dit tabblad nu sluiten";
?>
</div>
</body>
</html>