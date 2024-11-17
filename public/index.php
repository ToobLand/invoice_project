<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');  
date_default_timezone_set('Europe/Amsterdam');
// place tis somewhere else later
define ('SITE_ROOT', realpath(dirname(__FILE__)));


// Load Config
require_once '../config/config.php';
require_once '../app/Helpers/ErrorHandler.php';
require_once '../app/Helpers/Functions.php';
require_once '../app/Helpers/GoogleApi.php';
require_once('../views/includes/allIncludes.php');
// Autoloader
require_once '../vendor/autoload.php';
  // session start after inititalizing classes. (user object is inside $_SESSION['user'])
session_start();
require_once '../app/Helpers/AuthAccess.php'; // checks if a session is required. Default yes, if not edit this helper
// Routes    

require_once '../routes/web.php';
require_once '../app/router.php';



