<?php
/**
 *	Validation condition for OUTRAGElib: Required values.
 */


namespace OUTRAGElib\Validate\Constraint;


class Required extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $required = false;
	
	
	/**
	 *	Called when the validator is initiated
	 */
	public function init($required)
	{
		$this->required = $required;
	}
	
	
	/**
	 *	Called to make sure that this value does indeed exist.
	 */
	public function test($input)
	{
		if($this->required)
		{
			if($input === null || $input === "")
			{
				$this->error = "Value not supplied.";
				return false;
			}
		}
		
		return true;
	}
}