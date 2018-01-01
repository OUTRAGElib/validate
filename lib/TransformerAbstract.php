<?php


namespace OUTRAGElib\Validate;


abstract class TransformerAbstract implements TransformerInterface
{
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
		
		return;
	}
}