<?php


namespace OUTRAGElib\Validate\BufferElement\Provider;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


interface ProviderInterface
{
	/**
	 *	Populate the provider with input to parse
	 */
	public function __construct(ElementInterface $element, StorageInterface $storage);
	
	
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getContext($input);
}