<?php


namespace OUTRAGElib\Validate;


trait ErrorTrait
{
	/**
	 *	We'll store all errors here as well.
	 */
	public $errors = [];
	
	
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
		
		$context->errors[] = $error;
		
		if($context->root instanceof ElementList)
			$context->root->errors[] = $error;
		
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
}