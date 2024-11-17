<?php

namespace App\Models;

use App\Database\AbsData;
use App\Database\Mysql;
use App\Models\Keeplogin;

class User extends AbsData
{
    protected $table = "user";
    protected $fields = [
        'id' => ['type' => 'int', 'required' => false, 'default' => 0, 'encrypt' => 0],
        'firstname' => ['type' => 'string', 'required' => true, 'default' => false, 'encrypt' => 0],
        'middlename' => ['type' => 'string', 'required' => false, 'default' => false, 'encrypt' => 0],
        'lastname' => ['type' => 'string', 'required' => true, 'default' => false, 'encrypt' => 0],
        'email' => ['type' => 'email', 'required' => true, 'default' => false, 'encrypt' => 1],
        'password' => ['type' => 'string', 'required' => true, 'default' => false, 'encrypt' => 3],
        'keep_login' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'street' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'housenumber' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'postalcode' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'city' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'kvk' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'btw' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'iban' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'company' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'logo' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0],
        'kor' => ['type' => 'string', 'required' => false, 'default' => '0', 'encrypt' => 0],
        'sheet_template' => ['type' => 'string', 'required' => false, 'default' => '', 'encrypt' => 0]
    ];
    protected $cookie_device='tlandman_device';
    protected $cookie_hash='tlandman';

    public function check_login($email = '', $password = '', $keep_login = 0)
    {
        if ($email == '' || $password == '') {
            trigger_error("Fill in both your email and password", E_USER_ERROR);
            exit;
        } else {
            $conn = new Mysql();
            $result = $conn->fetchData("SELECT user.id, user.password FROM `{$this->table}` WHERE email='{$email}' LIMIT 1");
            if ($result[0]['id'] > 0) {
                if (password_verify($password, $result[0]['password'])) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user'] = new User($result[0]['id']);
                    $_SESSION['user']->password = '';

                    if ($keep_login == 1) {
                        // keep user logged in , with unique hash in cookie.
                        
                        $device=mt_rand(100000,100000000) . $_SESSION['user']->id;
                        if(isset($_COOKIE[$this->cookie_device])){
                            // if there was a valid keeplogin. we need to reset this. Somehow it doesn't work anymore, because user is loggin in
                            // again. So delete everything related
                            // and create later a new valid one.
                            $keeplogin=$_SESSION['user']->find_keeplogin_device($_COOKIE[$this->cookie_device]);
                            if($keeplogin){
                                $keeplogin->delete();
                            }
                        }
                        // create or overwrite cookie for device identifier.
                        setcookie($this->cookie_device, $device, time() + (86400 * 730), "/");

                        $seed = $result[0]['password'];
                        $hash = sha1(uniqid($seed . mt_rand(), true));
                        $unique_hash = substr($hash, 0, 100);
                        $keep_login=new Keeplogin();
                        $keep_login->id_user=$_SESSION['user']->id;
                        $keep_login->device=$device;
                        $keep_login->hash=$unique_hash;
                        $keep_login->date=time();
                        $keep_login->save();
                        
                        $cookie_value = "1H34fbHJ" . $_SESSION['user']->id . "||" . $unique_hash;
                        setcookie($this->cookie_hash, $cookie_value, time() + (86400 * 730), "/"); // 86400 = 1 day, so 2 years
                    }
                return true;
            } else {
                $_SESSION['user'] = false;
                $_SESSION['loggedin'] = false;
                return false;
            }
            } else {
                $_SESSION['user'] = false;
                $_SESSION['loggedin'] = false;
                return false;
            }
        }
    }
    protected function find_keeplogin_device($value){
        $conn = new Mysql();
        $result = $conn->fetchData("SELECT id FROM `keeplogin` WHERE `device`=? LIMIT 1",[$value]);
        if (isset($result[0]) && $result[0]['id'] > 0) {
            return new Keeplogin($result[0]['id']);
        }else{
            return false;
        }
    }
    protected function find_keeplogin_hash($value){
        $conn = new Mysql();
        $result = $conn->fetchData("SELECT id FROM `keeplogin` WHERE `hash`=? LIMIT 1",[$value]);
        if (isset($result[0]) && $result[0]['id'] > 0) {
            return new Keeplogin($result[0]['id']);
        }else{
            return false;
        }
    }
   
    public function login_with_cookie()
    {
        if (isset($_COOKIE[$this->cookie_hash]) && isset($_COOKIE[$this->cookie_device])) {
            $value = explode('||', $_COOKIE[$this->cookie_hash]);
            $id = str_replace('1H34fbHJ', '', $value[0]);
            if (is_numeric($id)) {
                $user = new User((int)$id);
                $keep_login=$user->find_keeplogin_device($_COOKIE[$this->cookie_device]);
                if($keep_login){
                    if($keep_login->hash==$value[1] && $user->id == $keep_login->id_user){
                        session_regenerate_id();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user'] = $user;
                        $_SESSION['user']->password = '';
                        return true;
                    }
                }
            } 
        } 
        // if above stuff failed. we need to cleanup the cookies and delete related keeplogin database records
        $this->cleanup_cookies();

        return false;
    }
    protected function cleanup_cookies(){
        if(isset($_COOKIE[$this->cookie_device])){
            $keep_login=$this->find_keeplogin_device($_COOKIE[$this->cookie_device]);
            if($keep_login){
                $keep_login->delete();
            }
            setcookie($this->cookie_device, '', -100, "/");
        }
        if(isset($_COOKIE[$this->cookie_hash])){
            $value = explode('||', $_COOKIE[$this->cookie_hash]);
            $keep_login=$this->find_keeplogin_hash($value[1]);
            if($keep_login){
                $keep_login->delete();
            }
            setcookie($this->cookie_hash, '', -100, "/");
        }
    }
    public function logout()
    {
        $_SESSION['user'] = false;
        $_SESSION['loggedin'] = false;
        $this->cleanup_cookies();

        header("Location: /login");
        die();
    }
}
