<?php
//Create a contact record with custom fields populated

$url = "http://localhost/sugarcrm/service/v4_1/rest.php";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//Login
$loginParameters = array(
   "user_auth" => array(
      "user_name" => "username",
      "password" => md5("password")
   )
);

$loginParametersJSON = json_encode($loginParameters);

$postFieldsLogin = "method=login&input_type=JSON&response_type=JSON&rest_data=$loginParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsLogin);

$loginResponse = json_decode(curl_exec($ch));
$session = $loginResponse->id;

//Create a contact
$contactParameters = array(
   "session" => $session, 
   "module_name" => "Contacts",
   "name_value_list" => array(
      array(
         "name" => "last_name",
         "value" => "Angel"
      ),
      array(
         "name" => "nick_name_c",
         "value" => "Angel1"
      ),
      array(
         "name" => "nick_name2_c",
         "value" => "Angel2"
      )
   )   
);
$contactParametersJSON = json_encode($contactParameters);

$postFieldsContact = "method=set_entry&input_type=JSON&response_type=JSON&rest_data=$contactParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsContact);

$contactResponse = json_decode(curl_exec($ch));

//Logout
$logoutParameters = array(
   "session" => $session
);

$logoutParametersJSON = json_encode($logoutParameters);
$postFieldsLogout = "method=logout&input_type=JSON&response_type=JSON&rest_data=$logoutParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsLogout);
curl_exec($ch);

curl_close($ch);

?>