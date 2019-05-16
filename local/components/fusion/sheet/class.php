<? if (! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\Main\Engine;
use \Bitrix\Main\Grid;
use \Bitrix\Main\Context;
use \Bitrix\Main\Search;

use \Fusion\Sheet;

class RequestListComponent 
	extends \CBitrixComponent 
	implements Engine\Contract\Controllerable
{

	public function configureActions()
	{
		return [];
	}

	public function onPrepareComponentParams($arParams)
	{
		$arParams = parent::onPrepareComponentParams($arParams);

		return $arParams;
	}

	/**
	 * Ajax component action group
	 */
	public function getRowsAction( $params )
	{
		$data = [];

		for ( $i = 0; $i < 50; $i++)
		{
			$data['rows'][] = [
				'make'  => 'Toyota',
				'model' => 'Celica',
				'price' => '35000',
			];

			$data['rows'][] = [
				'make'  => 'Ford',
				'model' => 'Mondeo',
				'price' => '32000',
			];

			$data['rows'][] = [
				'make'  => 'Porsche',
				'model' => 'Boxter',
				'price' => '72000',
			];
		}

		$data['lastRow'] = 150;

		return $data;
	}

	public function loadExtensions()
	{
		Main\UI\Extension::load([
			'ag.grid'
		]);
	}

	public function executeComponent()
	{
		try
		{
			$this->loadExtensions();

			$this->includeComponentTemplate();
		}
		catch ( \Exception $e )
		{
			ShowError($e->getMessage());
		}
	}
}