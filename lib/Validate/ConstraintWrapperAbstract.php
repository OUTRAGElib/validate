<?php


namespace OUTRAGElib\Validate;


abstract class ConstraintWrapperAbstract implements ConstraintWrapperInterface
{
	/**
	 *	Validates the specified constraints against an input
	 */
	public function validate($constraint, $input, &$errors = [])
	{
		$result = $this->test($constraint, $input);
		
		# well, we only want errors when they're being output, right?
		if($result === false)
			$errors = $this->getErrors($constraint);
		
		return $result;
	}
	
	
	/**
	 *	Filters an array of constraints and returns ones that can be
	 *	validated
	 */
	public function filterConstraints($constraints)
	{
		$list = array();
		
		foreach($constraints as $constraint)
		{
			# we're wanting to clone as we do not want these error messages to make
			# their way to global scope
			if($this->isTestable($constraint))
				$list[] = clone $constraint;
		}
		
		return $list;
	}
	
	
	/**
	 *	Validates the specified constraints against an input
	 */
	abstract protected function test($constraint, $input);
	
	
	/**
	 *	Retrieves the messages that were set
	 */
	abstract protected function getErrors($constraint);
}