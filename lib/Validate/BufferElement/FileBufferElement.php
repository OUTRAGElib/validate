<?php


namespace OUTRAGElib\Validate\BufferElement;

use \Exception;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\BufferElementInterface;


class FileBufferElement extends Element implements BufferElementInterface
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$fp = false;
		
		# if input is null, we need to check to see if this element exists within
		# the $_FILES object - if so, put in temp stream
		if(!empty($_FILES) && $input === null)
		{
			$name = $this->parseFileArray($_FILES, "name");
			$type = $this->parseFileArray($_FILES, "type");
			$tmp_name = $this->parseFileArray($_FILES, "tmp_name");
			
			# okay, now we have everything, we're going to open the temp file (and
			# not copy it over because this isn't required) - i'm hoping that this
			# sort of naming scheme won't cause any issues with filters and the like?
			if(is_null($tmp_name) || !file_exists($tmp_name))
				return false;
			
			$temp_fp = fopen($tmp_name, "r");
			
			if(!is_resource($temp_fp))
				return false;
			
			$fp = fopen("php://temp/upload/".$type."/".$name, "w+");
			
			if(!is_resource($fp))
				return false;
			
			stream_copy_to_stream($temp_fp, $fp);
			fclose($temp_fp);
			rewind($fp);
		}
		elseif(is_string($input) && file_exists($input))
		{
			# otherwise just load the input from here
			$fp = fopen($input, "r");
			
			if(!is_resource($fp))
				return false;
		}
		
		$fp = parent::validate($fp, $context);
		
		# just a final sanity check
		if(!is_resource($fp))
			return false;
		
		return $fp;
	}
	
	
	/**
	 *	Parses a $_FILES array to retrieve any given property
	 */
	protected function parseFileArray($files, $attribute)
	{
		# let's get the 'name' property of this upload
		$tree = $this->property_tree;
		
		array_splice($tree, 1, 0, [ $attribute ]);
		
		$levels = count($tree);
		
		for($i = 0; $i <= $levels; ++$i)
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