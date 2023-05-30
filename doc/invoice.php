<?php
$invoice = [
    'PILOTAGE' => [
        [
            'label'=> 'PILOTAGE TYPE',
            'value'=> 'INWARD'
        ],
        [
            'label'=> 'VESSEL NAME',
            'value'=> 'INWARD'
        ],
    ] ,

    'TUGS' =>[
        [
            [
                'LABEL'=>'TUG',
                'VALUE'=>'TUG NME',

            ],
            [
                'LABEL'=>'Assistance from time',
                'VALUE'=>'03-05-2020 12:00AM',
            ],
            [
                'LABEL'=>'Assistance to time',
                'VALUE'=>'03-05-2020 12:00AM',
            ],
            [
                'LABEL'=>'Primary',
                'VALUE'=>'YES',
            ],
            [
                'LABEL'=>'Work Location',
                'VALUE'=>'OUTSIDE PORT LIMIT',
            ]
        ],


    ],
    'FEES'=>''

];

$json_data = json_encode($invoice);

