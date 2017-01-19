<?php
class Task {
   private $taskId;
   private $task;
   private $status;
   
   private function getId(){
       return $taskId;
   }
   
   private function setId($id){
       $this->taskId = $id;
   }
   
   private function setTask($task){
       $this->task = $task;
   }
   
   private function getTask(){
       return $task;
   }
   
   private function getStatus(){
       return $status;
   }
   
   private function setStatus($status){
       $this->status =  $status;
   }
}
