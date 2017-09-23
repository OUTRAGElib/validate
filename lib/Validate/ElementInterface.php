<?php


namespace OUTRAGElib\Validate;


interface ElementInterface
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null);
	
	
	/**
	 *	Add a validator
	 */
	public function addConstraint($constraint);
	
	
	/**
	 *	Checks to see if this validator is in use
	 */
	public function hasConstraint($constraint);
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeConstraint($constraint);
	
	
	/**
	 *	Retrieve all conditions that match what is provided
	 */
	public function getConstraints($constraint = null);
	
	
	/**
	 *	Add a transformer
	 */
	public function addTransformer($constraint);
	
	
	/**
	 *	Checks to see if this transformer is in use
	 */
	public function hasTransformer($constraint);
	
	
	/**
	 *	Removes all transformers that match what is provided
	 */
	public function removeTransformer($constraint);
	
	
	/**
	 *	Retrieve all transformers that match what is provided
	 */
	public function getTransformers($constraint = null);
}