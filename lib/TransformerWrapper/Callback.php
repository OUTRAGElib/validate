<?php


namespace OUTRAGElib\Validate\TransformerWrapper;

use \Closure;
use \Exception;
use \OUTRAGElib\Validate\TransformerWrapperInterface;
use \ReflectionFunction;
use \ReflectionObject;


class Callback implements TransformerWrapperInterface
{
	/**
	 *	Is this wrapper actually able to be used?
	 */
	public function isAvailable()
	{
		return class_exists(Closure::class);
	}
	
	
	/**
	 *	Checks to see whether or not this particular type of constraint
	 *	can be accepted by this object
	 */
	public function isTransformable($transformer)
	{
		if(is_object($transformer) && $transformer instanceof Closure)
			return true;
		elseif(is_string($transformer) && function_exists($transformer))
			return true;
		elseif(is_array($transformer) && is_object($transformer[0]) && method_exists($transformer[0], $transformer[1]))
			return true;
		
		return false;
	}
	
	
	/**
	 *	Filters an array of constraints and returns ones that can be
	 *	validated
	 */
	public function filterTransformers($transformers)
	{
		$list = array();
		
		foreach($transformers as $transformer)
		{
			if($this->isTransformable($transformer))
				$list[] = $this->toClosure($transformer);
		}
		
		return $list;
	}
	
	
	/**
	 *	Validates the specified transformer against an input
	 */
	public function transform($transformer, $input)
	{
		return $transformer($input);
	}
	
	
	/**
	 *	Converts any sort of callable function into a closure, ready for evaluation
	 */
	protected function toClosure(callable $transformer)
	{
		if($transformer instanceof Closure)
		{
			return $transformer;
		}
		elseif(is_string($transformer) && function_exists($transformer))
		{
			return (new ReflectionFunction($transformer))->getClosure();
		}
		elseif(is_array($transformer) && is_object($transformer[0]) && method_exists($transformer[0], $transformer[1]))
		{
			$reflector = new ReflectionObject($transformer[0]);
			
			if($reflector->hasMethod($transformer[1]))
				return $reflector->getMethod($transformer[1])->getClosure($transformer[0]);
		}
		
		throw new Exception("Unable to turn callback into closure");
	}
}