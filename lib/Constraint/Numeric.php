<?php


namespace OUTRAGElib\Validate\Constraint;

use \Exception;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Numeric extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $perform = false;
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function init($perform = true)
	{
		$this->perform = (boolean) $perform;
	}
	
	
	/**
	 *	Called to make sure that this value is a numerical value - /^[0-9]*$/
	 */
	public function test($input)
	{
		if($this->perform)
		{
			if(ctype_digit($input) || filter_var($input, FILTER_VALIDATE_INT))
				return true;
			
			$this->error = "Value not a numerical value.";
			return false;
		}
		
		return true;
	}
}