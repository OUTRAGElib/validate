<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\ConstraintWrapperInterface;
use \OUTRAGElib\Validate\ErrorInterface;


interface ComponentInterface extends ErrorInterface
{
	/**
	 *	Set the name (key?) of this component.
	 */
	public function setName($name);
	
	
	/**
	 *	Set the label of this component.
	 */
	public function setLabel($label = null);
	
	
	/**
	 *	Set the array capability of this component.
	 */
	public function setIsArray($value = null);
	
	
	/**
	 *	Appends this element to a input template.
	 */
	public function appendTo(ElementList $element);
	
	
	/**
	 *	Adds a constraint wrapper to the validation request
	 */
	public function addConstraintWrapper(ConstraintWrapperInterface $wrapper);
	
	
	/**
	 *	Retrieves all constraint wrappers
	 */
	public function getConstraintWrappers();
}