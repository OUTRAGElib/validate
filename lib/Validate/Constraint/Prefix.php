<?php


namespace OUTRAGElib\Validate\Constraint;

use \Exception;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Prefix extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $prefix = [];
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function init($prefix = [])
	{
		$this->prefix = is_array($prefix) ? $prefix : [ $prefix ];
	}
	
	
	/**
	 *	Called to make sure that this value is a numerical value - /^[0-9]*$/
	 */
	public function test($input)
	{
		if(!$this->prefix)
			return true;
		
		foreach($this->prefix as $prefix)
		{
			if(preg_match("/^".preg_quote($prefix)."/", $input))
				return true;
		}
		
		$this->error = "Value does not begin with '".implode("', '", $this->prefix)."'.";
		return false;
	}
}