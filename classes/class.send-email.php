<?php

namespace jon;

class SendEmail {

  public function __construct($emailInfo) {


    echo '<pre>';
    print_r($emailInfo);
    //print_r($showsInfo);
    echo '</pre>';
    die();


    $email['to'] = $emailInfo['emailTo'];
    $email['subject'] = $emailInfo['showName'].' Tickets';
    $email['message'] = $emailInfo['emailMessage'];
    self::compileEmail($email);
  }

  protected function compileEmail($email) {
    require_once 'assets/email-info.php';
    $addresses = explode(',', $email['to']);

    foreach ($addresses as $address) {
      mail($address, $email['subject'], $email['message'], $email['headers']);
    }
  }
}
