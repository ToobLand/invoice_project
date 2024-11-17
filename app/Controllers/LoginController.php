<?php 

namespace App\Controllers;

use Symfony\Component\Routing\RouteCollection;
use App\Models\User;
class LoginController
{
    // Show the product attributes based on the id.
	public function showAction(RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/login.php';
	}
    public function checkLogin(RouteCollection $routes)
	{
		$user = new User();
		if($user->check_login($_POST['email'],$_POST['password'],$_POST['keep_login'])){
			echo "gelukt";
		}else{
			echo 'unvalid';
		}
		
	}
	public function logOut(RouteCollection $routes)
	{
		$_SESSION['user']->logout();
		header("Location: /login");
	}
    
}