<?php


namespace OUTRAGElib\Validate\BufferElement\Provider;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


class ProviderFileUpload implements ProviderInterface
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
		$file_type = $this->parseFileArray($this->element, $input, "type");
		$file_name = $this->parseFileArray($this->element, $input, "name");
		$tmp_name = $this->parseFileArray($this->element, $input, "tmp_name");
		
		# okay, now we have everything, we're going to open the temp file (and
		# not copy it over because this isn't required) - i'm hoping that this
		# sort of naming scheme won't cause any issues with filters and the like?
		if(is_null($tmp_name) || !file_exists($tmp_name))
			return null;
		
		$tmp_fp = fopen($tmp_name, "r");
		
		if(!is_resource($tmp_fp))
			return null;
		
		# great, now get our pointer
		$fp = $this->storage->getContext($file_name, $file_type);
		
		if(!is_resource($fp))
			return null;
		
		stream_copy_to_stream($tmp_fp, $fp);
		fclose($tmp_fp);
		rewind($fp);
		
		return $fp;
	}
	
	
	/**
	 *	Parses a $_FILES array to retrieve any given property
	 */
	protected function parseFileArray(ElementInterface $element, $files, $attribute)
	{
		# let's get the 'name' property of this upload
		$tree = $element->property_tree;
		
		array_splice($tree, 1, 0, [ $attribute ]);
		
		$levels = count($tree);
		
		for($i = 0; $i < $levels; ++$i)
		{
			if(isset($files[$tree[$i]]))
			{
				if(is_array($files[$tree[$i]]))
					$files = $files[$tree[$i]];
				else
					return $files[$tree[$i]];
			}
		}
		
		return null;
	}
}