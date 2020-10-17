<?php
include 'cgi-bin/config.php';
include 'includes/databaseFunctions.php';
require_once 'vendor/autoload.php';
//use Zenstruck\JWT\Token;
//use Zenstruck\JWT\Signer\OpenSSL\ECDSA\ES256;
use \Firebase\JWT\JWT;
$jwt="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IjI5MmU4MWIzZGFmOTYwY2UifQ.eyJpc3MiOiJodHRwczpcL1wvd3d3LnBvbmNlc29sdXRpb25zLmNvbSIsImF1ZCI6Imh0dHBzOlwvXC93d3cucG9uY2Vzb2x1dGlvbnMuY29tIiwiaWF0IjoxNjAwNzU4MDAwLCJleHAiOjE5MTYxMTgwMDAsIm5iZiI6MTYwMDc1ODAwMCwiY3VzdG9tZXJJRCI6IjE3IiwiZGV2aWNlVVVJRCI6IjI5MmU4MWIzZGFmOTYwY2UiLCJjaGFyZ2VJRCI6ImN1c19JNFkyWG1CMFpmTmNoYiIsImFwcE5hbWUiOiJBbmRyb2lkIEFQUCB0byBjb25kdWN0IGludmVudG9yeSIsImFwcElEIjozLCJsaW1pdEV4cG9ydCI6MTAwMDAwMDAsImNoZWNrIjoiRkE5NUI0M0JFNENGOEJCMDMwRDg1RTY2MzMyNEVDQzUyNDdDMkFGNjI3MjY3MkJGRDRCQkMxMDIwQjQwNTgyQiIsImVtYWlsIjoiZ2lsYmVydG9AcG9uY2Vzb2x1dGlvbnMuY29tIn0.JIaBeXCDdLxcaQa6w7yR1XVmtS5vj76rhobtmo8mZv5pThA0jH5Y3h7ciRRLWZHSJ1BmcL2IDndXwSBfRfRgVKbrwYYvSZxSlkKR_OTGPzMVFdlpB1p5jTIT3iKdJANzAN6t32kiodCGm4MVbHyKNxFW8u0jVsBbVGsZBipc2lWIcUVe7lmXcftRQP-5ip_MAlmU-lXd_-Q0rc64duc7WdHUFzkMXpTf65_cHUeUOJZlspunIGnd2gfrUjCt0oi_M8bc6OEKQpVsgJpVgLUHREYHTl9fjsZVJ5rj6EdE7au9gIYBFMQ8vKchgSqYSuFYvIw67DALbmLP8yFdnLrnWY2dBvBySNaXPyIeihSfbkOImS9nwQC1LP3CrqatZGis92yTsroD1MXBitk-B_5ZaonU5cjZHg4dIABYQmIhyHP0ZdaqB1mAmghlwW18maW_s0OkLFkutdwoHx618P_Qc4cyhW5fKKtuBOGeI3fPkrRdjLcoWc2gPovqx2qXMuegrXnu6siMF03OFi5nsZBk3sOZ_TLtgjYrRkEiElnAAPrHNSLdZwDMM2NVgWPe3FBorrJPt_zWI6AFdQKSvitQ3sAKtZlB-Wqi81PKzotZ5gzoR447cACHaO2jwUp52TQy2IyZhGY_fxLQ99RbPtczl3eY9FhEtyUuRziyPU7JSd4"; //good
//$jwt="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IjI5MmU4MWIzZGFmOTYwY2UifQ.eyJpc3MiOiJodHRwczpcL1wvd3d3LnBvbmNlc29sdXRpb25zLmNvbSIsImF1ZCI6Imh0dHBzOlwvXC93d3cucG9uY2Vzb2x1dGlvbnMuY29tIiwiaWF0IjoxNjAwNzU4MDAwLCJleHAiOjE2MDA4NDQ0MDAsIm5iZiI6MTYwMDc1ODAwMCwiY3VzdG9tZXJJRCI6IjE3IiwiZGV2aWNlVVVJRCI6IjI5MmU4MWIzZGFmOTYwY2UiLCJjaGFyZ2VJRCI6ImN1c19JNFkwdUh2U1ZWd0VlUCIsImFwcE5hbWUiOiJBbmRyb2lkIEFQUCB0byBjb25kdWN0IGludmVudG9yeSIsImFwcElEIjozLCJsaW1pdEV4cG9ydCI6MTAwMDAwMDAsImNoZWNrIjoiRkE5NUI0M0JFNENGOEJCMDMwRDg1RTY2MzMyNEVDQzUyNDdDMkFGNjI3MjY3MkJGRDRCQkMxMDIwQjQwNTgyQiIsImVtYWlsIjoiZ2lsYmVydG9AcG9uY2Vzb2x1dGlvbnMuY29tIn0.nG7yrulrcZAuADVUUivKtONNpidZFf4wdojmTv7F4E_X7j-1QRjJeHwnNHvxAUwG9xaUIrnQdIe_I5Zqpi5rUFDLIRpYgJ7JFip6iX2EySKiB8VLT9VuzD1tAG9IyLNFRfr00s7bEPzqIFve4cRNqALCCDT-vta86PGUhluqzSnZSd4CbpN4gikHOUW11P_q3-kotusqktbD2B3JbfD8kbpSZO9hHywzdDfGEUWyjNlYVJahIhgYTaFAdEoUpDCtjk5hC8nXZqzAktSbr1ViRqSbYYDNE4icymUf8XlEep5IBBiFyDFlvYniDPawfz5cG5bRXn4p5prYjJ0Ctz2K4jBAEWmkJhfcbHlvTsRFfr4mAT-7mGvWh8yILZ4RWW494tEv8Ba82x2dQbnDw3d4CeBn0Q7JirtSsd2nV3aJgeEPDKtIraZk4hRGC27SvBaA7hZE-_QXc_67wJZRxkYQcPqI1Nio2HJixs0AyWtGn5eA9izq7W1-KU-ZH5wA5fGLP-kIkXIE62g5MiSx_BQuNWBsosGyNOWFnLil_PL2fNeANSPos2uxKzaaOg5QwUEwfzGYkjI6ObU0Pk2m1OYCGubz_WoyM_bIZPL9fS6FySKegraW3oWNWNuRitumJwCD7Vmyn_SZVpv2ph9Qw6T1K5IegvUuIJLKZH7HV2WDBes"; //expired



   

   if (isset($_POST['action'])) {
      $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
      $action = $POST['action'];
      /*
      params.put("code", code);
                    params.put("jwt", jwt);
                    params.put("isCheckingDate",isCheckingDate);
                    params.put("currentDate", format.format(eventDate));

      [customer_id] => 17
                [event_id] => 100
                [event_name] => Event 100
                [event_date] => 09-01-2020
                [code_value] => 04963406
                [guest_id] => 20004
                [guest_name] => Roselyn Giordano
                [guest_phone] => roselyn.giord
                [guest_email] => roselyn.giordano@poncesolutions.com
                [group_count] => 1
                [table_or_row] => 1
                [seat] => 5
                [user_field_01] => User Field 1
                [user_field_02] => User Field 2
                [user_field_03] => User Field 3
                [user_field_04] => User Field 4
                [user_field_05] => User Field 5
                [checked_in_date] => 0
                [has_checked_in] => 0
                [is_in_progress] => 1
      */

      switch($action) {
         case 'updatePaperlessEventCode':
            if (isset($_POST['jwt'])
                 && isset($_POST['code'])
                 && isset($_POST['currentDate'])
                 //&& isset($_POST['isCheckingDate'])
                 ) {
                  $jwt  = $POST["jwt"];
                  $code = $POST["code"];
                  $currentDate = $POST["currentDate"];
                  //$isCheckingDate = $POST["isCheckingDate"];
                  try {
                     $decoded = JWT::decode($jwt, $pubKey, array('RS256'));
                     $decoded_array = (array) $decoded;
                     $email         = $decoded_array["email"];
                     $customerId    = $decoded_array["customerID"];

                     if ($customerId == "cus_00000000000001") {
                        die( '{"status":"failed","message":"The token you submitted is the sample token. You need to purchase a token", "error":400,"token":"' . $jwt . '"}'); 
                     }

                     $guestUpdate = updateGuestProgress($mysql,$code,$currentDate, $customerId);
                     //$guestUpdate = updateGuestProgress($mysql,$code,"09-01-2020", $customerId);
                     //$resultInfo = json_decode($guestResult, true);

                     $guestResult = getGuest($mysql, $code, $currentDate, $customerId);
                     //$guestResult = getGuest($mysql, $code, "09-01-2020", $customerId);
                     $resultInfo = json_decode($guestResult, true);
                     $guests = $resultInfo['guests'];

                     if (count($resultInfo["guests"]) == 0) {
                        //print_r($resultInfo["guests"][0]["code_value"]);
                        die( '{"status":"failed","guests":[],"message":"Code ' . $code . ' with date ' . $currentDate . ' was not found online ", "error":400,"token":"' . $jwt . '"}'); 

                     }

                     if ($resultInfo["guests"][0]["has_checked_in"] > 0) {
                        $guestUpdate = updateGuestProgressDone($mysql,$code,$currentDate, $customerId);
                        die( '{"status":"failed","guests":' . json_encode($guests) . ',"message":"Code ' . $code . ' with date ' . $currentDate . ' is already checked in ", "error":400,"token":"' . $jwt . '"}'); 
                     }

                     $date = new DateTime();
                     $currentDateTime = $date->getTimeStamp();
                     updateGuestCheckedIn($mysql, $code, $currentDate, $customerId, $currentDateTime);
                     //updateGuestCheckedIn($mysql, $code, "09-01-2020", $customerId, $currentDateTime);





                     //print_r($resultInfo["guests"]);

                     
                     die('{"status":"success","guests":' . json_encode($guests) . ',"jwt":"' . $customerId . $code . $currentDate .  '"}');
                  }
                  catch(Exception $e) {
                     //print_r($e);
                     die( '{"status":"failed","message":"' . 'Token could not be verified: ' . $e->getMessage() . '", "error":400,"token":"' . $jwt . '"}');
                  //echo $e;
               
                  }

            } else {
               die( '{"status":"failed","message":"Missing required fields. Please check and try again", "error":102}');
            }
            break;
         default:
         die( '{"status":"failed","message":"Action not defined(' . $action . ')", "error":100}');
            break;

               
      }

      
   } else {
      try {
         $decoded = JWT::decode($jwt, $pubKey, array('RS256'));
         $decoded_array = (array) $decoded;
         $email = $decoded_array["email"];
         die('{"status":"success"}');
      }
      catch(Exception $e) {
         //print_r($e);
         die( '{"status":"failed","message":"' . 'Token could not be verified: ' . $e->getMessage() . '", "error":400,"token":"' . $jwt . '"}');
      //echo $e;
   
      }

   }

   

   

   //print_r($decoded);
   //$decoded_array = (array) $decoded;
   //echo $decoded["email"];
   //print_r($decoded_array);
   //echo 'good';
   //echo '{"status":"success"}';
   
   //$decoded->uuid = "uuui";
   //print_r($decoded);



if (isset($_POST['itemPurchased'])) {

   $itemPurchased = json_decode($_POST["itemPurchased"]);
   if ($itemPurchased != null) {
      //echo 'decoded';
      $result = insertItemPurchased($mysql,$itemPurchased);
      if (strlen($result) > 0) {
         echo $result;   
      }
      
   }
   else {
      //10000 = json format incorrect
      echo '{"result":"error","errorNumber":10000}';
   }
}

if (isset($_POST['itemList'])) {

   $itemList = json_decode($_POST["itemList"]);
   if ($itemList != null) {
      //echo 'decoded';
      $result = getItemList($mysql,$itemList);
      if (strlen($result) > 0) {
         echo $result;   
      }
      
   }
   else {
      //10000 = json format incorrect
      echo '{"result":"error","errorNumber":10000}';
   }
} 
