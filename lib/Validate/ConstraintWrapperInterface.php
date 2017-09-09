<?php


namespace OUTRAGElib\Validate;


interface ConstraintWrapperInterface
{
	/**
	 *	Is this wrapper actually able to be used?
	 */
	public function isAvailable();
	
	
	/**
	 *	Checks to see whether or not this particular type of constraint
	 *	can be accepted by this object
	 */
	public function isTestable($constraint);
	
	
	/**
	 *	Filters an array of constraints and returns ones that can be
	 *	validated
	 *	
	 *	I recommend that you're returning set of *cloned* validators
	 */
	public function filterConstraints($constraints);
	
	
	/**
	 *	Validates the specified constraints against an input
	 */
	public function validate($constraint, $input, &$errors = []);
}