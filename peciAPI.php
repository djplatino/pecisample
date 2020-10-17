<?php
include 'cgi-bin/config.php';
include 'includes/databaseFunctions.php';

if (isset($_POST['action'])) {
   $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
   $action = $POST['action'];

   //Evaluation the type of actions allowed
   switch($action) {
      case 'updatePaperlessEventCode':
         //The Paperless Event Checking In app sents three variables.
         //For your own server you only need to receive the following two.
         if (isset($_POST['code']) && isset($_POST['currentDate'])) {
            $code = $POST["code"];
            $currentDate = $POST["currentDate"];

            //The return here is ignored
            $guestUpdate = updateGuestProgress($mysql,$code,$currentDate);
            
            
            $guestResult = getGuest($mysql, $code, $currentDate);

            //We do not want to return the result just yet.
            //We decode the json that was sent from the database
            $resultInfo = json_decode($guestResult, true);

            //Extract the guest array from the decoded JSON
            $guests = $resultInfo['guests'];

            if (count($resultInfo["guests"]) == 0) {
               //You can decide whether to return success or failed
               die( '{"status":"failed","guests":[],"message":"Code ' . $code . ' with date ' . $currentDate . ' was not found online ", "error":"10002"}'); 
            }

            if ($resultInfo["guests"][0]["has_checked_in"] > 0) {
               //Release the working on progress so that other request can work on it
               $guestUpdate = updateGuestProgressDone($mysql,$code,$currentDate);
               die( '{"status":"failed","guests":' . json_encode($guests) . ',"message":"Code ' . $code . ' with date ' . $currentDate . ' is already checked in ", "error":"10002"}'); 
            }

            $date = new DateTime();
            $currentDateTime = $date->getTimeStamp();
            updateGuestCheckedIn($mysql, $code, $currentDate, $currentDateTime);

            
            //We return the result this wait so that it stops here
            die('{"status":"success","guests":' . json_encode($guests) .'}');







            //echo $guestResult;

         } else {
            die( '{"status":"failed","message":"Fields code and currentDate are required.", "error":"004"}');
         }
         break;
      default:
         die( '{"status":"failed","message":"Action not defined(' . $action . ')", "error":"003"}');
         break;


   }


} else {
   //Return JSON message
   die( '{"status":"failed","message":"action needs to be defined in the POST request", "error":"002"}'); 
}