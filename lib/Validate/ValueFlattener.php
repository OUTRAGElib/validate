<?php


namespace OUTRAGElib\Validate;


class ValueFlattener
{
	/**
	 *	List of values that have been flattened/merged
	 */
	protected $context = [];
	
	
	/**
	 *	Called when this flattener is initialised
	 */
	public function __construct($pairs)
	{
		$this->context = $this->flatten($pairs);
	}
	
	
	/**
	 *	Flattens an array of Values into a nested array.
	 */
	protected function flatten($pairs = [], $offset = 0, &$context = [])
	{
		foreach($pairs as $pair)
		{
			$pointer = &$context;
			
			if($count = count($pair->tree))
			{
				for($i = $offset; $i < $count; ++$i)
				{
					$key = (string) $pair->tree[$i];
					
					if(!isset($pointer[$key]))
						$pointer[$key] = [];
					
					$pointer = &$pointer[$key];
				}
				
				if(is_array($pair->value))
				{
					if(isset($pair->element) && $pair->element->is_array)
					{
						foreach($pair->value as $item)
							$this->flatten($item, $offset, $context);
					}
					else
					{
						$this->flatten($pair->value, $offset, $context);
					}
				}
				else
				{
					$pointer = $pair->value;
				}
			}
			
			unset($pair);
			unset($pointer);
		}
		
		return $context;
	}
	
	
	/**
	 *	Returns all the values from above
	 */
	public function getContext()
	{
		return $this->context;
	}
}