<?php 

namespace App\Controllers;

use Symfony\Component\Routing\RouteCollection;
class LandingpageController
{
    // Show the product attributes based on the id.
	public function showAction(RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/landingpage.php';
	}
}
?>