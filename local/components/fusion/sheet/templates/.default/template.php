<? if (! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->SetViewTarget('pagetitle'); ?>
    <button onclick='BX.Fusion.Sheet.Row.showAddRow();' class="ui-btn ui-btn-md ui-btn-success">Добавить строку</button>
<? $this->EndViewTarget(); ?>

<div 
	id="myGrid" 
	class="ag-theme-balham" 
	style="width: 100%; height: 95%;"
	></div>

<script type="text/javascript">
BX.ready(function(){
	window['agGridObject'] = BX.AgGrid.init(<?=CUtil::PhpToJSObject([
		'container' => 'myGrid',
		'options' => [
			'columnDefs' => $arResult['COLUMNS'],

			'rowModelType' => 'serverSide',

			'domLayout' => 'autoHeight',

			'defaultColDef' => [
				'editable' => true,
				'resizable' => true,
			],

			'multiSortKey' => 'ctrl',

			"pagination" => true,
			"paginationPageSize" => $arResult['PAGE_SIZE'],

		]
	])?>);
});
</script>