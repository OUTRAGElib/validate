<?php


namespace OUTRAGElib\Validate\BufferElement\Storage;


interface StorageInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getContext($file_name, $file_type = null, $mode = "w+");
}