<?php


namespace OUTRAGElib\Validate\Constraint;

use \Exception;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Regex extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $pattern = null;
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function init($pattern)
	{
		$this->pattern = (array) $pattern;
	}
	
	
	/**
	 *	Called to make sure that this value is a numerical value - /^[0-9]*$/
	 */
	public function test($input)
	{
		if($this->pattern)
		{
			foreach($this->pattern as $item)
			{
				if(preg_match($item, $input))
					return true;
			}
			
			$this->error = "Value does not match pattern.";
			return false;
		}
		
		return true;
	}
}