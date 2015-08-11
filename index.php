<?php
    // load shows to check for
    require_once 'assets/shows-info.php';

    // load tickets class
    require_once 'classes/class.tixs.php';
    $tickets = new jz\Tixs($showsInfo);

    $emailDetails = $tickets->emailMessage();

    if (!empty($emailDetails)) {
        require_once 'classes/class.send-email.php';
        $email = new jon\SendEmail($emailDetails);
    }
?>
