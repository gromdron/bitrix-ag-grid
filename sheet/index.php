<?

use Bitrix\Main\Localization\Loc;

require( $_SERVER[ "DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle('GS');
?>
<?
$APPLICATION->IncludeComponent(
    'bitrix:crm.interface.toolbar',
    'title',
    array(
        'TOOLBAR_ID' => 'SHEET_TOOLBAR',
        'BUTTONS' => array(
            array(
                'TEXT' => 'Перейти к настройке БП',
                'TITLE' => 'Перейти к настройке БП',
                'LINK' => '/sheet/bp_list.php'
            )
        )
    )
);
?>

<?
$APPLICATION->IncludeComponent(
  "fusion:sheet",
  "",
  [],
  false
);
?>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>