<?php


namespace OUTRAGElib\Validate;


interface ErrorInterface
{
	/**
	 *	Called when an error state needs to be flagged
	 */
	public function triggerError(ElementInterface $context, $message = null);
	
	
	/**
	 *	Retrieve errors against this element.
	 */
	public function getErrors($named = true);
}