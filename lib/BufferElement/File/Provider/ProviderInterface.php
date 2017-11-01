<?php


namespace OUTRAGElib\Validate\BufferElement\File\Provider;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\File\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


interface ProviderInterface
{
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getFile($input);
}