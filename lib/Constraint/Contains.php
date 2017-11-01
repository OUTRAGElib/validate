<?php


namespace OUTRAGElib\Validate\Constraint;

use \Traversable;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Contains extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $dictionary = null;
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function init($dictionary)
	{
		if(!is_array($dictionary))
		{
			if($dictionary instanceof Traversable)
				$dictionary = iterator_to_array($dictionary);
		}
		
		$this->dictionary = array_values($dictionary);
	}
	
	
	/**
	 *	Called to make sure that this value is a numerical value - /^[0-9]*$/
	 */
	public function test($input)
	{
		if(count($this->dictionary) > 0)
		{
			if(!in_array($input, $this->dictionary))
			{
				$this->error = "Value is not valid.";
				return false;
			}
		}
		
		return true;
	}
}