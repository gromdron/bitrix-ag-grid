<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var CCrmEntityPopupComponent $component */

if($arResult['IFRAME'])
{
	\Fusion\Core\System::getMain()->RestartBuffer();

	?><!DOCTYPE html>
	<html>
	<head>
		<script type="text/javascript">
			// Prevent loading page without header and footer
			if(window === window.top)
			{
				window.location = "<?=CUtil::JSEscape($APPLICATION->GetCurPageParam('', array('IFRAME'))); ?>";
			}
		</script>
		<?$APPLICATION->ShowHead();?>
		<style>
			.contract-edit-iframe-popup
			{
				background-color: #eef2f4;
			}

			.contract-edit-iframe-workarea
			{
				padding: 0px 20px 20px 20px;
			}

			.contract-edit-iframe-pagetitle
			{
				margin: 20px 0 20px 0px;
			}

			.contract-edit-iframe-pagetitle-text
			{
				display: inline-block;
				font: 26px/40px "OpenSans-Light",Helvetica,Arial,sans-serif;
				margin: 0;
			}

			.contract-edit-iframe-toolbar
			{
				display: inline-block;
				float: right;
			}
		</style>
	</head>
	<body>
		<div class="contract-edit-iframe-workarea">
			<div class="contract-edit-iframe-pagetitle">
				<span class="contract-edit-iframe-pagetitle-text"><?$APPLICATION->ShowTitle(false);?></span>
				<div class="contract-edit-iframe-toolbar"><?$APPLICATION->ShowViewContent("pagetitle"); ?></div>
			</div>
			<div class="contract-edit-iframe-content"><?
}
foreach ($arResult['LIST'] as $item)
{
	$APPLICATION->IncludeComponent(
		$item['name'],
		$item['template'],
		$item['params'],
		false,
		false,
		$this->getComponent()
	);
}

if($arResult['IFRAME'])
{
			?></div>
		</div>
		</body>
	</html><?
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
	die();
}
