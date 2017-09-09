<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \ReflectionClass;


class ConstraintFactory
{
	/**
	 *	Constraint
	 */
	protected $constraint = null;
	
	
	/**
	 *	Creates an instance of a constraint from a string name
	 */
	public function __construct($constraint, $arguments = [])
	{
		$class = '\OUTRAGElib\Validate\Constraint\\'.ucwords($constraint);
		
		if(class_exists($class))
			$this->constraint = (new ReflectionClass($class))->newInstanceArgs($arguments);
		
		return null;
	}
	
	
	/**
	 *	Retrieves the constraint
	 */
	public function getConstraint()
	{
		return $this->constraint;
	}
}