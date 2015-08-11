<?php

namespace jon;

class SendEmail {

  public function __construct($emailInfo) {
    foreach ($emailInfo as $email) {
        $email['to'] = $email['emailTo'];
        $email['emailAlt'] = $email['showName'];
        $email['subject'] = $email['showName'].' Tickets - '.date("l, F j, Y");
        $email['message'] = $email['emailMessage'];
        $email['url'] = $email['showUrl'];
        $email['template'] = self::addEmailTpl($email);
        self::compileEmail($email);
    }
  }

  protected function addEmailTpl($email) {
      $file = file_get_contents($email['emailTemplate']);

      // load tempate
      $file = str_replace('{url}', $email['url'], $file);
      $file = str_replace('{headerImage}', $email['emailHeaderImage'], $file);
      $file = str_replace('{headerImageAlt}', $email['emailAlt'], $file);
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
