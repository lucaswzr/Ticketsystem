<?php

namespace APP\db;

class ConnectionDB
{
    private static $datasoure = 'mysql:host=localhost;dbname=ticketsystem';
    private static $username = 'root';
    private static $password = 'root';
    private static $db;

    private function __construct(){}

    //the main public function which will return the required PDO object
    public static function getDB(){
        if(!isset(self::$db)){
            try{
                self::$db=new \PDO(self::$datasoure,self::$username,self::$password);

            }
            catch(\PDOException $e)
            {
                echo "Fehler beim Verbindungsaufbau: " . $e->getMessage();
                exit();
            }
        }
        return self::$db;
    }
}