<?php
/**
 *	Element for array input validation for OUTRAGElib.
 */


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\Error\ErrorableInterface;
use \OUTRAGElib\Validate\Error\ErrorMessage;

class Element extends Component
{
	/**
	 *	Stores a list of all conditions that this element depends on for a
	 *	successful validation.
	 */
	protected $constraints = [];
	
	
	/**
	 *	What is the default value?
	 */
	public $default = null;
	
	
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		# first thing we need to check - do we have a root? we should have, regardless
		# of whether or not we're testing this within the bounds of a form
		if(!$this->root)
			throw new Exception("Root element not found");
		
		if($input === null)
			$input = $this->default;
		
		# something to check - if we have something that is an array yet
		# has been defined as an array, we will just go ahead and mark it
		# as being null!
		if(!$this->is_array && is_array($input))
			$input = null;
		
		foreach($this->root->getConstraintWrappers() as $wrapper)
		{
			# a cool feature of this is that we're able to just stick any type
			# of conditional in and as long as the constraint wrapper is available
			# it will just go ahead and test it - how fun, right?
			foreach($wrapper->filterConstraints($this->constraints) as $constraint)
			{
				$errors = [];
				$result = $wrapper->validate($constraint, $input, $errors);
				
				if($result == false)
				{
					if($context != null && $context instanceof ErrorableInterface)
					{
						# it's best for the errors to always be arrays
						if(is_array($errors))
						{
							foreach($errors as $error)
								$context->triggerError($this, $error);
						}
					}
				}
			}
		}
		
		return $input;
	}
	
	
	/**
	 *	So, since we're at this point, we can presume that we're going to either create
	 *	or modify a validator - so we'll do that stuff here!
	 */
	public function __call($constraint, $arguments)
	{
		$matches = [];
		
		if(preg_match("/^(has|remove)([A-Za-z])$/", $constraint, $matches))
		{
			switch($matches[1])
			{
				case "has":
					return $this->hasConstraint($matches[2]);
				break;
				
				case "remove":
					return $this->removeConstraint($matches[2]);
				break;
			}
		}
		else
		{
			return $this->addConstraint($constraint);
		}
	}
	
	
	/**
	 *	Add a validator
	 */
	public function addConstraint($constraint)
	{
		$this->constraints[] = $constraint;
		return $this;
	}
	
	
	/**
	 *	Checks to see if this validator is in use
	 */
	public function hasConstraint($constraint)
	{
	}
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeConstraint($constraint)
	{
	}
}