<?php
/**
 *	Element for array input validation for OUTRAGElib.
 */


namespace OUTRAGElib\Validate;

use \OUTRAGElib\Validate\Error\ErrorableInterface;
use \OUTRAGElib\Validate\Error\ErrorMessage;

class Element extends Component
{
	/**
	 *	Stores a list of all conditions that this element depends on for a
	 *	successful validation.
	 */
	protected $conditions = [];
	
	
	/**
	 *	What is the default value?
	 */
	public $default = null;
	
	
	/**
	 *	Perform a validation on this element based on the condition.
	 */
	public function validate($input, $context = null)
	{
		if($input === null)
			$input = $this->default;
		
		# something to check - if we have something that is an array yet
		# has been defined as an array, we will just go ahead and mark it
		# as being null!
		if(!$this->is_array && is_array($input))
			$input = null;
		
		$result = $input;
		
		foreach($this->conditions as $condition)
		{
			if($condition->clean()->validate($result))
			{
				if($context != null && $context instanceof ErrorableInterface)
					$context->triggerError($this, $condition->error());
			}
			
			if($condition instanceof Transformer)
				$result = $condition->transform($result);
		}
		
		return $result;
	}
	
	
	/**
	 *	So, since we're at this point, we can presume that we're going to either create
	 *	or modify a validator - so we'll do that stuff here!
	 */
	public function __call($condition, $arguments)
	{
		$matches = [];
		
		if(preg_match("/^(has|remove)([A-Za-z])$/", $condition, $matches))
		{
			switch($matches[1])
			{
				case "has":
					return $this->hasCondition($matches[2]);
				break;
				
				case "remove":
					return $this->removeCondition($matches[2]);
				break;
			}
		}
		else
		{
			return $this->addCondition($condition, $arguments);
		}
	}
	
	
	/**
	 *	Add a validator
	 */
	public function addCondition($condition, $arguments = [])
	{
		if(!is_object($condition))
		{
			$class = "\\OUTRAGElib\\Validate\\Conditions\\".ucfirst($condition);
			
			if(!class_exists($class))
				throw new \Exception("Invalid validator condition: '".$condition."'");
			
			$condition = new $class();
		}
		
		$this->conditions[] = $condition;
		
		# deals with this library's condition set
		if($condition instanceof Condition)
		{
			if(!empty($arguments))
			{
				if(method_exists($condition, "methodArgs"))
					call_user_func_array([ $condition, "methodArgs" ], $arguments);
			}
		}
		
		return $this;
	}
	
	
	/**
	 *	Checks to see if this validator is in use
	 */
	public function hasCondition($condition)
	{
		# for those objects
		if(is_object($condition))
			return in_array($condition, $this->conditions, true);
		
		if(is_string($condition))
		{
			if(class_exists($condition))
			{
				# for those full paths
				foreach($this->conditions as $item)
				{
					if(get_class($item) == $condition)
						return true;
				}
			}
			elseif(class_exists("\\OUTRAGElib\\Validate\\Conditions\\".ucfirst($condition)))
			{
				# for those short paths
				foreach($this->conditions as $item)
				{
					if("\\".get_class($item) == "\\OUTRAGElib\\Validate\\Conditions\\".ucfirst($condition))
						return true;
				}
			}
		}
		
		return false;
	}
	
	
	/**
	 *	Removes all conditions that match what is provided
	 */
	public function removeCondition($condition)
	{
		# for the objects
		if(is_object($condition))
		{
			$key = array_search($condition, $this->conditions);
			
			if($key !== false)
				unset($this->conditions[$key]);
		}
		
		if(is_string($condition))
		{
			if(class_exists($condition))
			{
				# for those full paths
				foreach($this->conditions as $key => $item)
				{
					if(get_class($item) == $condition)
						unset($this->conditions[$key]);
				}
			}
			elseif(class_exists("\\OUTRAGElib\\Validate\\Conditions\\".ucfirst($condition)))
			{
				# for those short paths
				foreach($this->conditions as $key => $item)
				{
					if("\\".get_class($item) == "\\OUTRAGElib\\Validate\\Conditions\\".ucfirst($condition))
						unset($this->conditions[$key]);
				}
			}
		}
		
		return $this;
	}
}