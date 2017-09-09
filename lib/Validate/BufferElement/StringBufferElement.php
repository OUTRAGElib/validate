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
		# the pattern should be type, mime/type, name
		$fp = fopen("php://temp/string/application/text/buffer.txt", "w+");
		
		if($input !== null)
		{
			fwrite($fp, $input);
			rewind($fp);
		}
		
		return parent::validate($fp, $context);
	}
}