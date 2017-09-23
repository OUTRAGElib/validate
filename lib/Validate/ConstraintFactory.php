<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \ReflectionClass;


class ConstraintFactory
{
	/**
	 *	Class
	 */
	protected $class = null;
	
	
	/**
	 *	Creates an instance of a constraint from a string name
	 */
	public function __construct($constraint)
	{
		$this->class = '\OUTRAGElib\Validate\Constraint\\'.ucwords($constraint);
	}
	
	
	/**
	 *	Retrieves class name
	 */
	public function getClass()
	{
		return $this->class;
	}
	
	
	/**
	 *	Retrieves the constraint
	 */
	public function getConstraint($arguments = [])
	{
		if(class_exists($this->class))
			return (new ReflectionClass($this->class))->newInstanceArgs($arguments);
		
		return null;
	}
}