<?php
if (php_sapi_name() != 'cli')
    return;

define("NO_KEEP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
define("NEED_AUTH", true);

/** Environment params for CLI */
$_SERVER['DOCUMENT_ROOT'] = realpath('/home/bitrix/www');
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

/** Include bitrix core */
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);

try
{
	$client = \Fusion\Sheet\Client::get();
	

	$service = new \Google_Service_Sheets($client);
 
	$sheetId  = GS_SHEET_ID;
	$listName = GS_SHEET_NAME;

	$requests = [];

	$rows = \Fusion\Sheet\DataTable::getList([
		'filter' => [
			'=IS_SYNCED' => 'N'
		],
		'limit' => 1000,
	]);

	foreach ($rows as $k => $row)
	{
		$rowData = (array) $row;

		unset($rowData['IS_SYNCED']);
		unset($rowData['ROW_NUMBER']);

		$rowData = array_values($rowData);

		$requests[$k] = new Google_Service_Sheets_ValueRange([
			'range' => $listName.'!A'.$row['ROW_NUMBER'],
			'majorDimension' => 'ROWS',
			'values' => ['values' => $rowData],
		]);
	}

	if ( count($requests)<1 )
	{
		throw new \Exception("All data is synced");
	}

	$requestBody = new Google_Service_Sheets_BatchUpdateValuesRequest();
	$requestBody->setData($requests);
	$requestBody->setValueInputOption('RAW');

	try
	{
		$responses = $service->spreadsheets_values->batchUpdate(GS_SHEET_ID, $requestBody);
	}
	catch( \Exception $e )
	{
		\CEventLog::Add([
			"SEVERITY"      => "ERROR",
			"AUDIT_TYPE_ID" => "SYNC_ERROR",
			"MODULE_ID"     => "fusion.sheet",
			"DESCRIPTION"   => $e->getMessage()
		]);
		return '';
	}

	foreach ($responses as $response)
	{
		if ( !preg_match_all('#Sheet1!A([0-9]+):F([0-9]+)#i', $response->getUpdatedRange(), $matches) )
		{
			continue;
		}

		$row = \Fusion\Sheet\DataTable::getRow([
			'select' => ['ROW_NUMBER'],
			'filter' => [
				'=ROW_NUMBER' => $matches[1][0]
			],
		]);

		if ( !$row )
		{
			\CEventLog::Add([
				"SEVERITY"      => "ERROR",
				"AUDIT_TYPE_ID" => "SYNC_ERROR",
				"MODULE_ID"     => "fusion.sheet",
				'ITEM_ID'       => $response->getUpdatedRange(),
				"DESCRIPTION"   => 'Updated row not found'
			]);
			continue;
		}

		$updatedFields = [
			'IS_SYNCED' => 'Y'
		];

		$updateResult = \Fusion\Sheet\DataTable::update($row['ROW_NUMBER'], $updatedFields);

		if ( !$updateResult->isSuccess() )
		{
			\CEventLog::Add([
				"SEVERITY"      => "ERROR",
				"AUDIT_TYPE_ID" => "SYNC_ERROR",
				"MODULE_ID"     => "fusion.sheet",
				'ITEM_ID'       => $row['ROW_NUMBER'],
				"DESCRIPTION"   => implode(', ', $updateResult->getErrorMessages() )
			]);
			continue;
		}
	}
}
catch( \Exception $e )
{
	\CEventLog::Add([
		"SEVERITY"      => "ERROR",
		"AUDIT_TYPE_ID" => "SYNC_ERROR",
		"MODULE_ID"     => "fusion.sheet",
		"DESCRIPTION"   => 'Google sync result: '.$e->getMessage()
	]);
}