<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\ConstraintWrapper;


trait ConstraintWrapperTrait
{
	/**
	 *	What constraint wrappers are currently in use?
	 */
	protected $constraint_wrappers = [];
	
	
	/**
	 *	Adds a constraint wrapper to the validation request
	 */
	public function addConstraintWrapper(ConstraintWrapperInterface $wrapper)
	{
		$this->constraint_wrappers[] = $wrapper;
		return $this;
	}
	
	
	/**
	 *	Retrieves all constraint wrappers
	 */
	public function getConstraintWrappers()
	{
		# okay, so if the constraint wrappers are empty, we're going to have to
		# populate them, fun times...
		# we'll just for the moment use the four below - let's see how nice this will end up
		if(empty($this->constraint_wrappers))
		{
			foreach([ ConstraintWrapper\OUTRAGElib::class, ConstraintWrapper\Callback::class, ConstraintWrapper\Symfony::class, ConstraintWrapper\Zend::class ] as $class)
			{
				$object = new $class();
				
				if($object->isAvailable())
					$this->constraint_wrappers[] = $object;
			}
		}
		
		return $this->constraint_wrappers;
	}
}