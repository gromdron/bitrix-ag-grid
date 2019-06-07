<?php

namespace Fusion\Sheet;

use \Bitrix\Main;
use \Bitrix\Main\ORM;
use \Bitrix\Main\Orm\Fields;
use \Bitrix\Main\Search;

class DataTable extends ORM\Data\DataManager
{
	public static function getTableName()
	{
		return 'f_sheet_data';
	}

	public static function getMap()
	{
		return [
			(new Fields\IntegerField('ROW_NUMBER'))
				->configurePrimary(true),

			(new Fields\StringField('CELL_A', [
				'title' => 'A',
			])),

			(new Fields\StringField('CELL_B', [
				'title' => 'B',
			])),

			(new Fields\StringField('CELL_C', [
				'title' => 'C',
			])),

			(new Fields\StringField('CELL_D', [
				'title' => 'D',
			])),

			(new Fields\StringField('CELL_E', [
				'title' => 'E',
			])),

			(new Fields\StringField('CELL_F', [
				'title' => 'F',
			])),

			(new Fields\BooleanField('IS_SYNCED',[
				'default_value' => 'N',
				'values' => ['N','Y']
			])),
		];
	}

	public static function onBeforeAdd( ORM\Event $event)
	{
		$result = new ORM\EventResult;

		$data = $event->getParameter("fields");

		$modifyFields = [];

		if ( !isset($data['IS_SYNCED']) )
		{
			$modifyFields['IS_SYNCED'] = 'N';
		}

		if ( !empty($modifyFields) )
		{
			$result->modifyFields( $modifyFields );
		}

		return $result;
	}

	public static function onBeforeUpdate( ORM\Event $event)
	{
		$result = new ORM\EventResult;

		$data = $event->getParameter("fields");

		$modifyFields = [];

		if ( !array_key_exists('IS_SYNCED', $data) )
		{
			$modifyFields['IS_SYNCED'] = 'N';
		}

		if ( !empty($modifyFields) )
		{
			$result->modifyFields( $modifyFields );
		}

		return $result;
	}
}