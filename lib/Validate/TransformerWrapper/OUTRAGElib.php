<?php


namespace OUTRAGElib\Validate\TransformerWrapper;

use \Exception;
use \OUTRAGElib\Validate\TransformerInterface;
use \OUTRAGElib\Validate\TransformerWrapperInterface;


class OUTRAGElib implements TransformerWrapperInterface
{
	/**
	 *	Is this wrapper actually able to be used?
	 */
	public function isAvailable()
	{
		return interface_exists(TransformerInterface::class);
	}
	
	
	/**
	 *	Checks to see whether or not this particular type of transformer
	 *	can be accepted by this object
	 */
	public function isTransformable($transformer)
	{
		if(is_object($transformer))
			return $transformer instanceof TransformerInterface;
		
		return false;
	}
	
	
	/**
	 *	Filters an array of transformers and returns ones that can be
	 *	validated
	 */
	public function filterTransformers($transformers)
	{
		$list = array();
		
		foreach($transformers as $transformer)
		{
			# we're wanting to clone as we do not want these error messages to make
			# their way to global scope
			if($this->isTransformable($transformer))
				$list[] = clone $transformer;
		}
		
		return $list;
	}
	
	
	/**
	 *	Validates the specified transformer against an input
	 */
	public function transform($transformer, $input)
	{
		return $transformer->transform($input);
	}
}