<?php
define('MODULE_ID', 'fusion.sheet');
define('ENTITY', '\Fusion\Sheet\DataDocument');

$fp = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bizprocdesigner/admin/bizproc_wf_settings.php';

if (is_file($fp))
{
    require( $fp );
}