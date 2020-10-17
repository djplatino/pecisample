<?php


//This function returns the guest will all the fields available
function getGuest(&$mysql, $code, $date)
{
    $sql = "SELECT customer_id,
                   event_id,
                   event_name,
                   event_date,
                   code_value,
                   guest_id,
                   guest_name,
                   guest_phone,
                   guest_email,
                   group_count,
                   table_or_row,
                   seat,
                   user_field_01,
                   user_field_02,
                   user_field_03,
                   user_field_04,
                   user_field_05,
                   checked_in_date,
                   has_checked_in,
                   is_in_progress
            FROM  guest
            WHERE code_value = '" . $code . "'
            AND   event_date = '" . $date . "'";
    mysqli_query($mysql, 'SET NAMES utf8');
    $result = mysqli_query($mysql, $sql);
    $guestArray = array();
    while ($guest = mysqli_fetch_object($result)) {
               
        $guestArray[] = $guest ;
    }
    
    return '{"status":"success"
            ,"guests":' . json_encode($guestArray) . '}';

}

function updateGuestCheckedIn(&$mysql, $code, $date, $dateTime) {
    $sqlUpdate = "UPDATE guest 
                  SET is_in_progress = 0,
                      has_checked_in = 1,
                      checked_in_date  = $dateTime
                  WHERE code_value     = '" . $code . "'
                  AND   event_date     = '" . $date . "'
                  AND   is_in_progress = 1";
    //echo $sqlUpdate;
    if (!mysqli_query($mysql, $sqlUpdate)) {
        die('{"status":"failed","message":"Database error on updateGuestCheckedIn update ","error":"1001"}');
    }
    $jsonString = '{"status":"success","message"}';
    return $jsonString;

}


//This functions updates the status of the guest to make sure
//that only one request can modify it.
function updateGuestProgress(&$mysql, $code, $date) {
    $sqlUpdate = "UPDATE guest 
                  SET is_in_progress = 1
                  WHERE code_value     = '" . $code . "'
                  AND   event_date     = '" . $date . "'
                  AND   is_in_progress = 0";
    //echo $sqlUpdate;
    if (!mysqli_query($mysql, $sqlUpdate)) {
        die('{"status":"failed","message":"Database error on updateGuestProgress update ","error":"1001"}');
    }
    $jsonString = '{"status":"success"}';
    return $jsonString;

}

//This function releases the guest so that other requests
//can work on it
function updateGuestProgressDone(&$mysql, $code, $date) {
    $sqlUpdate = "UPDATE guest 
                  SET is_in_progress = 0
                  WHERE code_value     = '" . $code . "'
                  AND   event_date     = '" . $date . "'
                  AND   is_in_progress = 1";
    if (!mysqli_query($mysql, $sqlUpdate)) {
        die('{"status":"failed","message":"Database error on updateGuestProgressDone update ","error":"1001"}');
    }
    $jsonString = '{"status":"success","message"}';
    return $jsonString;

}