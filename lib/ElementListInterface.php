<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Structure\ObjectListInterface;


interface ElementListInterface extends ObjectListInterface, ErrorInterface, ElementGetterInterface
{
	/**
	 *	An entry point to begin adding in rules on class initialisation
	 */
	public function rules();
	
	
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
	
	
	/**
	 *	Validate this template based on fields passed.
	 */
	public function validate($input);
	
	
	/**
	 *	Retrieves an child on this template level.
	 */
	public function getElement($component);
	
	
	/**
	 *	Checks if this template already has an element with the same name
	 *	already on this template level.
	 */
	public function hasElement($component);
	
	
	/**
	 *	Retrieve values from the last validation attempt. Will return values regardless
	 *	of the validity of the last request.
	 */
	public function getValues();
}