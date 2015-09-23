<?php
    // ENV variables, since cron cant get server info
    function getHost() {
        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != '') {
            return $_SERVER['HTTP_HOST'];
        } else {
            return  '';
        }
    }

    $host = getHost();

    switch ($host) {
        case 'tickets.dev':
            $GLOBALS['path'] = $_SERVER['HTTP_HOST'];
            break;

        case '':
        default:
            $GLOBALS['path'] = 'tickets.zielenkievicz.com';
            break;
    }

    $jonsEmail = 'jonzielen@gmail.com';

    function datesFileName($showName) {
        return 'assets/dates/'.str_replace(" ", "-", strtolower ($showName)).'.txt';
    }

    function addPathForEmailImages($imgName) {
        $serverPath = 'http://'.$GLOBALS['path'].'/assets/img/'.$imgName;
        return $serverPath;
    }

    function addPathForEmailTemplate($tplName) {
        $serverPath = 'http://'.$GLOBALS['path'].'/assets/tempates/'.$tplName;
        return $serverPath;
    }

    $showsInfo = [
        'showclix' => [
            'settings' => [
                'status' => [
                    'on_sale' => 'on_sale',
                    'pre_sale' => 'pre_sale',
                    'post_sale' => 'post_sale',
                    'sold_out' => 'sold_out'
                ],
                'errors' => [
                    'errorLogFile' => 'error_log',
                    'email' => [
                        'template' => addPathForEmailTemplate('error-tpl.php'),
                        'subject' => 'Tickets Error',
                        'emailTo' => $jonsEmail,
                    ]
                ]
            ],
            'shows' => [
                'dailyShow' => [
                    'tickets' => 'http://www.showclix.com/event/TheDailyShowWithTrevorNoah/recurring-event-times',
                    'url' => 'https://www.showclix.com/event/TheDailyShowWithTrevorNoah',
                    'showName' => ($showName = 'The Daily Show with Trevor Noah'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName),
                    'email' => [
                        'headerImage' => addPathForEmailImages('the-daily-show-with-trevor-noah.jpg'),
                        'template' => addPathForEmailTemplate('basic-tpl.php')
                    ]
                ],
                'stephenColbert' => [
                    'tickets' => 'http://www.showclix.com/event/TheLateShowWithStephenColbert/recurring-event-times',
                    'url' => 'http://www.showclix.com/event/TheLateShowWithStephenColbert',
                    'showName' => ($showName = 'The Late Show with Stephen Colbert'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName),
                    'email' => [
                        'headerImage' => addPathForEmailImages('the-late-show-with-stephen-colbert.jpg'),
                        'template' => addPathForEmailTemplate('basic-tpl.php')
                    ]
                ]
            ]
        ]
    ];
?>
