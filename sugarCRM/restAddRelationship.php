<?php
//Add an entry to Contacts module
//Add an entry to Accounts module
//Add a relationship between Accounts and Contacts records

$url = "http://localhost/sugarcrm/service/v4_1/rest.php";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

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

$contactParameters = array(
   "session" => $session,
   "module" => "Contacts",
   "name_value_list" => array(
      array(
         "name" => "last_name",
         "value" => "Magana"
      )
   )
);

$contactParametersJSON = json_encode($contactParameters);
$postFieldsContacts = "method=set_entry&input_type=JSON&response_type=JSON&rest_data=$contactParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsContacts);
$contactResponse = json_decode(curl_exec($ch));

$accountParameters = array(
   "session" => $session,
   "module" => "Accounts",
   "name_value_list" => array(
      array(
         "name" => "name",
         "value" => "MaganaAccount"
      )
   )
);

$accountParametersJSON = json_encode($accountParameters);
$postFieldsAccounts = "method=set_entry&input_type=JSON&response_type=JSON&rest_data=$accountParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsAccounts);
$accountResponse = json_decode(curl_exec($ch));

$relationshipParameters = array(
   "session" => $session,
   "module_name" => "Contacts",
   "module_id" => $contactResponse->id,
   "link_field_name" => "accounts", 
   "related_ids" => array($accountResponse->id)
);
$relationshipParametersJSON = json_encode($relationshipParameters);
$postFieldsRelationship = "method=set_relationship&input_type=JSON&response_type-JSON&rest_data=$relationshipParametersJSON";
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsRelationship);
$relationshipResponse = json_decode(curl_exec($ch));

curl_close($ch);
?>