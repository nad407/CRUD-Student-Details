<?php
/**
 * Connect/Disconnect with the database 'School' using PDO
 *
 * @author Nadine
 */
class Database{
    private static $serverName = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbName = "School";
    
    private static $conn=null;
    
    public static function connect(){
        try {
            self::$conn = new PDO("mysql:host=".self::$serverName.";dbname=".self::$dbName, self::$username, self::$password);
            // set the PDO error mode to exception
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
            {
        echo "Connection failed: " . $e->getMessage();
        }
        
        return self::$conn;
    }
    
    public static function disconnect(){
        self::$conn = null;
    }
}
