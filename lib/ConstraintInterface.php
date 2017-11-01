<?php


namespace OUTRAGElib\Validate;


interface ConstraintInterface
{
	/**
	 *	Use this method to deal with validating the input value.
	 */
	public function test($input);
	
	
	/**
	 *	Method to allow the validator access to the error message.
	 */
	public function getError();
}