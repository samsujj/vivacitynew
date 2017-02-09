<?php

global $AI;



$userId = $AI->user->userID;
$emailid = $AI->user->email;

if($AI->user->account_type == 'Distributor'){
    $res12 = db_query("SELECT * FROM `users` WHERE `userID` = ".$userId);

    while($res12 && $row12 = db_fetch_assoc($res12)) {
        $accept_term = $row12['accept_term'];
    }

    if($accept_term == 0){
        util_redirect('distributor-dashboard');
    }

}

?>

