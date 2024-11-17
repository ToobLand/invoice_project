<?php

use App\Models\User;

if(in_array($_SERVER['REQUEST_URI'],['/login', '/login_ajax','/','','/register','/register_ajax'])){
    // no session nessecary
    
}else{
    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
       $user=new User();
       if($user->login_with_cookie()){
        // session is generated with keepLogin cookies // 
       }else{
            header("Location: /login");
            die();
       }
    }
}
