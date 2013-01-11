<?php
//Another simple example of utilising REST API of SugarCRM

//Find out all of the modules that are accessible to the logged in user
//For any of the module, retrieve all of the fields available
 

$url = "http://127.0.0.1:8081/sugarcrm/service/v4_1/rest.php";

$ch = curl_init($url);

$loginData = array(
   "user_auth" => array(
      "user_name" => "username",
      "password" => md5("password")
   )
);

$loginDataJSON = json_encode($loginData);

$postFieldsLogin = "method=login&input_type=JSON&response_type=JSON&rest_data=$loginDataJSON";

//Return the output, don't just display it on the screen.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsLogin);

$loginResponse = json_decode(curl_exec($ch));
$session = $loginResponse->id;

$retrieveModuleFields = array(
   "session" => $session
);
$retrieveModuleFieldsJSON = json_encode($retrieveModuleFields);

$postFieldsModules = "method=get_available_modules&input_type=JSON&response_type=JSON&rest_data=$retrieveModuleFieldsJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsModules);
$modulesResponse = json_decode(curl_exec($ch));

//Dump all of the modules that are available to the logged in user
var_dump($modulesResponse);

//Retrieve one of the modules
if(!empty($modulesResponse)){
   $firstModule = $modulesResponse->modules["1"]->module_key;
   
   //Pull a list of all fields available for the module
   $moduleAvailableFieldsData = array(
      "session" => $session,
   	  "module_name" => $firstModule
   );
   
   $moduleAvailableFieldsDataJSON = json_encode($moduleAvailableFieldsData);
   
   $postFieldsPull = "method=get_module_fields&input_type=JSON&response_type=JSON&rest_data=$moduleAvailableFieldsDataJSON";
   curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsPull);
   $pullResponse = json_decode(curl_exec($ch));
   
   //Dump all of the fields available for module
   var_dump($pullResponse);
}

$logoutData = array("session" => $session);
$logoutDataJSON = json_encode($logoutData);
$postLogout = "method=logout&input_type=JSON&response_type=JSON&rest_data=$loginDataJSON";

curl_setopt($ch, CURLOPT_POSTFIELDS, $postLogout);

$logoutResponse = json_decode(curl_exec($ch));

curl_close($ch);

?>