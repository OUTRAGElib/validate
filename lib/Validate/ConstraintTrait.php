<?php


namespace OUTRAGElib\Validate;


trait ConstraintTrait
{
	/**
	 *	Stores a list of all conditions that this element depends on for a
	 *	successful validation.
	 */
	protected $constraints = [];
	
	
	/**
	 *	Add a validator
	 */
	public function addConstraint($constraint)
	{
		$this->constraints[] = $constraint;
		return $this;
	}
	
	
	/**
	 *	Checks to see if this validator is in use
	 */
	public function hasConstraint($constraint)
	{
	}
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeConstraint($constraint)
	{
	}
	
	
	/**
	 *	Retrieve all constraints
	 */
	public function getConstraints()
	{
		return $this->constraints;
	}
}