<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();


class CoreFrameComponent
	extends \CBitrixComponent
{

	public function executeComponent()
	{
		$this->arResult['LIST'] = (array) $this->arParams['COMPONENTS'];

		$this->arResult['IFRAME'] = isset($this->request['IFRAME']) && $this->request['IFRAME'] === 'Y';
		$this->arResult['IFRAME_USE_SCROLL'] = $this->request['IFRAME_USE_SCROLL'] == 'Y';

		$this->includeComponentTemplate();
	}
}