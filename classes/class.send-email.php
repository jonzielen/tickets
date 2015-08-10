<?php

namespace jon;

class SendEmail {

  public function __construct($emailInfo) {
    foreach ($emailInfo as $email) {
        $email['to'] = $email['emailTo'];
        $email['subject'] = $email['showName'].' Tickets';
        $email['message'] = $email['emailMessage'];
        self::compileEmail($email);
    }
  }

  protected function compileEmail($email) {
    require_once 'assets/email-info.php';
    $addresses = explode(',', $email['to']);

    foreach ($addresses as $address) {
      mail($address, $email['subject'], $email['message'], $email['headers']);
    }
  }
}
