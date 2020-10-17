<?php

function deleteGuests(&$mysql)
{
    $deleteSQL = "DELETE FROM guest";
    //echo $deleteSQL;
    if (!mysqli_query($mysql, $deleteSQL)) {
        die('{"status":"failed","message":"Database error - deleteGuests","error":203}');
    }
    //mysql_query($mysql, $deleteSQL);
    $deleteFromGuest = mysqli_affected_rows($mysql);
    
    return '{"status":"success"
        ,"itemsDeleted":' . $deleteFromGuest . '}';

}

function getGuest(&$mysql, $code, $date, $customerId)
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
            AND   customer_id = " . $customerId . "
            AND   event_date = '" . $date . "'";
    //echo $sql;
    mysqli_query($mysql, 'SET NAMES utf8');
    $result = mysqli_query($mysql, $sql);
    $guestArray = array();
    while ($guest = mysqli_fetch_object($result)) {
               
        $guestArray[] = $guest ;
    }
    
    return '{"status":"success"
            ,"guests":' . json_encode($guestArray) . '}';

}

function insertGuest(&$mysql, $itemObj)
{
    $sqlInsert = "INSERT IGNORE INTO guest (customer_id
                                           ,event_id
                                           ,event_name
                                           ,event_date
                                           ,code_value
                                           ,guest_id
                                           ,guest_name
                                           ,guest_phone
                                           ,guest_email
                                           ,group_count
                                           ,table_or_row
                                           ,seat
                                           ,user_field_01
                                           ,user_field_02
                                           ,user_field_03
                                           ,user_field_04
                                           ,user_field_05)
                      VALUES(" . $itemObj->customerId . ","
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->eventId ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->eventName ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->eventDate ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->codeValue ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->guestId ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->guestName ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->guestPhone ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->guestEmail ) . "',"
                                . $itemObj->groupCount . ","
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->tableRow ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->seat ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->userField01 ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->userField02 ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->userField03 ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->userField04 ) . "',"
                                . "'" . mysqli_real_escape_string($mysql, $itemObj->userField05 ) . "')";
    //echo $sqlInsert;
    if (!mysqli_query($mysql, $sqlInsert)) {
        die('{"status":"failed","message":"Database error on insertItemMaster","error":203}');
    }
    $idCreated = mysqli_insert_id($mysql);
    $jsonString = '{"status":"success","id":' . $idCreated . '}';
    return $jsonString;

}

function insertGuests($invArray
                              ,&$mysql)
{
    $sqlInsert = "INSERT IGNORE INTO guest (customer_id
                                            ,event_id
                                            ,event_name
                                            ,event_date
                                            ,code_vale
                                            ,guest_id
                                            ,guest_name
                                            ,guest_phone
                                            ,guest_email
                                            ,group_count
                                            ,table_or_row
                                            ,seat
                                            ,user_field_01
                                            ,user_field_02
                                            ,user_field_03
                                            ,user_field_04
                                            ,user_field_05)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    echo $sqlInsert;
    $rowCount = 0;
    if($stmt = mysqli_prepare($mysql, $sqlInsert)){
        mysqli_stmt_bind_param($stmt, "issssssssisssssss" 
                                ,$customer_id
                                ,$event_id
                                ,$event_name
                                ,$event_date
                                ,$code_value
                                ,$guest_id
                                ,$guest_name
                                ,$guest_phone
                                ,$guest_email
                                ,$group_code
                                ,$table_or_row
                                ,$seat
                                ,$user_field_01
                                ,$user_field_02
                                ,$user_field_03
                                ,$user_field_04
                                ,$user_field_05);
        
        foreach ($invArray as $item) {
            $customer_id   = $itemObj->customerId;
            $event_id      = $itemObj->eventId;
            $event_name    = $itemObj->eventName;
            $event_date    = $itemObj->eventDate;
            $code_value    = $itemObj->codeValue;
            $guest_id      = $itemObj->guestId;
            $guest_name    = $itemObj->guestName;
            $guest_phone   = $itemObj->guestPhone;
            $guest_email   = $itemObj->guestEmail;
            $group_count   = $itemObj->groupCount;
            $table_or_row  = $itemObj->tableRow;
            $seat          = $itemObj->seat;
            $user_field_01 = $itemObj->userField01;
            $user_field_02 = $itemObj->userField02;
            $user_field_03 = $itemObj->userField03;
            $user_field_04 = $itemObj->userField04;
            $user_field_05 = $itemObj->userField05;

           
            mysqli_stmt_execute($stmt);
            
            $rowCount = $rowCount + 1;
        }

    }
    mysqli_stmt_close($stmt);
    
    return '{"status":"success","rowsInserted":'. $rowCount .'}';

}

function updateGuestCheckedIn(&$mysql, $code, $date, $customerId, $dateTime) {
    $sqlUpdate = "UPDATE guest 
                  SET is_in_progress = 0,
                      has_checked_in = 1,
                      checked_in_date  = $dateTime
                  WHERE code_value     = '" . $code . "'
                  AND   customer_id    = " . $customerId . " 
                  AND   event_date     = '" . $date . "'
                  AND   is_in_progress = 1";
    //echo $sqlUpdate;
    if (!mysqli_query($mysql, $sqlUpdate)) {
        die('{"status":"failed","message":"Database error on updateGuestCheckedIn update ","error":203}');
    }
    $jsonString = '{"status":"success","message"}';
    return $jsonString;

}

function updateGuestProgress(&$mysql, $code, $date, $customerId) {
    $sqlUpdate = "UPDATE guest 
                  SET is_in_progress = 1
                  WHERE code_value     = '" . $code . "'
                  AND   customer_id    = " . $customerId . " 
                  AND   event_date     = '" . $date . "'
                  AND   is_in_progress = 0";
    //echo $sqlUpdate;
    if (!mysqli_query($mysql, $sqlUpdate)) {
        die('{"status":"failed","message":"Database error on updateGuestProgress update ","error":203}');
    }
    $jsonString = '{"status":"success","message"}';
    return $jsonString;

}

function updateGuestProgressDone(&$mysql, $code, $date, $customerId) {
    $sqlUpdate = "UPDATE guest 
                  SET is_in_progress = 0
                  WHERE code_value     = '" . $code . "'
                  AND   customer_id    = " . $customerId . " 
                  AND   event_date     = '" . $date . "'
                  AND   is_in_progress = 1";
    //echo $sqlUpdate;
    if (!mysqli_query($mysql, $sqlUpdate)) {
        die('{"status":"failed","message":"Database error on updateGuestProgress update ","error":203}');
    }
    $jsonString = '{"status":"success","message"}';
    return $jsonString;

}