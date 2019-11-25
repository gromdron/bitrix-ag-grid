<?php

define('MODULE_ID', 'fusion.sheet');
define('ENTITY', '\Fusion\Sheet\DataDocument');

$fp = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bizproc/admin/bizproc_selector.php';
if (is_file($fp))
{
    require($fp);
}