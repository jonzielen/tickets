<?php

namespace jz;

class Tixs {
  protected $jsonAvailableDates = array();
  protected $storedDateList;

  protected $emailMessage;

  protected $settings;
  protected $showsList;
  protected $showsDetails;
  protected $storedDateListPath;

  public function __construct($showsInfo) {
      // loop through each ticket seller, get their settings
      foreach ($showsInfo as $ticketSeller => $ticketSellerOptions) {
          // get ticket seller settings
          $this->settings = $ticketSellerOptions['settings'];

          // get shows to check for, and their info
          $this->showsList = $ticketSellerOptions['shows'];

          // foreach show from this ticket seller
          foreach ($this->showsList as $show => $showInfo) {
            unset($this->storedDateList);
            unset($this->emailMessage);
            $this->showsDetails = $showInfo;
            $this->storedDateListPath = $showInfo['datesFile'];

            self::loadJsonFile($showInfo, $this->storedDateListPath);
          }
      }
  }

  protected function loadJsonFile($showInfo, $filePath) {
    $this->storedDateList = self::loadDatesFile($filePath);
    $jsonFileContent = json_decode(file_get_contents($showInfo['url']));
    self::jsonAvailableDates($jsonFileContent);
  }

  protected function loadDatesFile($file) {
    if (file_exists($file)) {
      return file($file, FILE_IGNORE_NEW_LINES);
  } else {
      return array();
    }
  }

  protected function jsonAvailableDates($jsonDates) {
    unset($this->jsonAvailableDates);

    for ($i = 0; $i < count($jsonDates->times); $i++) {
      if ($jsonDates->times[$i]->event_status != $this->settings['sold_out']) {
        foreach ($jsonDates->times[$i] as $key => $value) {
          $this->jsonAvailableDates[$jsonDates->times[$i]->time][$key] = $value;
        }
      }
    }

    $availabilityChange = self::checkAvailabilityChange();

    if ((!empty($this->jsonAvailableDates) && !empty($this->storedDateList)) || $availabilityChange) {
      self::sortDateStatus();
    }
  }

  protected function checkAvailabilityChange() {
    // get json keys (dates) as own array
    $jasonDateKeyValue = array_keys($this->jsonAvailableDates);
    $diffOne = array_diff($jasonDateKeyValue, $this->storedDateList);
    $diffTwo = array_diff($this->storedDateList, $jasonDateKeyValue);
    $diff = array_merge($diffOne, $diffTwo);

    if (empty($diff)) {
      return false;
    } else {
      return true;
    }
  }

  protected function sortDateStatus() {
    unset($dateInfo);

    // get json keys (dates) as own array
    $jasonDateKeyValue = array_keys($this->jsonAvailableDates);

    // reoccurring date
    foreach ($jasonDateKeyValue as $keyDate) {
      if (in_array($keyDate, $this->storedDateList)) {
        $dateInfo['reoccurring'][] = $keyDate;
      } else {
        $dateInfo['new'][] = $keyDate;
      }
    }

    // sold out
    foreach ($this->storedDateList as $storedDate) {
      if (!in_array($storedDate, $jasonDateKeyValue)) {
        $dateInfo['soldOut'][] = $storedDate;
      }
    }

    // add new dates to stored file
    if (!empty($dateInfo['new'])) {
      foreach ($dateInfo['new'] as $key => $newDate) {
        self::addDateToFile($newDate);
      }
    }

    // remove dates from stored file
    if (!empty($dateInfo['soldOut'])) {
      foreach ($dateInfo['soldOut'] as $key => $soldOutDate) {
        self::deleteDate($soldOutDate);
      }
    }

    self::compileEmailMessage();
  }

  protected function deleteDate($soldOutDates) {
    $fileDates = file_get_contents($this->storedDateListPath);
    $fileDates = str_replace($soldOutDates."\n", '', $fileDates);
    file_put_contents($this->storedDateListPath, $fileDates);
  }

  protected function addDateToFile($newDates) {
    $fileDates = fopen($this->storedDateListPath, 'a+');
    fwrite($fileDates, $newDates."\n");
    fclose($fileDates);
  }

  protected function compileEmailMessage() {
    $message = '';
    $soldOutMessage = '';

    if (!empty($this->jsonAvailableDates)) {
      $message .= 'The following {date_count} available:<br />';
      $dateCount = 0;

      // add available dates with link to email message
      foreach ($this->jsonAvailableDates as $date) {
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
      if (!empty($this->jsonAvailableDates)) {
        $soldOutMessage .= '<br />';
      }

      $soldOutMessage .= 'The following {soldout_count} sold out:<br />';
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

    $this->emailMessage = $message.$soldOutMessage;
  }

  protected function emailDateLinksTpl($date) {
    $tpl = '<a href="http://www.showclix.com{uri}">{time}</a><br />';

   foreach ($date as $key => $val) {
     $tpl = str_replace("{".$key."}", $val, $tpl);
   }

   echo '<pre>';
   print_r($tpl);
   //print_r($showsInfo);
   echo '</pre>';

   return $tpl;
  }

  public function emailMessage() {
    $email = [
        'details' => $this->showsDetails,
        'message' => $this->emailMessage
    ];
    return $email;
  }
}
