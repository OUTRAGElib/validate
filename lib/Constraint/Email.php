<?php


namespace OUTRAGElib\Validate\Constraint;

use \Exception;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Email extends ConstraintAbstract
{
	/**
	 *	Are we going to check this or not then?
	 */
	protected $perform = false;
	
	
	/**
	 *	Called whenever arguments are passed to the condition.
	 */
	public function init($perform = true)
	{
		$this->perform = (boolean) $perform;
	}
	
	
	/**
	 *	Called to make sure that this value indeed an e-mail address.
	 */
	public function test($input)
	{
		if($this->perform)
		{
			if($this->validateEmail($input))
				return true;
			
			$this->error = "Value not a valid e-mail address.";
			return false;
		}
		
		return true;
	}
	
	
	/**
	 *	Utility function to properly validate an e-mail address - hopefully
	 *	all of the RFC will be covered here.
	 *
	 *	Nope, we won't check for DNS records here - that's awfully slow.
	 */
	protected function validateEmail($input)
	{
		$ampersat = strrpos($input, "@");
		
		if($ampersat === false)
			return false;
		
		$local = substr($input, 0, $ampersat);
		$domain = substr($input, $ampersat + 1);
		
		$local_len = strlen($local);
		$domain_len = strlen($domain);
		
		if(($local_len < 1 || $local_len > 64) || ($domain_len < 1 || $domain_len > 64))
			return false;
		
		if($local[0] == "." || $local[$local_len - 1] == ".")
			return false;
		
		if(preg_match("/\\.\\./", $local))
			return false;
		
		if(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			return false;
		
		if(preg_match('/\\.\\./', $domain))
			return false;
		
		if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local)))
		{
			if(!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
				return false;
		}
		
		return true;
	}
}