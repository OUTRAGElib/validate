<?php


namespace OUTRAGElib\Validate\BufferElement;

use \OUTRAGElib\FileStream\FileInterface;
use \OUTRAGElib\Validate\BufferElement\File\Provider\Filesystem as ProviderFilesystem;
use \OUTRAGElib\Validate\BufferElement\File\Provider\FileUpload as ProviderFileUpload;
use \OUTRAGElib\Validate\BufferElement\File\Provider\Http as ProviderHttp;
use \OUTRAGElib\Validate\BufferElement\File\Storage\Temporary as TemporaryStorage;
use \OUTRAGElib\Validate\BufferElementAbstract;
use \RuntimeException;


class FileBufferElement extends BufferElementAbstract
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$file = null;
		$storage = new TemporaryStorage();
		
		# if input is null, we need to check to see if this element exists within
		# the $_FILES object - if so, put in temp stream
		$files = $_FILES;
		
		if(is_null($input) && !empty($files))
		{
			$provider = new ProviderFileUpload($this, $storage);
			$file = $provider->getFile($files);
		}
		elseif(is_string($input) && file_exists($input))
		{
			$provider = new ProviderFilesystem();
			$file = $provider->getFile($input);
		}
		elseif(is_string($input) && in_array(parse_url($input, PHP_URL_SCHEME), [ "http", "https" ]))
		{
			$provider = new ProviderHttp($storage);
			$file = $provider->getFile($input);
		}
		
		# okay, here's where it gets interesting. so, most validation suites out there
		# don't generally care for PSR7, they care more for actual files. this might change
		# later on in the future, but before then, we'll just pretend to do a validation with
		# the stream in question...
		if($file === null)
		{
			$result = parent::validate(null, $context);
		}
		else
		{
			if($file instanceof FileInterface === false)
				throw new RuntimeException("Incorrect file type; expected instance of ".FileInterface::class);
			
			$result = parent::validate($file, $context);
		}
		
		if($result === null)
			return null;
		
		# okay, the facade is over, let's return the *correct* value, which is our new
		# PSR7 compliant file...
		return $file;
	}
}