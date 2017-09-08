<?php


namespace OUTRAGElib\Validate;


abstract class ConstraintAbstract implements ConstraintInterface
{
	/**
	 *	Store the error message in this variable, the validator
	 *	will pick this up.
	 */
	protected $error = null;
	
	
	/**
	 *	Pass any arguments onto the arguments handler, if there is one.
	 */
	public final function __construct()
	{
		$arguments = func_get_args();
		
		if(count($arguments))
		{
			if(method_exists($this, "init"))
				call_user_func_array([ $this, "init" ], $arguments);
		}
		
		return true;
	}
	
	
	/**
	 *	Use this method to deal with validating the input value.
	 *
	 *	Any return value is treated as boolean, however there is a slight cinch.
	 *	A false value denotes success, a true value denotes failure. Compare this
	 *	to the return values of a process in your operating system. $? anyone?
	 */
	public function test($input)
	{
		return true;
	}
	
	
	/**
	 *	Method to allow the validator access to the error message.
	 */
	public function getError()
	{
		return $this->error;
	}
}