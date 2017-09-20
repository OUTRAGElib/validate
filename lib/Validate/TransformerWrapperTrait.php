<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\TransformerWrapper;


trait TransformerWrapperTrait
{
	/**
	 *	What transformer wrappers are currently in use?
	 */
	protected $transformer_wrappers = [];
	
	
	/**
	 *	Adds a transformer wrapper to the validation request
	 */
	public function addTransformerWrapper(TransformerWrapperInterface $wrapper)
	{
		$this->transformer_wrappers[] = $wrapper;
		return $this;
	}
	
	
	/**
	 *	Retrieves all transformer wrappers
	 */
	public function getTransformerWrappers()
	{
		# okay, so if the transformer wrappers are empty, we're going to have to
		# populate them, fun times...
		# we'll just for the moment use the four below - let's see how nice this will end up
		if(empty($this->transformer_wrappers))
		{
			foreach([ TransformerWrapper\OUTRAGElib::class, TransformerWrapper\Callback::class ] as $class)
			{
				$object = new $class();
				
				if($object->isAvailable())
					$this->transformer_wrappers[] = $object;
			}
		}
		
		return $this->transformer_wrappers;
	}
}