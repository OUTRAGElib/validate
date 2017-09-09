<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Delegator\DelegatorTrait;
use \OUTRAGElib\Validate\ConstraintWrapper;
use \OUTRAGElib\Validate\ConstraintWrapperInterface;
use \OUTRAGElib\Validate\Error\ErrorableInterface;
use \OUTRAGElib\Validate\Error\ErrorMessage;


abstract class Component implements ErrorableInterface
{
	/**
	 *	We'd like to use some delegators to make our life ever so easier.
	 */
	use DelegatorTrait;
	
	
	/**
	 *	Store all of our family trees here.
	 */
	public $parent = null;
	
	
	/**
	 *	Chances are that this component will have a name.
	 */
	public $component = null;
	
	
	/**
	 *	This component might even have a label to boot.
	 */
	public $label = null;
	
	
	/**
	 *	Is this an array?
	 */
	public $is_array = false;
	
	
	/**
	 *	We'll store all errors here as well.
	 */
	public $errors = [];
	
	
	/**
	 *	What constraint wrappers are currently in use?
	 */
	protected $constraint_wrappers = [];
	
	
	/**
	 *	What index is this component currently in in some sort
	 *	of pseudo-stack? Useful for validation or rule grabbing.
	 *
	 *	Be aware to clean up after using though!!
	 */
	public $key = null;
	
	
	/**
	 *	Please extend and return this - you'll probably be needing this to
	 *	create your definitions.
	 */
	public function __construct($component = null)
	{
		$this->component = $component;
	}
	
	
	/**
	 *	Returns a list of all accessable parent properties in this scope.
	 *	Do I still need this?
	 */
	public function getter_property_tree($persistant = false)
	{
		$target = $this;
		$tree = [];
		
		while(($target = $target->parent) != null)
		{
			if($target->is_array)
				array_unshift($tree, isset($this->key) ? $this->key : 0);
			
			if($target->component)
				array_unshift($tree, $target->component);
		}
		
		$tree[] = $this->component;
		
		return $tree;
	}
	
	
	/**
	 *	Get the name of this particular component.
	 *
	 *	Rather than cache it, I'll just generate its resolved name every time.
	 *	Shouldn't cause too many problems, right?
	 */
	public function getter_name($persistant = false)
	{
		$return = "";
		
		foreach($this->property_tree as $index => $node)
			$return .= $index ? "[".$node."]" : $node;
		
		if($this->is_array)
			$return .= "[]";
		
		return $return;
	}
	
	
	/**
	 *	Find the top-most element of this component. This is very likely, if not
	 *	always, going to be a template.
	 */
	public function getter_root()
	{
		$pointer = $this->parent;
		
		while($pointer->parent !== null)
		{
			$pointer = $pointer->parent;
			
			if($pointer instanceof Component == false)
				throw new Exception("Invalid parent hierarchy");
		}
		
		return $pointer;
	}
	
	
	/**
	 *	Set the name (key?) of this component.
	 */
	public function setName($name)
	{
		$this->component = $name;
		return $this;
	}
	
	
	/**
	 *	Set the label of this component.
	 */
	public function setLabel($label = null)
	{
		$this->label = $label;
		return $this;
	}
	
	
	/**
	 *	Set the array capability of this component.
	 */
	public function setIsArray($value = null)
	{
		$this->is_array = $value && true;
		return $this;
	}
	
	
	/**
	 *	Appends this element to a input template.
	 */
	public function appendTo(ElementList $element)
	{
		$element->append($this);
		return $this;
	}
	
	
	/**
	 *	Add an error to this component.
	 */
	public function triggerError($context, $message = null)
	{
		$error = new Error\Message();
		
		$error->name = $context->name;
		$error->context = $context;
		$error->message = $message;
		
		$this->errors[] = $error;
		return $this;
	}
	
	
	/**
	 *	Retrieve errors against this element.
	 */
	public function getErrors($named = true)
	{
		if(!$named)
			return $this->errors;
		
		$errors = [];
		
		foreach($this->errors as $error)
		{
			if(!isset($errors[$error->name]))
				$errors[$error->name] = [];
			
			$errors[$error->name][] = $error->message;
		}
		
		return $errors; 
	}
	
	
	/**
	 *	Adds a constraint wrapper to the validation request
	 */
	public function addConstraintWrapper(ConstraintWrapperInterface $wrapper)
	{
		$this->constraint_wrappers[] = $wrapper;
		return $this;
	}
	
	
	/**
	 *	Retrieves all constraint wrappers
	 */
	public function getConstraintWrappers()
	{
		# okay, so if the constraint wrappers are empty, we're going to have to
		# populate them, fun times...
		# we'll just for the moment use the four below - let's see how nice this will end up
		if(empty($this->constraint_wrappers))
		{
			foreach([ "OUTRAGElib", "Callback", "Symfony", "Zend" ] as $name)
			{
				$class = '\OUTRAGElib\Validate\ConstraintWrapper\\'.$name;
				$object = new $class();
				
				if($object->isAvailable())
					$this->constraint_wrappers[] = $object;
			}
		}
		
		return $this->constraint_wrappers;
	}
	
	
	/**
	 *	Turns this component into a string.
	 */
	public function __toString()
	{
		return (string) ($this->component ?: "");
	}
	
	
	/**
	 *	ObjectListInterface interface: Creates a duplicate of this container
	 */
	public function duplicate()
	{
		return clone $this;
	}
}