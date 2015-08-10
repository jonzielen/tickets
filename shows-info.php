<?php
    $jonsEmail = 'jonzielen@gmail.com';

    function datesFileName($showName) {
        return 'assets/'.str_replace(" ", "-", strtolower ($showName)).'.txt';
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
                    'showName' => ($showName = 'The Tonight Show Starring Jimmy Fallon'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName)
                ],
                'stephenColbert' => [
                    'url' => 'http://www.showclix.com/event/TheLateShowwithStephenColb604314/recurring-event-times',
                    'showName' => ($showName = 'The Late Show with Stephen Colbert'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName)
                ],
                'sethMeyers' => [
                    'url' => 'http://www.showclix.com/event/latenightseth/recurring-event-times',
                    'showName' => ($showName = 'Late Night with Seth Meyers'),
                    'emailTo' => $jonsEmail,
                    'datesFile' => datesFileName($showName)
                ]
            ]
        ]
    ];
?>
