<?php
class TaskDB {
    public static function createTask($userId, $task){
        $query = "INSERT INTO tasks(userId, task) values(:userId, :task)";
        $db = Database::getDB();
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":userId",$userId);
        $stmnt->bindValue(":task",$task);
        $stmnt->execute();
        $stmnt->closeCursor();
        return $db->lastInsertId("id");
    }
    
    public static function getTasks($userId){
        $db = Database::getDb();
        $query = "SELECT task, id, status, created_at FROM tasks WHERE userId = :userId";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":userId", $userId);
        $stmnt->execute();
        $result= $stmnt->fetchAll(PDO::FETCH_ASSOC);
        $stmnt->closeCursor();
        
      
        return $result;
    }
    
    public static function getTask($userId,$id){
        $db = Database::getDb();
        $query = "SELECT task, id, status, created_at FROM tasks WHERE userId = :userId and id = :id";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":userId", $userId);
        $stmnt->bindValue(":id", $id);
        $stmnt->execute();
        $result= $stmnt->fetchAll(PDO::FETCH_ASSOC);
        $stmnt->closeCursor();
        
      
        return $result;
    }
    
    public static function updateTask($id,$userId,$task,$status){
        $db = Database::getDb();
        $query = "UPDATE tasks set task = :task, status = :status WHERE userId = :userId and id = :id";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":userId", $userId);
        $stmnt->bindValue(":id", $id);
        $stmnt->bindValue(":task", $task);
        $stmnt->bindValue(":status", $status);
        $stmnt->execute();
        $stmnt->closeCursor();
    }
    
    public static function deleteTask($id,$userId){
        $db = Database::getDb();
        $query = "DELETE from tasks WHERE userId = :userId and id = :id";
        $stmnt = $db->prepare($query);
        $stmnt->bindValue(":userId", $userId);
        $stmnt->bindValue(":id", $id);
        $stmnt->execute();
        $stmnt->closeCursor();
    }
}
?>