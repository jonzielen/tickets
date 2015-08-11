<?php
    $jonsEmail = 'jonzielen@gmail.com';

    function datesFileName($showName) {
        return 'assets/'.str_replace(" ", "-", strtolower ($showName)).'.txt';
    }

    function addPathForEmailImages($imgName) {
        $serverPath = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'assets/img/'.$imgName;
        return $serverPath;
    }

    function addPathForEmailTemplate($tplName) {
        $serverPath = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'assets/tempates/'.$tplName;
        return $serverPath;
    }

    $showsInfo = [
        'showclix' => [
            'settings' => [
                'on_sale' => 'on_sale',
                'pre_sale' => 'pre_sale',
                'post_sale' => 'post_sale',
                'sold_out' => 'sold_out'
            ],
            'shows' => [
                'jimmyFallon' => [
                    'url' => 'http://www.showclix.com/event/TheTonightShowStarringJimmyFallon/recurring-event-times',
                    //'url' => 'assets/jf-recurring-event-times.json',
                    'showName' => ($showName = 'The Tonight Show Starring Jimmy Fallon'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName),
                    'email' => [
                        'headerImage' => addPathForEmailImages('the-tonight-show-starring-jimmy-fallon.png'),
                        'template' => addPathForEmailTemplate('basic-tpl.php')
                    ]
                ],
                'stephenColbert' => [
                    'url' => 'http://www.showclix.com/event/TheLateShowwithStephenColb604314/recurring-event-times',
                    //'url' => 'assets/sc-recurring-event-times.json',
                    'showName' => ($showName = 'The Late Show with Stephen Colbert'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName),
                    'email' => [
                        'headerImage' => addPathForEmailImages('the-late-show-with-stephen-colbert.jpg'),
                        'template' => addPathForEmailTemplate('basic-tpl.php')
                    ]
                ],
                'sethMeyers' => [
                    'url' => 'http://www.showclix.com/event/latenightseth/recurring-event-times',
                    //'url' => 'assets/sm-recurring-event-times.json',
                    'showName' => ($showName = 'Late Night with Seth Meyers'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName),
                    'email' => [
                        'headerImage' => addPathForEmailImages('late-night-with-seth-meyers.png'),
                        'template' => addPathForEmailTemplate('basic-tpl.php')
                    ]
                ]
            ]
        ]
    ];
?>
