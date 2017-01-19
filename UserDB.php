<?php
include "./Database.php";

class UserDB {
    
    //insert new user into database
    public static function createUser($user){
        $query = "INSERT INTO users(name, email, password_hash, api_key, status) values(:name, :email, :password, :apiKey, 1)";
        $db = Database::getDB();
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":name",$user->getName());
        $stmnt->bindValue(":email",$user->getEmail());
        $stmnt->bindValue(":password",$user->getPassword());
        $stmnt->bindValue(":apiKey",$user->getApiKey());
        $stmnt->execute();
        $stmnt->closeCursor();
        $user->setUserId($db->lastInsertId("id"));
        return $user;
    }
    
    //checks if a user exist with the give email
    public static function userExist($email) {
        $users  = array();
        try{
            $db = Database::getDb();
            $query = "SELECT * from users WHERE email = :email";
            $stmnt = $db->prepare($query);
            $stmnt->bindValue(":email", $email);
            $stmnt->execute();
            $users= $stmnt->fetchAll(PDO::FETCH_ASSOC);
            $stmnt->closeCursor();
        }catch(Exception $e){
            echo "Error checking if user exist";
        }
        return !empty($users);
    }
    
     public static function checkLogin($email, $password) {
        // fetching user by email
        $db = Database::getDb();
        $query = "SELECT password_hash from users WHERE email = :email";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":email", $email);
        $stmnt->execute();
        $result= $stmnt->fetchAll(PDO::FETCH_ASSOC);
        $stmnt->closeCursor();
       
        if(empty($result))
            return false;
        else{
            $result = $result[0]["password_hash"];
       if (password_verify($password, $result)) {
           return true;
       }
       else
           return false;
        }
    }
 

    public static function getUserByEmail($email) {
        $db = Database::getDb();
        $query = "SELECT name, email, password_hash, api_key, status FROM users WHERE email = :email";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":email", $email);
        $stmnt->execute();
        $result= $stmnt->fetchAll(PDO::FETCH_ASSOC);
        $stmnt->closeCursor();
        if (empty($result)) {
            return null;
        } else {
            $user = User::createUser($result[0]);
            return $user;
        }
    }
 
    public static function isValidApiKey($api) {
        $db = Database::getDb();
        $query = "SELECT id FROM users WHERE api_key = :api";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":api", $api);
        $stmnt->execute();
        $result= $stmnt->fetchAll(PDO::FETCH_ASSOC);
        $stmnt->closeCursor();
        
      
        if(empty($result)){
            return -1;
        }else{
            return $result[0]["id"];
        }
    }
}
?>
