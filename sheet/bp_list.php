<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/header.php");


$APPLICATION->IncludeComponent('bitrix:main.interface.toolbar', '',
    [
        'BUTTONS' => [
            [
                'TEXT' => 'Создать БП со статусами',
                'TITLE' => 'Создать БП со статусами',
                'LINK' => CComponentEngine::makePathFromTemplate(
                    '/sheet/bp_construct.php?init=statemachine', [
                    'ID' => 0
                ]),
                'ICON' => 'btn-new'
            ],
            [
                'TEXT' => 'Создать последовательный БП',
                'TITLE' => 'Создать последовательный БП',
                'LINK' => CComponentEngine::makePathFromTemplate(
                    '/sheet/bp_construct.php', [
                    'ID' => 0
                ]),
                'ICON' => 'btn-new'
            ]
        ]
    ]
);







$APPLICATION->IncludeComponent('bitrix:bizproc.workflow.list', '.default',
    [
        'MODULE_ID' => 'fusion.sheet',
        'ENTITY' => \Fusion\Sheet\DataDocument::class,
        'DOCUMENT_ID' => 'sheet',
        'CREATE_DEFAULT_TEMPLATE' => 'N',
        'EDIT_URL' => '/sheet/bp_construct.php?ID=#ID#',
        'SET_TITLE' => 'N',
        'TARGET_MODULE_ID' => 'fusion.sheet'
    ]
);




require ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/footer.php");