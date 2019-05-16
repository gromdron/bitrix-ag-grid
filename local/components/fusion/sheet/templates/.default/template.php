<? if (! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div 
	id="myGrid" 
	class="ag-theme-balham" 
	style="width: 100%; height: 100%;"
	></div>

<script type="text/javascript">
BX.ready(function(){
	window['agGridObject'] = BX.AgGrid.init(<?=CUtil::PhpToJSObject([
		'container' => 'myGrid',
		'options' => [
			'columnDefs' => [
				[
					'headerName' => 'Make',
					'field' => 'make',
					'sortable' => true, 
					'filter' => "agTextColumnFilter"
				],
				[
					'headerName' => 'Model',
					'field' => 'model',
					'sortable' => true, 
					'filter' => "agTextColumnFilter"
				],
				[
					'headerName' => 'Price',
					'field' => 'price',
					'sortable' => true, 
					'filter' => "agTextColumnFilter"
				],
			],

			'rowModelType' => 'serverSide',

			'domLayout' => 'autoHeight',

			'defaultColDef' => [
				'editable' => true,
				'resizable' => true,
			],

			'multiSortKey' => 'ctrl',

			"pagination" => true,
			"paginationPageSize" => 10,

		]
	])?>);
});
</script>