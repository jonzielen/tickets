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
        'vendors' => [
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
                    ],
                    'baseUrl' => 'http://www.showclix.com'
                ],
                'shows' => [
                    'jimmyFallon' => [
                        'tickets' => 'http://www.showclix.com/event/TheTonightShowStarringJimmyFallon/recurring-event-times',
                        //'tickets' => 'assets/js/jf-recurring-event-times.json',
                        'url' => 'https://www.showclix.com/event/latenightseth',
                        'showName' => ($showName = 'The Tonight Show Starring Jimmy Fallon'),
                        'emailTo' => $jonsEmail,
                        'datesFile' => datesFileName($showName),
                        'email' => [
                            'headerImage' => addPathForEmailImages('the-tonight-show-starring-jimmy-fallon.jpg'),
                            'template' => addPathForEmailTemplate('basic-tpl.php')
                        ]
                    ],
                    'stephenColbert' => [
                        'tickets' => 'http://www.showclix.com/event/TheLateShowWithStephenColbert/recurring-event-times',
                        //'tickets' => 'assets/js/sc-recurring-event-times.json',
                        'url' => 'http://www.showclix.com/event/TheLateShowWithStephenColbert',
                        'showName' => ($showName = 'The Late Show with Stephen Colbert'),
                        'emailTo' => $jonsEmail,
                        'datesFile' => datesFileName($showName),
                        'email' => [
                            'headerImage' => addPathForEmailImages('the-late-show-with-stephen-colbert.jpg'),
                            'template' => addPathForEmailTemplate('basic-tpl.php')
                        ]
                    ],
                    'sethMeyers' => [
                        'tickets' => 'http://www.showclix.com/event/latenightseth/recurring-event-times',
                        //'tickets' => 'assets/js/sm-recurring-event-times.json',
                        'url' => 'https://www.showclix.com/event/latenightseth',
                        'showName' => ($showName = 'Late Night with Seth Meyers'),
                        'emailTo' => $jonsEmail,
                        'datesFile' => datesFileName($showName),
                        'email' => [
                            'headerImage' => addPathForEmailImages('late-night-with-seth-meyers.jpg'),
                            'template' => addPathForEmailTemplate('basic-tpl.php')
                        ]
                    ]
                ]
            ],
            '1iota' => [
                'settings' => [
                    'status' => [
                        'on_sale' => 'btn btn-action',
                        'pre_sale' => '',
                        'post_sale' => '',
                        'sold_out' => 'btn btn-soldOut'
                    ],
                    'errors' => [
                        'errorLogFile' => 'error_log',
                        'email' => [
                            'template' => addPathForEmailTemplate('error-tpl.php'),
                            'subject' => 'Tickets Error',
                            'emailTo' => $jonsEmail,
                        ]
                    ],
                    'baseUrl' => 'http://1iota.com'
                ],
                'shows'=> [
                    'jimmyFallon' => [
                        //'tickets' => 'https://s3-us-west-1.amazonaws.com/data.1iota.com/project/353/details/data.json',
                        'tickets' => 'assets/js/jf-recurring-event-times.json',
                        'url' => 'https://fallon.1iota.com/show/353/The-Tonight-Show-Starring-Jimmy-Fallon',
                        'showName' => ($showName = 'The Tonight Show Starring Jimmy Fallon'),
                        'emailTo' => $jonsEmail,
                        'datesFile' => datesFileName($showName),
                        'email' => [
                            'headerImage' => addPathForEmailImages('the-tonight-show-starring-jimmy-fallon.jpg'),
                            'template' => addPathForEmailTemplate('basic-tpl.php')
                        ]
                    ],
                    'sethMeyers' => [
                        'tickets' => 'http://s3-us-west-1.amazonaws.com/data.1iota.com/project/461/details/data.json',
                        //'tickets' => 'assets/js/sm-recurring-event-times.json',
                        'url' => 'http://1iota.com/Show/461/Late-Night-with-Seth-Meyers',
                        'showName' => ($showName = 'The Tonight Show Starring Jimmy Fallon'),
                        'emailTo' => $jonsEmail,
                        'datesFile' => datesFileName($showName),
                        'email' => [
                            'headerImage' => addPathForEmailImages('the-tonight-show-starring-jimmy-fallon.jpg'),
                            'template' => addPathForEmailTemplate('basic-tpl.php')
                        ]
                    ]
                ]
            ]
        ]
    ];
