<?php 

namespace App\Controllers;

use Symfony\Component\Routing\RouteCollection;
use Google\Client;
use Google\Service\Docs;
use Google_Client;
class docs_test
{

   
    public function docs_test(RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/docs_test.php';
    }
    public function askAuth(RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/googleApi/askAuth.php';
    }
    public function return($values,RouteCollection $routes)
	{

      require_once APP_ROOT . '/views/googleApi/return.php';
    }
}