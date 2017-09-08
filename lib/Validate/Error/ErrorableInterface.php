<?php


namespace OUTRAGElib\Validate\Error;


interface ErrorableInterface
{
	/**
	 *	Called when an error state needs to be flagged
	 */
	public function triggerError($context, $message = null);
}