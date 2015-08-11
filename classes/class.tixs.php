<?php

namespace jz;

class Tixs {
  protected $email = [];
  protected $settings;
  protected $showsList;

  public function __construct($showsInfo) {
      // loop through each ticket seller, get their settings
      foreach ($showsInfo as $ticketSeller => $ticketSellerOptions) {
          // get ticket seller settings
          $this->settings = $ticketSellerOptions['settings'];

          // get shows to check for, and their info
          $this->showsList = $ticketSellerOptions['shows'];

          // foreach show from this ticket seller
          foreach ($this->showsList as $show => $showInfo) {
            self::loadJsonFile($showInfo);
          }
      }
  }

  protected function loadJsonFile($showInfo) {
    $showInfo['storedDateList'] = self::loadDatesFile($showInfo['datesFile']);
    $showInfo['jsonFileContent'] = json_decode(file_get_contents($showInfo['tickets']));

    self::jsonAvailableDates($showInfo);
  }

  protected function loadDatesFile($file) {
    if (file_exists($file)) {
      return file($file, FILE_IGNORE_NEW_LINES);
  } else {
      return array();
    }
  }

  protected function jsonAvailableDates($showInfo) {
    $showInfo['jsonAvailableDates'] = [];
    for ($i = 0; $i < count($showInfo['jsonFileContent']->times); $i++) {
      if ($showInfo['jsonFileContent']->times[$i]->event_status != $this->settings['sold_out']) {
        foreach ($showInfo['jsonFileContent']->times[$i] as $key => $value) {
          $showInfo['jsonAvailableDates'][$showInfo['jsonFileContent']->times[$i]->time][$key] = $value;
          $showInfo['jsonAvailableDates'][$showInfo['jsonFileContent']->times[$i]->time]['new_time'] = date("l, F j, Y", strtotime($showInfo['jsonFileContent']->times[$i]->time));
        }
      }
    }

    $availabilityChange = self::checkAvailabilityChange($showInfo);

    if ((empty($showInfo['jsonAvailableDates']) && empty($showInfo['storedDateList'])) || $availabilityChange) {
      //die();
      self::sortDateStatus($showInfo);
    } else {
      self::sortDateStatus($showInfo);
    }
  }

  protected function checkAvailabilityChange($showInfo) {
    // get json keys (dates) as own array
    $jasonDateKeyValue = array_keys($showInfo['jsonAvailableDates']);
    $diffOne = array_diff($jasonDateKeyValue, $showInfo['storedDateList']);
    $diffTwo = array_diff($showInfo['storedDateList'], $jasonDateKeyValue);
    $diff = array_merge($diffOne, $diffTwo);

    if (empty($diff)) {
      return true;
    } else {
      return false;
    }
  }

  protected function sortDateStatus($showInfo) {
    // get json keys (dates) as own array
    $jasonDateKeyValue = array_keys($showInfo['jsonAvailableDates']);

    // reoccurring date
    foreach ($jasonDateKeyValue as $keyDate) {
      if (in_array($keyDate, $showInfo['storedDateList'])) {
        $dateInfo['reoccurring'][] = $keyDate;
      } else {
        $dateInfo['new'][] = $keyDate;
      }
    }

    // sold out
    foreach ($showInfo['storedDateList'] as $storedDate) {
      if (!in_array($storedDate, $jasonDateKeyValue)) {
        $dateInfo['soldOut'][] = $storedDate;
      }
    }

    // add new dates to stored file
    if (!empty($dateInfo['new'])) {
      foreach ($dateInfo['new'] as $key => $newDate) {
        self::addDateToFile($newDate, $showInfo);
      }
    }

    // remove dates from stored file
    if (!empty($dateInfo['soldOut'])) {
      foreach ($dateInfo['soldOut'] as $key => $soldOutDate) {
        self::deleteDate($soldOutDate, $showInfo);
      }
    }

    self::compileEmailMessage($showInfo);
  }

  protected function deleteDate($soldOutDates, $showInfo) {
    $fileDates = file_get_contents($showInfo['datesFile']);
    $fileDates = str_replace($soldOutDates."\n", '', $fileDates);
    file_put_contents($showInfo['datesFile'], $fileDates);
  }

  protected function addDateToFile($newDates, $showInfo) {
    $fileDates = fopen($showInfo['datesFile'], 'a+');
    fwrite($fileDates, $newDates."\n");
    fclose($fileDates);
  }

  protected function compileEmailMessage($showInfo) {
    $message = '';
    $soldOutMessage = '';

    if (!empty($showInfo['jsonAvailableDates'])) {
      $message .= 'The following <strong>'.$showInfo['showName'].'</strong> {date_count} available:<br /><br />';
      $dateCount = 0;

      // add available dates with link to email message
      foreach ($showInfo['jsonAvailableDates'] as $date) {
        $message .= self::emailDateLinksTpl($date);
        $dateCount = $dateCount+1;
      }

      if ($dateCount <= 1) {
        $message = str_replace('{date_count}', 'date is', $message);
      } else {
        $message = str_replace('{date_count}', 'dates are', $message);
      }
    }

    if (!empty($dateInfo['soldOut'])) {
      if (!empty($showInfo['jsonAvailableDates'])) {
        $soldOutMessage .= '<br />';
      }

      $soldOutMessage .= 'The following <strong>'.$showInfo['showName'].'</strong> {soldout_count} sold out:<br /><br />';
      $soldoutCount = 0;

      // add sold out dates to email message
      foreach ($dateInfo['soldOut'] as $soldOutDate) {
        $soldOutMessage .= $soldOutDate.'<br />';
        $soldoutCount = $soldoutCount+1;
      }

      if ($soldoutCount <= 1) {
        $soldOutMessage = str_replace('{soldout_count}', 'date is', $soldOutMessage);
      } else {
        $soldOutMessage = str_replace('{soldout_count}', 'dates have', $soldOutMessage);
      }
    }

    $showInfo['emailMessage'] = $message.$soldOutMessage;

    // check if there is any message
    if ($showInfo['emailMessage'] != '') {
        // collect info for the email
        $this->email[] = [
            'showName' => $showInfo['showName'],
            'showUrl' => $showInfo['url'],
            'emailTo' => $showInfo['emailTo'],
            'emailMessage' => $showInfo['emailMessage'],
            'emailHeaderImage' => $showInfo['email']['headerImage'],
            'emailTemplate' => $showInfo['email']['template']
        ];
    }
  }

  protected function emailDateLinksTpl($date) {
    $tpl = '<a href="http://www.showclix.com{uri}" style="text-decoration:none;font-weight:bold;">{new_time}</a><br />';

   foreach ($date as $key => $val) {
     $tpl = str_replace("{".$key."}", $val, $tpl);
   }

   return $tpl;
  }

  public function emailMessage() {
    $email = $this->email;
    return $email;
  }
}
