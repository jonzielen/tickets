<?php

namespace jon;

class SendEmail {

  public function __construct($emailInfo) {
      foreach ($emailInfo as $email) {
          $method = 'format'.self::getTemplateFile($email['template']);
      }

      self::$method($emailInfo);
  }

  protected function formatErrorTpl($emailInfo) {
      $email = $emailInfo[0];

      function addErrorEmailTpl($email) {
        // load tempate
        $file = file_get_contents($email['template']);
        $file = str_replace('{message}', $email['message'], $file);

        return $file;
      }

      $email['to'] = $email['emailTo'];
      $email['template'] = addErrorEmailTpl($email);

      self::compileEmail($email);
  }

  protected function getTemplateFile($file) {
      $filename = explode('-',reset(explode('.', end(explode('/', $file)))));

      for ($i=0; $i < count($filename); $i++) {
          $filename[$i] = ucwords($filename[$i]);
      }

      return join($filename);
  }

  protected function formatBasicTpl($emailInfo) {
    function addEmailTpl($email) {
      // load tempate
      $file = file_get_contents($email['template']);
      $file = str_replace('{url}', $email['url'], $file);
      $file = str_replace('{headerImage}', $email['headerImage'], $file);
      $file = str_replace('{headerImageAlt}', $email['emailAlt'], $file);
      $file = str_replace('{message}', $email['message'], $file);

      return $file;
    }

    foreach ($emailInfo as $email) {
      $email['to'] = $email['emailTo'];
      $email['emailAlt'] = $email['showName'];
      $email['subject'] = $email['showName'].' Tickets - '.date("l, F j, Y");
      $email['message'] = $email['message'];
      $email['url'] = $email['showUrl'];
      $email['template'] = addEmailTpl($email);
    }

    self::compileEmail($email);
  }

  protected function compileEmail($email) {
    require 'assets/email-info.php';
    $addresses = explode(',', $email['to']);

    foreach ($addresses as $address) {
      mail($address, $email['subject'], $email['template'], $email['headers']);
    }
  }
}
