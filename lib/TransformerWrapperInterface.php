<?php


namespace OUTRAGElib\Validate;


interface TransformerWrapperInterface
{
	/**
	 *	Is this wrapper actually able to be used?
	 */
	public function isAvailable();
	
	
	/**
	 *	Checks to see whether or not this particular type of transformer
	 *	can be accepted by this object
	 */
	public function isTransformable($transformer);
	
	
	/**
	 *	Filters an array of transformers and returns ones that can be used
	 *	to transform values
	 */
	public function filterTransformers($transformers);
	
	
	/**
	 *	Performs a transformation on a value
	 */
	public function transform($transformer, $input);
}