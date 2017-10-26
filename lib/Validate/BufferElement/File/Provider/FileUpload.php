<?php


namespace OUTRAGElib\Validate\BufferElement\File\Provider;

use \Exception;
use \OUTRAGElib\Validate\BufferElement\File\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


class FileUpload implements ProviderInterface
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
		# retrieve some stuff
		$data = [
			"type" => null,
			"name" => null,
			"tmp_name" => null,
			"error" => null,
		];
		
		foreach($data as $key => $value)
			$data[$key] = $this->parseFileArray($this->element, $input, $key);
		
		# okay, now we have everything, we're going to open the temp file (and
		# not copy it over because this isn't required) - i'm hoping that this
		# sort of naming scheme won't cause any issues with filters and the like?
		$fp = null;
		
		if(!is_null($data["tmp_name"]) || file_exists($data["tmp_name"]))
		{
			$sink = fopen($data["tmp_name"], "r");
			
			# great, now get our pointer
			if(is_resource($sink))
			{
				$fp = $this->storage->open($file_name);
				
				rewind($sink);
				stream_copy_to_stream($sink, $fp);
				fclose($sink);
				
				rewind($fp);
			}
		}
		
		# then, we need to build a stream
		$stream = new Stream();
		$stream->setFilePointer($fp);
		
		# and now build the file object
		$file = new File();
		$file->setStream($stream);
		
		if(isset($fp))
		{
			if(isset($data["type"]))
				$file->setClientMediaType($data["type"]);
			
			if(isset($data["name"]))
				$file->setClientFilename($data["name"]);
			
			if(isset($data["error"]))
				$file->setError($data["error"]);
		}
		
		return $file;
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