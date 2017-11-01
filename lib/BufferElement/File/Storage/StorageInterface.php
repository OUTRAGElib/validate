<?php


namespace OUTRAGElib\Validate\BufferElement\File\Storage;


interface StorageInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function open($filename, $mode = "w+");
}