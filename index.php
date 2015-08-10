<?php
    // load shows to check for
    require_once 'shows-info.php';

    // load tickets class
    require_once 'classes/class.tixs.php';
    $tickets = new jz\Tixs($showsInfo);

    $emailDetails = $tickets->emailMessage();

    if (!empty($emailDetails)) {
        require_once 'classes/class.send-email.php';
        $email = new jon\SendEmail($emailDetails);
    }


  // echo '<pre>';
  // print_r($tickets);
  // print_r($showsInfo);
  // echo '</pre>';
?>
