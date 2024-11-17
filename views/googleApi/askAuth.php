<?php

use function views\header\render_header;
use Google\Client;
use Google\Service\Docs;
use function App\Helpers\Functions\is_local;
/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */
echo render_header('inloggen Google');
?>
<div class="container">

<h1 class='title'>Connect to Google Api:</h1>
<?php

$client = new Client();
    $client->setApplicationName('Google Docs API PHP Quickstart');
    //$client->setScopes('https://www.googleapis.com/auth/documents.readonly      ');
    $client->setAuthConfig(APP_ROOT . '/config/invoiceapp-credentials.json');
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    if(!is_local()){
        $client->setRedirectUri("https://www.tlandman.nl/googleapi/");
    }
    $client->addScope(Google\Service\Drive::DRIVE);
    $credentialsPath = APP_ROOT . '/config/' . $_SESSION['user']->id . 'token.json';
    $credentialsPath2 = APP_ROOT . '/config/' . $_SESSION['user']->id . 'refreshtoken.json';
   
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
       if(isset($accessToken['error'])){
        $authUrl = $client->createAuthUrl();
        echo "Om gebruik te maken van Google spreadsheets, moet je inloggen met je google account.";
        echo "<br><br>Gebruik de volgende link om deze website toegang te geven tot google spreadsheets.";
        echo "<br><br><a href='{$authUrl}'>Ga naar google authorizatie</a>";
       }else{
            $client->setAccessToken($accessToken);
            $go=true;
            // Refresh the token if it's expired.
            if ($client->isAccessTokenExpired()) {
                $refreshToken = json_decode(file_get_contents($credentialsPath2), true);
                $res_refresh =$client->fetchAccessTokenWithRefreshToken($refreshToken);
                if(isset($res_refresh['error'])){
                    $go=false;
                }else{
                    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
                }
            }
            $authUrl = $client->createAuthUrl();
            if($go){
                
                echo "Er is nog steeds een geldige token voor {$_SESSION['user']->firstname} om de Google Api te gebruiken.<br><br>
                Ervaar je toch problemen? Klik dan hier om opnieuw authorizatie te verlenen voor google:<br><br>
                <a href='{$authUrl}'>Ga naar google authorizatie</a> 
                ";
            }else{
                echo "Auth token om de Google Api te gebruiken is verlopen voor {$_SESSION['user']->firstname} .<br><br>
                Klik hier om opnieuw authorizatie te verlenen voor google:<br><br>
                <a href='{$authUrl}'>Ga naar google authorizatie</a> 
                ";
            }
        }
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        echo "Om gebruik te maken van Google spreadsheets, moet je inloggen met je google account.";
        echo "<br><br>Gebruik de volgende link om deze website toegang te geven tot google spreadsheets.";
        echo "<br><br><a href='{$authUrl}'>Ga naar google authorizatie</a>";
        
        //redirect
    }
    

?>
</div>
</body>
</html>