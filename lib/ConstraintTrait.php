<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\Constraint\Required;


trait ConstraintTrait
{
	/**
	 *	Stores a list of all conditions that this element depends on for a
	 *	successful validation.
	 */
	protected $constraints = [];
	
	
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
		foreach($this->constraints as $item)
		{
			if(is_string($constraint) && is_a($item, $constraint))
				return true;
			elseif(is_object($constraint) && $constraint === $item)
				return true;
		}
		
		return false;
	}
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeConstraint($constraint)
	{
		foreach($this->constraints as $index => $item)
		{
			if(is_string($constraint) && is_a($item, $constraint))
				unset($this->constraints[$index]);
			elseif(is_object($constraint) && $constraint === $item)
				unset($this->constraints[$index]);
		}
		
		return $this;
	}
	
	
	/**
	 *	Retrieve all constraints
	 */
	public function getConstraints($constraint = null)
	{
		if(is_null($constraint))
			return $this->constraints;
		
		$list = [];
		
		foreach($this->constraints as $item)
		{
			if(is_string($constraint) && is_a($item, $constraint))
				$list[] = $item;
			elseif(is_object($constraint) && $constraint === $item)
				$list[] = $item;
		}
		
		return $list;
	}
	
	
	/**
	 *	Set this element as being required, using the Required constraint
	 */
	public function setRequired($required)
	{
		$required = $required && true;
		
		if($this->constraints)
			$this->removeConstraint(Required::class);
		
		$this->addConstraint(new Required($required));
		
		return $this;
	}
	
	
	/**
	 *	Retrieve whether or not this element is required
	 */
	public function isRequired()
	{
		$required = false;
		
		if($this->constraints)
		{
			foreach($this->getConstraints(Required::class) as $constraint)
				$required = $required || $constraint->isRequired();
		}
		
		return $required;
	}
}