<?php


namespace OUTRAGElib\Validate;


interface TransformerInterface
{
	/**
	 *	Use this method to deal with validating the input value.
	 */
	public function transform($input);
}