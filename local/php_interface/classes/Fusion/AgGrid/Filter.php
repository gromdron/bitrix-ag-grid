<?php

namespace Fusion\AgGrid;

class Filter
{
	protected $filters = [];

	protected $compiledFilter = [];

	private $filterCompiled = false;

	public function __construct( $filters )
	{
		$this->filters = $filters;

		if ( empty($this->filters) )
		{
			$this->filterCompiled = true;
		}
	}

	/**
	 * Return compiled filter
	 * 
	 * @return array
	 */
	public function getCompiledFilter()
	{
		if ( !$this->filterCompiled )
		{
			$this->compile();
			$this->filterCompiled = true;
		}

		return $this->compiledFilter;
	}

	/**
	 * Compile filter and store it to interal
	 *     object property
	 * @return void
	 */
	protected function compile()
	{
		$this->compiledFilter = [];

		foreach ($this->filters as $column => $mixFilter)
		{
			$rule = [];

			if ( array_key_exists('operator', $mixFilter) )
			{
				$rule['LOGIC'] = strtoupper($mixFilter['operator']);

				unset($mixFilter['operator']);
			}
			else
			{
				$mixFilter = [ $mixFilter ];
			}

			foreach ($mixFilter as $condition)
			{
				$result = $this->parseCondition( $condition, $column );

				if ( !empty($result['rule']) )
				{
					$rule[][ $result['rule'] ] = $result['value'];
				}
			}

			if ( count($rule) <= 2 )
			{
				unset($rule['LOGIC']);
				$rule = array_shift($rule);
			}

			$this->compiledFilter[] = $rule;
		}

		if ( count($this->compiledFilter) == 1 )
		{
			$this->compiledFilter = (array) array_shift($this->compiledFilter);
		}

		//global $APPLICATION;

		//$APPLICATION->RestartBuffer();
		//echo "<pre>";
		//var_dump($this->compiledFilter);
		//echo "</pre>";
		//die();
	}

	/**
	 * Parse condition and return prepared string
	 * @param array $condition 
	 * @param string $column 
	 * @return array
	 */
	protected function parseCondition( $condition, $column )
	{
		$result = [
			'rule'  => '',
			'value' => '',
		];

		switch ($condition['type'])
		{
			case 'startsWith':
				$result['rule']  = $column;
				$result['value'] = $condition['filter'].'%';
				break;

			case 'endsWith':
				$result['rule']  = $column;
				$result['value'] = '%'.$condition['filter'];
				break;

			case 'equals':
				$result['rule']  = '='.$column;
				$result['value'] = $condition['filter'];
				break;

			case 'notEqual':
				$result['rule']  = '!='.$column;
				$result['value'] = $condition['filter'];
				break;

			case 'contains':
				$result['rule']  = '%'.$column;
				$result['value'] = $condition['filter'];
				break;

			case 'notContains':
				$result['rule']  = '!%'.$column;
				$result['value'] = $condition['filter'];
				break;
			
			default:break;
		}

		return $result;
	}

}