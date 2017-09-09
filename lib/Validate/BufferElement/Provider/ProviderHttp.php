<?php


namespace OUTRAGElib\Validate\BufferElement\Provider;

use \Exception;
use \GuzzleHttp\Client;
use \OUTRAGElib\Validate\BufferElement\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


class ProviderHttp implements ProviderInterface
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
		$sink = fopen("php://temp", "w+");
		
		$client = new Client();
		$response = $client->request("GET", $input, [ "sink" => $sink ]);
		
		$file_type = null;
		$file_name = null;
		
		# first port of call - do we have a 'Content-Disposition' header? if we do
		# we can use this to sniff out the file name
		if($response->hasHeader("Content-Disposition"))
		{
			$components = [];
			
			foreach($response->getHeader("Content-Disposition") as $header)
			{
				foreach(explode(";", $header) as $chunk)
				{
					$stack = [];
					
					parse_str(trim($chunk), $stack);
					
					foreach($stack as $key => $value)
					{
						if(substr($value, 0, 1) == '"' && substr($value, -1, 1) == '"')
							$stack[$key] = substr(stripslashes($value), 1, -1);
					}
					
					if($stack)
						$components = array_merge($components, $stack);
				}
			}
			
			if(!empty($components["filename"]))
				$file_name = $components["filename"];
		}
		
		# second port of call - just go head and do basename on the URL that we have been
		# provided with - probably for the best
		if(empty($file_name))
			$file_name = basename(parse_url($input, PHP_URL_PATH));
		
		# now we need to look for the mime-type!
		if($response->hasHeader("Content-Type"))
			$file_type = $response->getHeader("Content-Type")[0];
		
		# great, now get our pointer
		$fp = $this->storage->getContext($file_name, $file_type);
		
		if(!is_resource($fp))
			return null;
		
		stream_copy_to_stream($sink, $fp);
		fclose($sink);
		rewind($fp);
		
		return $fp;
	}
}