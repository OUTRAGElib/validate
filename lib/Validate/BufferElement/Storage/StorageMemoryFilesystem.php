<?php


namespace OUTRAGElib\Validate\BufferElement\Storage;

use \Exception;
use \League\Flysystem\Filesystem;
use \League\Flysystem\Memory\MemoryAdapter;
use \Twistor\FlysystemStreamWrapper;


class StorageMemoryFilesystem implements StorageInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getContext($file_name, $file_type = null, $mode = "w+")
	{
		$protocol = $this->getProtocol();
		
		if(empty($file_type))
			$file_type = "application/octet-stream";
		
		return fopen($protocol."://temp/upload/".$file_type."/".$file_name, $mode);
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
		
		$filesystem = new Filesystem(new MemoryAdapter());
		
		if(!FlysystemStreamWrapper::register($protocol, $filesystem))
			throw new Exception("Unable to register stream wrapper");
		
		return $protocol;
	}
}