<?php


namespace OUTRAGElib\Validate\BufferElement\Storage;


class StorageMemory implements StorageInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getContext($file_name, $file_type = null, $mode = "w+")
	{
		if(empty($file_type))
			$file_type = "application/octet-stream";
		
		return fopen("php://temp/upload/".$file_type."/".$file_name, $mode);
	}
}