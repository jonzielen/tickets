<?php

namespace jon;

class SendEmail {

  public function __construct($emailInfo) {
    $email['to'] = $emailInfo['details']['emailTo'];
    $email['subject'] = $emailInfo['details']['showName'].' Tickets';
    $email['message'] = $emailInfo['message'];
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
