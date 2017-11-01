<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Structure\ObjectListInterface;


interface ElementListInterface extends ObjectListInterface
{
	/**
	 *	Adds a constraint wrapper to the validation request
	 */
	public function addConstraintWrapper(ConstraintWrapperInterface $wrapper);
	
	
	/**
	 *	Retrieves all constraint wrappers
	 */
	public function getConstraintWrappers();
	
	
	/**
	 *	Adds a transformer wrapper to the validation request
	 */
	public function addTransformerWrapper(TransformerWrapperInterface $wrapper);
	
	
	/**
	 *	Retrieves all transformer wrappers
	 */
	public function getTransformerWrappers();
}