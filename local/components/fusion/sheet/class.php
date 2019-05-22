<? if (! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\Rest;
use \Fusion\Sheet;
use \Fusion\AgGrid;
use \Bitrix\Main\Engine;
use \Bitrix\Main\Grid;
use \Bitrix\Main\Context;
use \Bitrix\Main\Search;

class SheetComponent 
	extends \CBitrixComponent 
	implements Engine\Contract\Controllerable
{
	protected static $letters = ['A','B','C','D','E','F'];

	protected static $pageSize = 100;

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


	/**
	 * Save row data
	 * @param array $params 
	 * @return boolean
	 */
	public function saveRowAction( $params )
	{
		$fields = [
			'IS_SYNCED' => 'N'
		];

		$cells = [
			'CELL_A',
			'CELL_B',
			'CELL_C',
			'CELL_D',
			'CELL_E',
			'CELL_F',
		];

		foreach ($cells as $cellName)
		{
			if ( array_key_exists($cellName, $params) )
			{
				$fields[ $cellName ] = $params[ $cellName ];
			}
		}

		if ( array_key_exists('ROW_NUMBER', $params) )
		{
			$fields['ROW_NUMBER'] = (int) $params['ROW_NUMBER'];
		}

		if ( empty($fields['ROW_NUMBER']) )
		{
			$fields['ROW_NUMBER'] = Sheet\Row::getLastRowNumber() + 1;

			$saveResult = Sheet\DataTable::add($fields);
		}
		else
		{
			$saveResult = Sheet\DataTable::update($fields['ROW_NUMBER'], $fields);
		}

		if ( !$saveResult->isSuccess() )
		{
			throw new Rest\RestException( implode(', ', $saveResult->getErrorMessages()));
		}

		return true;
	}

	
	public function getRowsAction( $params )
	{
		$data = [];

		$oGridFilter = new AgGrid\Filter( $params['filterModel'] );

		$order = $this->prepareSortModel( $params['sortModel'] );

		$sheetData = Sheet\DataTable::getList([
			'filter'      => $oGridFilter->getCompiledFilter(),
			'order'       => $order,
			'limit'       => $params['endRow'] + static::$pageSize,
			'offset'      => $params['startRow'],
			'count_total' => true,
		]);


		foreach ($sheetData as $row)
		{
			$data['rows'][] = (array) $row;
		}

		$data['lastRow'] = $sheetData->getCount();

		return $data;
	}

	public function prepareFilter( $filterParams = [] )
	{
		$filter = [];

		foreach ($filterParams as $cellCoord => $filterData)
		{
			$filterParams = [];

			if ( !empty($filterData['operator']) )
			{
				$filterParams['LOGIC'] = strtoupper($filterData['operator']);

				foreach ($filterData as $conditionId => $conditionValue)
				{
					
			switch ($filterData['type'])
			{
				case 'notContains':
					$filter['!%'.$cellCoord] = $filterData['filter'];
					break;

				case 'contains':
					$filter['%'.$cellCoord] = $filterData['filter'];
					break;
				
				default:
					break;
			}
		}

		return $filter;
	}

	public function parseCondition( $filterVars = [] )
	{
		$condition = [
			'key'   => '',
			'value' => '',
		];

		return $condition;
	}

	public function prepareSortModel( $sortModel = [] )
	{
		$order = [];

		foreach ($sortModel as $sortParam)
		{
			$order[ $sortParam['colId'] ] = strtoupper($sortParam['sort']);
		}

		return $order;
	}

	public function loadExtensions()
	{
		Main\UI\Extension::load([
			'fusion.sheet',
			'ag.grid',
		]);
	}

	public function executeComponent()
	{
		try
		{
			$this->loadExtensions();

			$arResult = &$this->arResult;

			foreach (static::$letters as $letter)
			{
				$arResult['COLUMNS'][] = [
					'headerName' => $letter,
					'field'      => 'CELL_'.$letter,
					'sortable'   => true, 
					'filter'     => "agTextColumnFilter",
					'filterParams' => [
						'apply' => true,
						'newRowsAction' => 'keep',
					],
				];
			}

			$arResult['PAGE_SIZE'] = static::$pageSize;

			$this->includeComponentTemplate();
		}
		catch ( \Exception $e )
		{
			ShowError($e->getMessage());
		}
	}
}