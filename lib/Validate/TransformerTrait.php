<?php


namespace OUTRAGElib\Validate;


trait TransformerTrait
{
	/**
	 *	Stores a list of all conditions that this element depends on for a
	 *	successful validation.
	 */
	protected $transformers = [];
	
	
	/**
	 *	Add a validator
	 */
	public function addTransformer($transformer)
	{
		$this->transformers[] = $transformer;
		return $this;
	}
	
	
	/**
	 *	Checks to see if this validator is in use
	 */
	public function hasTransformer($transformer)
	{
	}
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeTransformer($transformer)
	{
	}
	
	
	/**
	 *	Retrieve all transformers
	 */
	public function getTansformers()
	{
		return $this->transformers;
	}
}