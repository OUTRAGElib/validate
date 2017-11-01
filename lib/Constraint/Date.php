<?php


namespace OUTRAGElib\Validate\Constraint;

use \DateTime;
use \DateTimeZone;
use \OUTRAGElib\Validate\ConstraintAbstract;


class Date extends ConstraintAbstract
{
	/**
	 *	We'll want to save some key data here...
	 */
	protected $pattern = null;
	protected $result = null;
	
	
	/**
	 *	Called to set the arguments.
	 */
	public function init($pattern)
	{
		$this->pattern = (array) $pattern;
	}
	
	
	/**
	 *	We need to check that this is a valid date constraint.
	 */
	public function test($input)
	{
		if($this->pattern)
		{
			$timezone = new DateTimeZone("Europe/London");
			$date = new DateTime("now", $timezone);
			
			foreach($this->pattern as $item)
			{
				if($this->result = $date->createFromFormat($item, $input, $timezone))
					return true;
			}
			
			if($input)
			{
				$this->error = "Value not a supported date format.";
				return false;
			}
		}
		
		return true;
	}
}