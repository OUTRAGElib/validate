<?php


namespace OUTRAGElib\Validate;


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
}