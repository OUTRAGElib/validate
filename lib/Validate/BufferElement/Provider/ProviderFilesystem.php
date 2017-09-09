<?php


namespace OUTRAGElib\Validate\BufferElement\Provider;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


class ProviderFilesystem implements ProviderInterface
{
	/**
	 *	Store the element
	 */
	protected $element = null;
	
	
	/**
	 *	Store the storage helper
	 */
	protected $storage = null;
	
	
	/**
	 *	Populate the provider with input to parse
	 */
	public function __construct(ElementInterface $element, StorageInterface $storage)
	{
		$this->element = $element;
		$this->storage = $storage;
	}
	
	
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getContext($input)
	{
		if(file_exists($input))
			return fopen($input, "r");
		
		return null;
	}
}