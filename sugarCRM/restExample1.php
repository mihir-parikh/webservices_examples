<?php
//A simple example of usage of SugarCRM REST API

//Create a "Contacts" record


//Specify the REST web service to interact with
$url = "http://localhost/sugarcrm/service/v4_1/rest.php";

//Open a curl session for making a call
$curl = curl_init($url);

//Tell curl to use HTTP POST
curl_setopt($curl, CURLOPT_POST, true);

//Tell curl to return the response
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//Set the POST arguments to pass to Sugar server
$parameters = array(
   'user_auth' => array(
      'user_name' => 'username',
      'password' => md5('password')         
   )      
);

$json = json_encode($parameters);

$postArgs = "method=login&input_type=JSON&response_type=JSON&rest_data=".$json;
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

//Make the REST call
$response = json_decode(curl_exec($curl));

//Now the session is created
$session = $response->id;
echo "Login result $session <br />";

//Create the contact
//Set the POST arguments to pass to the Sugar server
$fields = array("id", "first_name", "last_name", "email");
$record_data = array("", "Test3", "Test3", "test3@gmail.com");
$module = "Contacts";

$parameters = array(
   "session" => $session,
   "module_name" => $module,
   "name_value_list" => array(
      array("name" => $fields[0], "value" => $record_data[0]),
      array("name" => $fields[1], "value" => $record_data[1]),
      array("name" => $fields[2], "value" => $record_data[2]),
      array("name" => $fields[3], "value" => $record_data[3])
   )
);

$json = json_encode($parameters);

$postArgs = "method=set_entry&input_type=JSON&response_type=JSON&rest_data=".$json;
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

//Make the REST call
$response = json_decode(curl_exec($curl));
$contact_id = $response->id;
echo "Set entry result $contact_id <br />";

//Update the same record
$fields = array("id", "last_name");
$record_data = array($contact_id, "Test4");
$module = "Contacts";

$parameters = array(
	"session" => $session,
	"module_name" => $module,
	"name_value_list" => array(
		array("name" => $fields[0], "value" => $record_data[0]),
		array("name" => $fields[1], "value" => $record_data[1]),
	)
);

$json = json_encode($parameters);

$postArgs = "method=set_entry&input_type=JSON&response_type=JSON&rest_data=".$json;
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

$response = json_decode(curl_exec($curl));


//Logout of Sugar
$parameters = array("session" => $session);
$json = json_encode($parameters);

$postArgs = "method=logout&input_type=JSON&response_type=JSON&rest_data=".$json;
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

//Make the REST call
$response = json_decode(curl_exec($curl));

//Close the connection
curl_close($curl);

?>