<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle('GS');
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