<?php


namespace OUTRAGElib\Validate;


class BufferElementAbstract extends Element implements BufferElementInterface
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$input = parent::validate($input, $context);
		
		if(!is_resource($input))
			return null;
		
		return $input;
	}
	
	
	/**
	 *	Validate a constraint.
	 *
	 *	If the constraint is unable to accept buffers as an input, we'll
	 *	presume that it must be operating on the file name of the stream
	 *	in question.
	 *
	 *	Use case for this would be presumably, extension of files and checking file
	 *	names for invalid characters...?
	 */
	protected function validateConstraint(ConstraintWrapperInterface $wrapper, $constraint, $input, &$errors = [])
	{
		$value = null;
		
		if(is_resource($input))
		{
			$metadata = stream_get_meta_data($input);
			
			if(isset($metadata["uri"]))
				$value = $metadata["uri"];
		}
		
		return $wrapper->validate($constraint, $value, $errors);
	}
}