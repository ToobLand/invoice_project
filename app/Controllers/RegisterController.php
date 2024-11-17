<?php 

namespace App\Controllers;

use Symfony\Component\Routing\RouteCollection;
use App\Models\User;

class RegisterController
{
    // Show the product attributes based on the id.
	public function showAction(RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/register.php';
	}
    public function saveAjax(RouteCollection $routes)
	{
        // sanitizing validating data is in ABSData, to do: nive to have visual front-end validation 

		$data=json_decode($_POST['data'], true);
		$user= new User();
		$user->firstname = $data['voornaam'];
		$user->middlename = $data['tussenvoegsels'];
		$user->lastname = $data['achternaam'];
		$user->email = $data['email'];
		$user->password = $data['password'];
		$user->street = $data['street'];
		$user->housenumber = $data['housenumber'];
		$user->city = $data['city'];
		$user->postalcode = $data['postalcode'];
		$user->btw = $data['btw'];
		$user->kvk = $data['kvk'];
		$user->iban = $data['iban'];
		$user->company = $data['company'];
		$user->save();
		echo ' opslaan gelukt ';

	}
    
}