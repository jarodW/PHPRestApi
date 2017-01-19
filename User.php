<?php
class User {
    private $name;
    private $email;
    private $password;
    private $apiKey;
    private $status;
    private $userId;
    
    public function __construct(){
    }
    
    public static function addUser($name = null, $email =  null, $password = null){
        $self = new User();
        $self->name = $name;
        $self->email = $email;
        $self->password = password_hash($password, PASSWORD_DEFAULT);
        $self->apiKey = uniqid();
        $self->status = 1;
        return $self;
    }
    
    public static function createUser($result = null){
        $self = new User();
        $self->name = $result["name"];
        $self->email = $result["email"];
        $self->password = $result["password_hash"];
        $self->apiKey = $result["api_key"];
        $self->status = $result["status"];
        return $self;
    }
    
    public function getId(){
        return $this->userId;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getApiKey(){
        return $this->apiKey;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function setUserId($id){
        $this->userId = $id;
    }

}
