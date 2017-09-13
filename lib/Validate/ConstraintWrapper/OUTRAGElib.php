<?php


namespace OUTRAGElib\Validate\ConstraintWrapper;

use \Exception;
use \OUTRAGElib\Validate\ConstraintInterface;
use \OUTRAGElib\Validate\ConstraintWrapperAbstract;


class OUTRAGElib extends ConstraintWrapperAbstract
{
	/**
	 *	Is this wrapper actually able to be used?
	 */
	public function isAvailable()
	{
		return interface_exists(ConstraintInterface::class);
	}
	
	
	/**
	 *	Checks to see whether or not this particular type of constraint
	 *	can be accepted by this object
	 */
	public function isTestable($constraint)
	{
		if(is_object($constraint))
			return $constraint instanceof ConstraintInterface;
		
		return false;
	}
	
	
	/**
	 *	Validates the specified constraints against an input
	 */
	protected function test($constraint, $input)
	{
		return $constraint->test($input);
	}
	
	
	/**
	 *	Retrieves the messages that were set
	 */
	protected function getErrors($constraint)
	{
		return [ $constraint->getError() ];
	}
}