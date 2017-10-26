<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \Psr\Http\Message\StreamInterface as PsrStreamInterface;
use \Psr\Http\Message\UploadedFileInterface as PsrUploadedFileInterface;


class BufferElementAbstract extends Element implements BufferElementInterface
{
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
		$path = null;
		
		if(is_resource($input))
		{
			$metadata = stream_get_meta_data($input);
			
			if(isset($metadata["uri"]))
				$path = $metadata["uri"];
		}
		elseif(is_object($input) && $input instanceof PsrUploadedFileInterface)
		{
			$stream = $input->getStream();
			
			if($stream instanceof PsrStreamInterface)
				$path = $stream->getMetadata("uri");
		}
		elseif(is_string($input) && file_exists($input))
		{
			$path = realpath($input);
		}
		
		return $wrapper->validate($constraint, $path, $errors);
	}
}