<?php


namespace OUTRAGElib\Validate\BufferElement\File\Provider;

use \Exception;
use \GuzzleHttp\Client;
use \OUTRAGElib\FileStream\File;
use \OUTRAGElib\FileStream\Stream;
use \OUTRAGElib\Validate\BufferElement\File\Storage\StorageInterface;
use \OUTRAGElib\Validate\ElementInterface;


class Http implements ProviderInterface
{
	/**
	 *	Store the storage helper
	 */
	protected $storage = null;
	
	
	/**
	 *	Populate the provider with input to parse
	 */
	public function __construct(StorageInterface $storage)
	{
		$this->storage = $storage;
	}
	
	
	/**
	 *	Retrieve the context that has been generated
	 */
	public function getFile($input)
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
		
		# something that might not necessarily matter to anyone else apart from the
		# darling developer, is the requirement for temporary paths to be at least like
		# what has been downloaded from the server...
		$fp = $this->storage->open($file_name);
		
		rewind($sink);
		stream_copy_to_stream($sink, $fp);
		fclose($sink);
		
		rewind($fp);
		
		# now, we can finally start building a stream
		$stream = new Stream();
		$stream->setFilePointer($fp);
		
		# and now build the file object
		$file = new File();
		
		$file->setStream($stream);
		$file->setClientFilename($file_name);
		$file->setClientMediaType($file_type);
		
		return $file;
	}
}