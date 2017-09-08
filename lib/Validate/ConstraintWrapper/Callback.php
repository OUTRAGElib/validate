<?php


namespace OUTRAGElib\Validate\ConstraintWrapper;

use \Closure;
use \Exception;
use \OUTRAGElib\Validate\ConstraintWrapperAbstract;
use \ReflectionFunction;
use \ReflectionObject;


class Callback extends ConstraintWrapperAbstract
{
	/**
	 *	Checks to see whether or not this particular type of constraint
	 *	can be accepted by this object
	 */
	public function isTestable($constraint)
	{
		if(is_object($constraint) && $constraint instanceof Closure)
			return true;
		elseif(is_string($constraint) && function_exists($constraint))
			return true;
		elseif(is_array($constraint) && is_object($constraint[0]) && method_exists($constraint[0], $constraint[1]))
			return true;
		
		return false;
	}
	
	
	/**
	 *	Filters an array of constraints and returns ones that can be
	 *	validated
	 */
	public function filterConstraints($constraints)
	{
		$list = array();
		
		foreach($constraints as $constraint)
		{
			if($this->isTestable($constraint))
				$list[] = $this->toClosure($constraint);
		}
		
		return $list;
	}
	
	
	/**
	 *	Validates the specified constraints against an input
	 */
	protected function test($constraint, $input)
	{
		return $constraint($input) !== false;
	}
	
	
	/**
	 *	Retrieves the messages that were set
	 */
	protected function getErrors($constraint)
	{
		return [ "Invalid or malformed input" ];
	}
	
	
	/**
	 *	Converts any sort of callable function into a closure, ready for evaluation
	 */
	protected function toClosure(callable $constraint)
	{
		if($constraint instanceof Closure)
		{
			return $constraint;
		}
		elseif(is_string($constraint) && function_exists($constraint))
		{
			return (new ReflectionFunction($constraint))->getClosure();
		}
		elseif(is_array($constraint) && is_object($constraint[0]) && method_exists($constraint[0], $constraint[1]))
		{
			$reflector = new ReflectionObject($constraint[0]);
			
			if($reflector->hasMethod($constraint[1]))
				return $reflector->getMethod($constraint[1])->getClosure($constraint[0]);
		}
		
		throw new Exception("Unable to turn callback into closure");
	}
}