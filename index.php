<?php

require 'vendor/autoload.php';
require './User.php';
require './UserDb.php';
require './Task.php';
require './TaskDb.php';

$app = new Slim\App();

 

$app->post('/register', function ($request, $response, $args) {
      $data = $request->getParsedBody();
      $values = array("name","email","password");
      
      if(!validateParam($data,$values)){
        $msg = array('error'=> true, 'message' => "Error submitting form. You have an invalid field or incomplete field" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
       
      if(!validateEmail($data["email"])){
          $msg = array('error'=> true, 'message' => "error submitting form. You have an invalid email");
          $newResponse = $response->withJson($msg, 400);
          return $newResponse;
      }
      
      $password = $data["password"];
      $name = $data["name"];
      $email = $data["email"];
      $user = User::addUser($name, $email, $password);
      
      if(UserDB::userExist($email)){
          $msg = array('error'=> true, 'message' => "This email address is already in use" );
          $newResponse = $response->withJson($msg, 400);
          echo $newResponse;
      }else{
          UserDB::createUser($user);
          $msg = array('error'=> false, 'message' => "The user account has been successfuly created" );
          $newResponse = $response->withJson($msg, 201);
          echo $newResponse;
      }
      //UserDB::createUser($user);
});
 
$app->post('/login', function ($request, $response, $args) {
    
      $data = $request->getParsedBody();
      $values = array("email","password");
      
      if(!validateParam($data,$values)){
        $msg = array('error'=> true, 'message' => "Error submitting form. You have an invalid field or incomplete field" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }

      $password = $data["password"];
      $email = $data["email"];
      if(!UserDB::checkLogin($email, $password)){
          $msg = array('error'=> true, 'message' => "Login error. Wrong username or password");
          $newResponse = $response->withJson($msg,400);
          return $newResponse;
      }else{
          $user = USERDB::getUserByEmail($email);
          $msg = array('error'=> false, 'apiKey' => $user->getApiKey());
          $newResponse = $response->withJson($msg,200);
          return $newResponse;
      }
      
});


$app->post('/tasks', function ($request, $response, $args) {
    
      $data = $request->getParsedBody();
      $hdata = $request->getHeader('api');

      $values = array("task");
      
      if(!validateParam($data,$values)){
        $msg = array('error'=> true, 'message' => "Error submitting form. You have an invalid field or incomplete field" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
      if(empty($hdata) == true){
        $msg = array('error'=> true, 'message' => "Missing API key" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
     
      $api = $hdata[0];
      $task = $data["task"];
      
      $userId = USERDB::isValidApiKey($api);
      if($userId == -1){
          $msg = array('error'=> true, 'message' => "ApiKey not valid");
          $newResponse = $response->withJson($msg,400);
          return $newResponse;
      }else{
          $taskId = TaskDB::createTask($userId, $task);
          $msg = array('error'=> false, 'message' => "created successfully", 'taskId'=>$taskId);
          $newResponse = $response->withJson($msg,201);
          return $newResponse;
      }
      
});

$app->get('/tasks', function ($request, $response, $args) {
    
      $hdata = $request->getHeader('api');
      
      if(empty($hdata) == true){
        $msg = array('error'=> true, 'message' => "Missing API key" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
     
      $api = $hdata[0];

      $userId = USERDB::isValidApiKey($api);
      if($userId == -1){
          $msg = array('error'=> true, 'message' => "ApiKey not valid");
          $newResponse = $response->withJson($msg,400);
          return $newResponse;
      }else{
          $tasks = TaskDB::getTasks($userId);
          $msg = array('error'=> false, 'message' => "success", 'tasks'=>$tasks);
          $newResponse = $response->withJson($msg,200);
          return $newResponse;
      }
});

$app->get('/tasks/{id}', function ($request, $response, $args) {
    
      $hdata = $request->getHeader('api');
      $id = $args["id"];

      if(empty($hdata) == true){
        $msg = array('error'=> true, 'message' => "Missing API key" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
     
      $api = $hdata[0];

      $userId = USERDB::isValidApiKey($api);
      if($userId == -1){
          $msg = array('error'=> true, 'message' => "ApiKey not valid");
          $newResponse = $response->withJson($msg,400);
          return $newResponse;
      }
      $task = TaskDB::getTask($userId,$id);
      if(empty($task)){
        $msg = array('error'=> true, 'message' => "Bad Request" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
      else{
          $msg = array('error'=> false, 'message' => "created successfully", 'task' => $task);
          $newResponse = $response->withJson($msg,200);
          return $newResponse;
      }
});

$app->put('/tasks/{id}', function ($request, $response, $args) {
    
      $data = $request->getParsedBody();
      $hdata = $request->getHeader('api');
      $id = $args["id"];
      
      $values = array("task","status");
      
      if(!validateParam($data,$values)){
        $msg = array('error'=> true, 'message' => "Error submitting form. You have an invalid field or incomplete field" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
      
      if(empty($hdata) == true){
        $msg = array('error'=> true, 'message' => "Missing API key" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
     
      $status = $data["status"];
      $task = $data["task"];
      $api = $hdata[0];

      $userId = USERDB::isValidApiKey($api);
      if($userId == -1){
          $msg = array('error'=> true, 'message' => "ApiKey not valid");
          $newResponse = $response->withJson($msg,400);
          return $newResponse;
      }
      $taskExist = TaskDB::getTask($userId,$id);
      if(empty($taskExist)){
        $msg = array('error'=> true, 'message' => "Bad Request" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
      else{
          TaskDB::updateTask($id, $userId, $task, $status);
          $msg = array('error'=> false, 'message' => "successfully updated");
          $newResponse = $response->withJson($msg,200);
          return $newResponse;
      }
});

$app->delete('/tasks/{id}', function ($request, $response, $args) {
    
      $hdata = $request->getHeader('api');
      $id = $args["id"];
      
      if(empty($hdata) == true){
        $msg = array('error'=> true, 'message' => "Missing API key" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
     
      $api = $hdata[0];

      $userId = USERDB::isValidApiKey($api);
      if($userId == -1){
          $msg = array('error'=> true, 'message' => "ApiKey not valid");
          $newResponse = $response->withJson($msg,400);
          return $newResponse;
      }
      $taskExist = TaskDB::getTask($userId,$id);
      if(empty($taskExist)){
        $msg = array('error'=> true, 'message' => "Bad Request" );
        $newResponse = $response->withJson($msg, 400);
        return $newResponse;
      }
      else{
          TaskDB::deleteTask($id, $userId);
          $msg = array('error'=> false, 'message' => "successfully deleted");
          $newResponse = $response->withJson($msg,200);
          return $newResponse;
      }
});


function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}
 

function validateParam($data, $values){
      if(sizeof($data) != sizeof($values))
          return false;
      for($i = 0; $i < count($values);  $i++){
          if(!isset($data[$values[$i]]) || $data[$values[$i]] == ""){
              return false;
          }
      }
      return true;
}

$app->run();
?>