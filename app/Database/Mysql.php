<?php 
namespace App\Database;

use PDO;
use function App\Helpers\Functions\is_local;

class Mysql
{
    private $host;
    private $port;
    private $dbName;
    private $user;
    private $pass;
    protected $conn;

    public function __construct(){
        $this->connect();
    }
    public function connect(){
        if(is_local()){
            $this->host = _DB_HOST_LOCAL;
            $this->port = _DB_PORT_LOCAL;
            $this->dbName = _DB_NAME_LOCAL;
            $this->user = _DB_USER_LOCAL;
            $this->pass = _DB_PASS_LOCAL;
        }else{
            $this->host = _DB_HOST;
            $this->port = _DB_PORT;
            $this->dbName = _DB_NAME;
            $this->user = _DB_USER;
            $this->pass = _DB_PASS;
        }

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
        // Set options
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES `utf8` COLLATE `utf8_general_ci`"
        );
        // Create a new PDO instanace
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            $this->conn->query('SET NAMES utf8');
            return true;
        } catch (PDOException $e) {
            $this->conn = null;
            trigger_error('COULD NOT CONNECT TO DATABASE: "' . $this->dbName . '"' . $e->getMessage());
            die();
        }
        return $this->conn;   
    }
    public function isConnected(){
        if (null !== $this->conn) {
            try {
                $this->conn->query('SELECT 1');
                return true;
            } catch (PDOException $e) {
                return $this->connect();
            }
        } else {
                return $this->connect();
        }
        return false;
    }
    public function fetchData($query,$values=[]){
        if(count($values)>0){
            try {
                $rRes = $this->conn->prepare($query);
                $rRes->execute($values);
                $_res= $rRes->fetchAll(PDO::FETCH_ASSOC);
                return $_res;
            } catch (Exception $e) {
                $this->isConnected(); // eerst checken of connectie weg gevallen is, restart connectie.
                try { // nog een keer proberen.
                    $rRes = $this->conn->prepare($query);
                    $rRes->execute($values);
                    $_res= $rRes->fetchAll(PDO::FETCH_ASSOC);
                    return $_res;
                } catch (Exception $e) { // anders error log eruit gooien
                   trigger_error("mysql Error: " . $e->getMessage() . "\n" . $query, E_USER_ERROR);
                }
            }
        }else{
            try {
                    $rRes = $this->conn->query($query);
                    $_res= $rRes->fetchAll(PDO::FETCH_ASSOC);
                    return $_res;
                } catch (Exception $e) {
                    $this->isConnected(); 
                    try { 
                        $rRes = $this->conn->query($query);
                        $_res= $rRes->fetchAll(PDO::FETCH_ASSOC);
                        return $_res;
                    } catch (Exception $e) { 
                        trigger_error("mysql Error: " . $e->getMessage() . "\n" . $query, E_USER_ERROR);
                    }
                }
        }
    }
    public function save_query($query,$values=[]){
        try {
            $rRes = $this->conn->prepare($query);
            $rRes->execute($values);
            $_res= $this->conn->lastInsertId();
            return $_res;
        } catch (Exception $e) {
            $this->isConnected(); 
            try { 
                $rRes = $this->conn->prepare($query);
                $rRes->execute($values);
                $_res= $this->conn->lastInsertId();
                return $_res;
            } catch (Exception $e) { 
               trigger_error("mysql Error: " . $e->getMessage() . "\n" . $query, E_USER_ERROR);
            }
        }
    }
    public function exec_query($query,$values=[]){
        try {
            $rRes = $this->conn->prepare($query);
            $rRes->execute($values);
            return true;
        } catch (Exception $e) {
            $this->isConnected(); 
            try { 
                $rRes = $this->conn->prepare($query);
                $rRes->execute($values);
                return true;
            } catch (Exception $e) { 
               trigger_error("mysql Error: " . $e->getMessage() . "\n" . $query, E_USER_ERROR);
            }
        }
    }
}