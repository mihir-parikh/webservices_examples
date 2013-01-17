<?php
//An example of utilising SugarCRM REST API

//Add three entries in Contacts module
//Update their first name based on their ids

$url = "http://localhost/sugarcrm/service/v4_1/rest.php";

$ch = curl_init($url);

$loginData = array(
   "user_auth" => array(
      "user_name" => "username",
      "password" => md5("password")
   )
);

$loginDataJSON = json_encode($loginData);

$postFieldsLogin = "method=login&input_type=JSON&response_type=JSON&rest_data=$loginDataJSON";

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsLogin);

$loginResponse = json_decode(curl_exec($ch));
$session = $loginResponse->id;

//Add three entries
$contactsNameValueLists[] = array(
   array(   
      "name" => "last_name",
      "value" => "Smith"
   )   
);
$contactsNameValueLists[] = array(
   array(
      "name" => "last_name",
      "value" => "Joe"
   )
);
$contactsNameValueLists[] = array(
   array(
      "name" => "last_name",
      "value" => "Bob"
   )
);

$threeRecordsParams = array(
   "session" => $session,
   "module_name" => "Contacts",
   "name_value_lists" => $contactsNameValueLists
);

$threeRecordsParamsJSON = json_encode($threeRecordsParams);

$postFieldsContactsRecords = "method=set_entries&input_type=JSON&response_type=JSON&rest_data=$threeRecordsParamsJSON";

curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsContactsRecords);

$contactsRecordsResponse = json_decode(curl_exec($ch));
$contactsRecordsResponse = $contactsRecordsResponse->ids;

//Update the same records
for($i = 0; $i < sizeof($contactsRecordsResponse); $i++){
   $updateData[] = array(
      array(
         "name" => "id",
         "value" => $contactsRecordsResponse[$i]
      ),
      array(
         "name" => "first_name",
         "value" => "Mr"
      )
   );    
}

$module = "Contacts";

$updateParameters = array(
	"session" => $session,
	"module_name" => $module,
	"name_value_lists" => $updateData
);

$updateParametersJSON = json_encode($updateParameters);

$updateArgs = "method=set_entries&input_type=JSON&response_type=JSON&rest_data=$updateParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $updateArgs);

$response = json_decode(curl_exec($ch));

//Logout
$logoutParams = array("session" => $session);
$logoutJSON = json_encode($logoutParams);

$logoutArgs = "method=logout&input_type=JSON&response_type=JSON&rest_data=$logoutJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $logoutArgs);

curl_exec($ch);

curl_close($ch);
?>