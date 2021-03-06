<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\ErrorInterface;


class Element extends Node implements ElementInterface
{
	/**
	 *	Include constraint functionality
	 */
	use ConstraintTrait;
	
	
	/**
	 *	Include transformer functionality
	 */
	use TransformerTrait;
	
	
	/**
	 *	What is the default value?
	 */
	public $default = null;
	
	
	/**
	 *	Tests are similar to validation calls - but instead of returning the value
	 *	which may have been changed somewhere, it just returns a boolean. How cute!
	 */
	public function test($input)
	{
		$this->errors = [];
		$this->validate($input, $this);
		
		return count($this->errors) == 0;
	}
	
	
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		# first thing we need to check - do we have a root? we should have, regardless
		# of whether or not we're testing this within the bounds of a form
		if(!$this->root)
			throw new Exception("Root element not found");
		
		# if no context is passed, stick all errors on this element
		if(is_null($context))
			$context = $this;
		
		if($input === null)
			$input = $this->default;
		
		# something to check - if we have something that is an array yet
		# has been defined as an array, we will just go ahead and mark it
		# as being null!
		if(!$this->is_array && is_array($input))
			$input = null;
		
		# if we have any transformers applied, we need to go ahead and transform
		# our transformers
		if(count($this->transformers) > 0)
		{
			foreach($this->root->getTransformerWrappers() as $wrapper)
			{
				# a cool feature of this is that we're able to just stick any type
				# of transformer in and as long as the transformer wrapper is available
				# it will just go ahead and test it - how fun, right?
				foreach($wrapper->filterTransformers($this->transformers) as $transformer)
				{
					if(($result = $wrapper->transform($transformer, $input)) !== null)
						$input = $result;
				}
			}
		}
		
		# something that might be worth checking is seeing whether or not this
		# element is able to be processed.
		# 
		# previously, if a null/undefined value was passed here, then the validators
		# will always be run.
		# now, if the field is not marked as required, and the field is null, we will
		# skip all processing. this of course takes into account default values, as per
		# the code block above.
		$process = true;
		
		if($this instanceof BufferElementInterface === false)
			$process = !is_null($input) || $this->isRequired();
		
		if($process && count($this->constraints) > 0)
		{
			foreach($this->root->getConstraintWrappers() as $wrapper)
			{
				# a cool feature of this is that we're able to just stick any type
				# of conditional in and as long as the constraint wrapper is available
				# it will just go ahead and test it - how fun, right?
				foreach($wrapper->filterConstraints($this->constraints) as $constraint)
				{
					$errors = [];
					$result = $this->validateConstraint($wrapper, $constraint, $input, $errors);
					
					if($result == false)
					{
						if(is_object($context) && $context instanceof ErrorInterface)
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
		}
		
		return $input;
	}
	
	
	/**
	 *	Validate a constraint
	 */
	protected function validateConstraint(ConstraintWrapperInterface $wrapper, $constraint, $input, &$errors = [])
	{
		return $wrapper->validate($constraint, $input, $errors);
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
			$factory = new ConstraintFactory($constraint);
			$output = $factory->getClass();
			
			if(class_exists($output))
			{
				if($list = $this->getConstraints($output))
				{
					foreach($list as $item)
					{
						if(method_exists($item, "init"))
							call_user_func_array([ $item, "init" ], $arguments);
					}
				}
				else
				{
					$this->addConstraint($factory->getConstraint($arguments));
				}
				
				return $this;
			}
		}
		
		throw new Exception("Method '".$constraint."' not found");
	}
	
	
	/**
	 *	Set the default value
	 */
	public function setDefault($default)
	{
		$this->default = $default;
		return $this;
	}
	
	
	/**
	 *	Retrieve the default value
	 */
	public function getDefault()
	{
		return $this->default;
	}
}