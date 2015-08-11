<?php

namespace jon;

class SendEmail {

  public function __construct($emailInfo) {
    foreach ($emailInfo as $email) {
        $email['to'] = $email['emailTo'];
        $email['subject'] = $email['showName'].' Tickets';
        $email['message'] = $email['emailMessage'];
        $email['template'] = self::addEmailTpl($email);
        self::compileEmail($email);
    }
  }

  protected function addEmailTpl($email) {
      $file = file_get_contents($email['emailTemplate']);

      // load tempate
      $file = str_replace('{headerImage}', $email['emailHeaderImage'], $file);
      $file = str_replace('{message}', $email['message'], $file);

      return $file;
  }

  protected function compileEmail($email) {
    require 'assets/email-info.php';
    $addresses = explode(',', $email['to']);

    foreach ($addresses as $address) {
      mail($address, $email['subject'], $email['template'], $email['headers']);
    }
  }
}
