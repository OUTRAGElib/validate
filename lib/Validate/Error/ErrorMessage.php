<?php
/**
 *	The Value class allows us to pin point exactly what errors can
 *	be associated to what field, and make it easier to iterate
 *	through results or something.
 */


namespace OUTRAGElib\Validate\Error;


class ErrorMessage
{
	public $name;
	public $context;
	public $message;
}