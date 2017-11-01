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
		foreach($this->transformers as $item)
		{
			if(is_string($transformer) && is_a($item, $transformer))
				return true;
			elseif(is_object($transformer) && $transformer === $item)
				return true;
		}
		
		return false;
	}
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeTransformer($transformer)
	{
		foreach($this->transformers as $index => $item)
		{
			if(is_string($transformer) && is_a($item, $transformer))
				unset($this->transformers[$index]);
			elseif(is_object($transformer) && $transformer === $item)
				unset($this->transformers[$index]);
		}
		
		return $this;
	}
	
	
	/**
	 *	Retrieve all transformers
	 */
	public function getTransformers($transformer = null)
	{
		if(is_null($transformer))
			return $this->transformers;
		
		$list = [];
		
		foreach($this->transformers as $item)
		{
			if(is_string($transformer) && is_a($item, $transformer))
				$list[] = $item;
			elseif(is_object($transformer) && $transformer === $item)
				$list[] = $item;
		}
		
		return $list;
	}
}