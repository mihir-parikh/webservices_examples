<?php
require_once("Rest.inc.php");

class API extends Rest{
   public $data = "";
   const DB_SERVER = "localhost";
   const DB_USER = "root";
   const DB_PASSWORD = "";
   const DB = "9_lessons";
   
   private $db = NULL;
   
   public function __construct(){
      //Init parent constructor
      parent::__construct();
	  //Initiate database connection
	  $this->dbConnect();
   }
   
   //Database connection
   private function dbConnect(){
      $this->db = mysql_connect(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD);
	  if($this->db){
	     mysql_select_db(self::DB, $this->db);
	  }
   }
   
   //Public method for accessing api
   //This method dynamically call the method based on the query string
   public function processApi(){
      $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
	  
	  if((int)method_exists($this, $func) > 0)
	     $this->$func();
	  else
	     $this->response("", 404);
		 //If the method doesn't exist with in this class, response would be "Page not found."
   }
   
   private function login(){
      //Cross validation if the request method id POST else it will return "Not Acceptable" status
	  if($this->get_request_method() != "POST"){
	     $this->response("", 406);
	  }
	  
	  $email = $this->_request['email'];
	  $password = $this->_request['pwd'];
	  
	  //Input validations
	  if(!empty($email) && !empty($password)){
	     if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		    $sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_email = '$email' AND user_password = '".md5($password)."' LIMIT 1", $this->db);
			
			if(mysql_num_rows($sql) > 0){
			   $result = mysql_fetch_array($sql, MYSQL_ASSOC);
			   //If success everything is good send header as "OK" and user profile
			   $this->response($this->json($result), 200);
			}
			//If no records "No content" status
			$this->response("", 204);
		 }		
	  }
	  
	  //If invalid inputs "Bad Request" status message and reason
	  $error = array('status' => "Failed", "msg" => "Invalid email address or password");
	  $this->response($this->json($error), 400);
   }
   
   private function users(){
      //Cross validation if the request method is GET else it will return "Not Acceptable" status
	  if($this->get_request_method() != "GET"){
	     $this->response("", 406);
	  }
	  
	  $sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1", $this->db);
	  
	  if(mysql_num_rows($sql) > 0){
	     $result = array();
		 while($rlt = mysql_fetch_array($sql, MYSQL_ASSOC)){
		    $result[] = $rlt;
		 }
		 
		 //If success everything is good and send header as "OK" and return list of users in JSON format
		 $this->response($this->json($result), 200);
	  }
	  //If no records "No Content" status
	  $this->response("", 204);
   }
   
   public function deleteUser(){
      if($this->get_request_method() != "DELETE"){
	     $this->response("", 406);
	  }
	  $id = (int)$this->_request['id'];
	  
	  if($id > 0){
	     mysql_query("DELETE FROM users WHERE user_id = $id");
		 $success = array("status" => "Success", "msg" => "Successfully deleted record");
		 $this->response($this->json($success), 200);
	  }
	  else{
	     //If no records "No Content" status
	     $this->response("", 204);
	  }
   }
   
   public function json($data){
      if(is_array($data)){
	     return json_encode($data);
	  }
   }
}
//Initiate Library
$api = new API();
$api->processApi();
?>