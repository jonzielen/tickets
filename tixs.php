<?php
$email['to'] = 'jonzielen@gmail.com';
$email['subject'] = 'Stephen Colbert Tickets';
$email['headers'] = "From: jon@zielenkievicz.com"."\r\n";
$email['headers'] .= "Reply-To: jon@zielenkievicz.com"."\r\n";
$email['headers'] .= "MIME-Version: 1.0\r\n";
$email['headers'] .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$email['message'] = $email['subject'].' for {date} are available now!<br /> <a href="http://www.showclix.com/event/TheLateShowwithStephenColb604314">http://www.showclix.com/event/TheLateShowwithStephenColb604314</a>.';

function checkTickets($email) {
  $json = file_get_contents('http://www.showclix.com/event/TheLateShowwithStephenColb604314/recurring-event-times');
  $obj = json_decode($json);

  for ($i=0; $i < count($obj->times); $i++) {
    if ($obj->times[$i]->event_status != 'sold_out' && $obj->times[$i]->time != '2015-08-27') {
      $email['message'] = str_replace('{date}', $obj->times[$i]->time, $email['message']);
      mail($email['to'], $email['subject'], $email['message'], $email['headers']);
    }
  }
}

checkTickets($email);
?>
