<?php


namespace OUTRAGElib\Validate;

use \Exception;
use \OUTRAGElib\Validate\Error\ErrorMessage;


trait ErrorTrait
{
	/**
	 *	We'll store all errors here as well.
	 */
	protected $errors = [];
	
	
	/**
	 *	Add an error to this component, and if a parent somewhere exists,
	 *	to the parent form as well.
	 */
	public function triggerError(ElementInterface $context, $message = null)
	{
		$error = new ErrorMessage();
		
		$error->name = $this->name ?: $context->name;
		$error->context = $context;
		$error->message = $message;
		
		$context->addError($error);
		
		# roots are always going to be element lists
		if($context->root instanceof ElementListInterface)
			$context->root->addError($error);
		
		return $this;
	}
	
	
	/**
	 *	Add an already created error message
	 */
	public function addError(ErrorMessage $error)
	{
		$this->errors[] = $error;
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
}