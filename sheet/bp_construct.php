<?php

require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$urlTemplates = [
    'BP_EDIT' => '/sheet/bp_construct.php?ID=#ID#',
    'BP_LIST' => '/sheet/bp_list.php',
];

$APPLICATION->IncludeComponent('bitrix:bizproc.workflow.edit', '', [
    'MODULE_ID' => 'fusion.sheet',
    'ENTITY' => \Fusion\Sheet\DataDocument::class,
    'DOCUMENT_TYPE' => 'sheet',
    'ID' => (int)$_REQUEST['ID'],
    'EDIT_PAGE_TEMPLATE' => $urlTemplates['BP_EDIT'] . '?ID=' . $_REQUEST['ID'],
    'LIST_PAGE_URL' => $urlTemplates['BP_LIST'],
    'SHOW_TOOLBAR' => 'Y',
    'SET_TITLE' => 'Y',
]);


require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');