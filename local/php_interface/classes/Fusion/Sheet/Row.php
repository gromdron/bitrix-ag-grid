<?php

namespace Fusion\Sheet;

class Row
{

	/**
	 * Return last row number
	 * @return integer
	 */
	public static function getLastRowNumber()
	{
		$lastRow = DataTable::getRow([
			'select' => [
				'ROW_NUMBER'
			],
			'limit'  => 1,
			'order'  => [
				'ROW_NUMBER' => 'DESC'
			]
		]);

		return (int) $lastRow['ROW_NUMBER'] ?? 1;
	}
}