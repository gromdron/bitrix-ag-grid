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

	$requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();

	$rows = \Fusion\Sheet\DataTable::getList([
		'filter' => [
			'=IS_SYNCED' => 'N'
		],
		'limit' => 1000,
	]);

	foreach ($rows as $row)
	{
		$requestBody[] = new Google_Service_Sheets_ValueRange([
			'range' => $listName.'!A1',
			'majorDimension' => 'ROWS',
			'values' => ['values' => $rowData],
		]);
	}

	if ( count($requestBody)<1 )
	{
		throw new \Exception("All data is synced");
	}

	try
	{
		$response = $service->spreadsheets->batchUpdate(GS_SHEET_ID, $requestBody);
	}
	catch( \Exception $e )
	{
		var_dump($e->getMessage());
	}

	ob_start();
	echo "<pre>";
	var_dump($response);
	file_put_contents('/home/bitrix/wwww/gs_sync.txt', ob_get_clean(), FILE_APPEND);


	//$service->spreadsheets_values->update(
	//    $sheetId,
	//    $listName.'!A1',
	//    $updateBody,
	//    ['valueInputOption' => 'USER_ENTERED']
	//);
}
catch( \Exception $e )
{
	echo $e->getMessage().PHP_EOL;
}