<?php 
namespace App\Helpers\GoogleApi;

use Google\Client;
use Google\Service\Docs;
use Google\Service\Drive;
use Google\Service\Sheets\SpreadSheet;
/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */

// TO DO: refactor this

function go_googleApi($title,$type='copy'){

    
    $return=[];
    $client = new Client();
    $client->setApplicationName('Invoice app');
    $client->setAuthConfig(APP_ROOT . '/config/invoiceapp-credentials.json');
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->addScope(\Google\Service\Drive::DRIVE);
    $credentialsPath = APP_ROOT . '/config/' . $_SESSION['user']->id . 'token.json';
    $credentialsPath2 = APP_ROOT . '/config/' . $_SESSION['user']->id . 'refreshtoken.json';
   
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
       if(isset($accessToken['error'])){
        
        $authUrl = $client->createAuthUrl();
        $return['message']= "Om gebruik te maken van Google spreadsheets, moet je inloggen met je google account.
        <br><br>Gebruik de volgende link om deze website toegang te geven tot google spreadsheets.
        <br><br><a href='{$authUrl}'>Ga naar google authorizatie</a>";
       }else{
        
            $client->setAccessToken($accessToken);
            // Refresh the token if it's expired.
            if ($client->isAccessTokenExpired()) {
                $refreshToken = json_decode(file_get_contents($credentialsPath2), true);
                $result_refresh=$client->fetchAccessTokenWithRefreshToken($refreshToken);
                if(isset($result_refresh['error'])){
                    
                    $authUrl = $client->createAuthUrl();
                    $return['message']= "Om gebruik te maken van Google spreadsheets, moet je inloggen met je google account.
                    <br><br>Gebruik de volgende link om deze website toegang te geven tot google spreadsheets.
                    <br><br><a href='{$authUrl}'>Ga naar google authorizatie</a>";
                }else{
                    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
                }
            }
            //////// NU KAN JE DOOR //
            
            if($type=='copy' && !isset($return['message'])){
                return copy_spreadsheet($client,$title);
            }elseif($type=='new' && !isset($return['message'])){
                return create_spreadsheet($client,$title);
            }
        }
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        $return['message']= "Om gebruik te maken van Google spreadsheets, moet je inloggen met je google account.
        <br><br>Gebruik de volgende link om deze website toegang te geven tot google spreadsheets.
        <br><br><a href='{$authUrl}'>Ga naar google authorizatie</a>";
        
        //redirect
    }
    if(isset($return['message'])){
        return $return;
    }else{
        return false;
    }
}

function copy_spreadsheet($client,$title){
    //$service = new \Google_Service_Sheets($client);
    $serviceDrive = new \Google_Service_Drive($client);
    $drive = new \Google_Service_Drive_DriveFile();
    $drive->setName($title);
    $spreadsheet = $serviceDrive->files->copy($_SESSION['user']->sheet_template, $drive);
    //

    $parameters = array();
    // Specify what fields you want 
    $parameters['fields'] = "permissions(*)";
    // Call the endpoint 
    $permissions = $serviceDrive->permissions->listPermissions($spreadsheet->id, $parameters);
    // print results
    $email='';
    foreach ($permissions->getPermissions() as $permission){
        if(isset($permission['emailAddress'])){
            $email=$permission['emailAddress'];
        }
    }
    $respons=insertPermission($serviceDrive, $spreadsheet->id, $email, 'user', 'writer');
    if(isset($respons['error'])){
        return $respons;
    }else{
        return array('id'=>$spreadsheet->id);
    }
    
}

function insertPermission($service, $fileId, $value, $type, $role)
{
    $newPermission = new \Google_Service_Drive_Permission();
    $newPermission->setEmailAddress($value);
    $newPermission->setType($type);
    $newPermission->setRole($role);
    try {
        return $service->permissions->create($fileId, $newPermission);
    } catch (\Exception $e) {
        return array('error'=>$e->getMessage());
    }
    return NULL;
}
function create_spreadsheet($client,$title){
    
    $service = new \Google_Service_Sheets($client);
    try{

        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $title
                ]
            ]);
            $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                'fields' => 'spreadsheetId'
            ]);
            return array('id'=>$spreadsheet->spreadsheetId);
    }
    catch(\Exception $e) {
        // TODO - handle error appropriately
        return array('error'=>$e->getMessage());
      }
}
?>