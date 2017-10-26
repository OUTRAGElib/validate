<?php


namespace OUTRAGElib\Validate\BufferElement\File\Storage;

use \Exception;
use \OUTRAGElib\Filesystem\TemporaryFilesystemStreamWrapper;


class Temporary implements StorageInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function open($filename, $mode = "w+")
	{
		$path = sprintf("%s://%s/%s", $this->getProtocol(), "temp/upload/".sha1(uniqid()), $filename);
		
		return fopen($path, $mode);
	}
	
	
	/**
	 *	What protocol are we going to be using?
	 *	(hint: will be some lovely custom name or something)
	 */
	protected function getProtocol()
	{
		$protocol = "outragelib-validate-buffer";
		
		if(in_array($protocol, stream_get_wrappers()))
			return $protocol;
		
		stream_wrapper_register($protocol, TemporaryFilesystemStreamWrapper::class);
		
		return $protocol;
	}
}