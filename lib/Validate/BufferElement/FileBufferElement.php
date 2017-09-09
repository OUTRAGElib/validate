<?php


namespace OUTRAGElib\Validate\BufferElement;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\Provider;
use \OUTRAGElib\Validate\BufferElementAbstract;
use \OUTRAGElib\Validate\BufferElement\Storage\StorageMemoryFilesystem;


class FileBufferElement extends BufferElementAbstract
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$fp = null;
		$storage = new StorageMemoryFilesystem();
		
		# if input is null, we need to check to see if this element exists within
		# the $_FILES object - if so, put in temp stream
		if(is_null($input) && !empty($_FILES))
			$fp = (new Provider\ProviderFileUpload($this, $storage))->getContext($_FILES);
		elseif(is_string($input) && file_exists($input))
			$fp = (new Provider\ProviderFilesystem($this, $storage))->getContext($input);
		elseif(is_string($input) && in_array(parse_url($input, PHP_URL_SCHEME), [ "http", "https" ]))
			$fp = (new Provider\ProviderHttp($this, $storage))->getContext($input);
		
		return parent::validate($fp, $context);
	}
}