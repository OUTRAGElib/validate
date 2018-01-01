<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\Error\ErrorMessage;


interface ErrorInterface
{
	/**
	 *	Called when an error state needs to be flagged
	 */
	public function triggerError(ElementInterface $context, $message = null);
	
	
	/**
	 *	Add an already created error message
	 */
	public function addError(ErrorMessage $error);
	
	
	/**
	 *	Retrieve errors against this element.
	 */
	public function getErrors($named = true);
}