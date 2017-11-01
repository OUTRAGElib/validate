<?php


namespace OUTRAGElib\Validate\BufferElement\File\Provider;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\File\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;
use \OUTRAGElib\FileStream\File;
use \OUTRAGElib\FileStream\Stream;


class Filesystem implements ProviderInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getFile($input)
	{
		# the PSR7 specification requires that we have a stream, and derived from
		# the stream we have a file.		
		if(!file_exists($input))
			return false;
		
		$fp = fopen($input, "r");
		
		if(!is_resource($fp))
			return false;
		
		# firstly, we need to build a stream
		$stream = new Stream();
		$stream->setFilePointer($fp);
		
		# and now build the file object
		$file = new File();
		
		$file->setStream($stream);
		$file->setClientFilename(basename($input));
		
		return $file;
	}
}