<?php

class Database{
    private static $db;
   
    public static function getDB(){
        try{
            include_once dirname(__FILE__) . './Config.php';
            self::$db = new PDO(DSN,USERNAME,PASSWORD,OPTIONS);
        }catch(PDOException $e){
            self::$db = null;
            echo "failed to  open connection to " . DSN.$e->getMessage();
        }
        return self::$db;
    }
}
?>
