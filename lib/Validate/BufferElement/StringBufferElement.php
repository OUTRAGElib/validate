<?php


namespace OUTRAGElib\Validate\BufferElement;

use \Exception;
use \OUTRAGElib\Validate\Element;
use \OUTRAGElib\Validate\BufferElementAbstract;


class StringBufferElement extends BufferElementAbstract
{
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		$fp = null;
		
		if(is_resource($input))
		{
			$fp = $input;
		}
		elseif(!is_null($input))
		{
			# the pattern should be type, mime/type, name
			$fp = fopen("php://temp/string/application/text/buffer.txt", "w+");
			
			fwrite($fp, $input);
			rewind($fp);
		}
		
		return parent::validate($fp, $context);
	}
}