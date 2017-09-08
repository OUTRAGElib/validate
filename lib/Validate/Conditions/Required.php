<?php
/**
 *	Validation condition for OUTRAGElib: Required values.
 */


namespace OUTRAGElib\Validate\Conditions;

use \OUTRAGElib\Validate;


class Required extends Validate\Condition
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $required = false;
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function methodArgs($required = false)
	{
		$this->required = (boolean) $required;
	}
	
	
	/**
	 *	Called to make sure that this value does indeed exist.
	 */
	public function validate($input)
	{
		if($this->required)
		{
			if($input === null || $input === "")
				return $this->error = "Value not supplied.";
		}
		
		return false;
	}
}