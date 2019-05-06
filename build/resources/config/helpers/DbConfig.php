<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

require_once (__DIR__."/../config.php");


/**
 * PDO Database Connection class.
 */
class DbConfig
{

    /** DB connection parameters */
    private $dbHost = DB_HOST;
    private $dbName = DB_NAME;
    private $dbCharset = DB_CHARSET;
    private $dbUsername = DB_USER;
    private $dbPassword = DB_PASSWORD;
    private $options = array(
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        'enableParamLogging'=>true,
        );

    /** @var object $pdo Copy of PDO connection */
    protected $pdo;

    /**
     * Connection init function
     * set DB connection parameters
     *
     * @return object pdo.
     */
    public function dbConnect(){

        /*connect to db only if php sessions are active*/
        if(session_status() === PHP_SESSION_ACTIVE) {
            try {
                $this->pdo = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName.";charset=".$this->dbCharset, $this->dbUsername, $this->dbPassword, $this->options);
            } catch (PDOException $exception) {
                echo "Oops. Pripojenie do databázy zlyhalo.".$exception ->getMessage();
                //error_log($exception->getMessage());
                //TODO: buď to presmerovať na nejaký error page alebo na index
                die();
            }
            return $this->pdo;
        }else{
            echo "Oops. Problém so sessions.";
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }
    }


    /**
     * Close connection init function
     *
     * @return void.
     */
    public function closeConnection()
    {
        $this->pdo = null;
    }
}