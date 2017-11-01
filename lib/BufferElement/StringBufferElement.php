<?php


namespace OUTRAGElib\Validate\BufferElement;

use \Exception;
use \OUTRAGElib\FileStream\File;
use \OUTRAGElib\FileStream\Stream;
use \OUTRAGElib\Validate\BufferElement\File\Storage\Temporary as TemporaryStorage;
use \OUTRAGElib\Validate\BufferElementAbstract;
use \OUTRAGElib\Validate\Element;


class StringBufferElement extends BufferElementAbstract
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$stream = null;
		
		$stream_file_name = "buffer.txt";
		$stream_mime_type = "application/text";
		
		if(is_resource($input))
		{
			$stream = new Stream();
			$stream->setFilePointer($input);
		}
		elseif(!is_null($input))
		{
			$storage = new TemporaryStorage();
			
			if($fp = $storage->open($stream_file_name))
			{
				fwrite($fp, $input);
				rewind($fp);
				
				$stream = new Stream();
				$stream->setFilePointer($fp);
			}
		}
		
		# and now build the file object
		if(isset($stream))
		{
			$file = new File();
			
			$file->setStream($stream);
			$file->setClientFilename($stream_file_name);
			$file->setClientMediaType($stream_mime_type);
			
			if(parent::validate($file, $context) === null)
				return null;
			
			return $file;
		}
		
		return parent::validate(null, $context);
	}
}