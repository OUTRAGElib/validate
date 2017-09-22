<?php


namespace OUTRAGElib\Validate\Constraint;

use \Exception;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Suffix extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $suffix = [];
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function init($suffix = [])
	{
		$this->suffix = is_array($suffix) ? $suffix : [ $suffix ];
	}
	
	
	/**
	 *	Called to make sure that this value is a numerical value - /^[0-9]*$/
	 */
	public function test($input)
	{
		if(!$this->suffix)
			return true;
		
		foreach($this->suffix as $suffix)
		{
			if(preg_match("/".preg_quote($suffix)."$/", $input))
				return true;
		}
		
		$this->error = "Value does not end with '".implode("', '", $this->suffix)."'.";
		return false;
	}
}