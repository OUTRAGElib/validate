<?php


namespace OUTRAGElib\Validate\ConstraintWrapper;

use \Exception;
use \Zend\Validator\ValidatorInterface;
use \OUTRAGElib\Validate\ConstraintWrapperAbstract;


class Zend extends ConstraintWrapperAbstract
{
	/**
	 *	Checks to see whether or not this particular type of constraint
	 *	can be accepted by this object
	 */
	public function isTestable($constraint)
	{
		if(is_object($constraint))
			return $constraint instanceof ValidatorInterface;
		
		return false;
	}
	
	
	/**
	 *	Validates the specified constraints against an input
	 */
	protected function test($constraint, $input)
	{
		return $constraint->isValid($input);
	}
	
	
	/**
	 *	Retrieves the messages that were set
	 */
	protected function getErrors($constraint)
	{
		return array_values($constraint->getMessages());
	}
}