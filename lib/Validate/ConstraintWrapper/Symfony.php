<?php


namespace OUTRAGElib\Validate\ConstraintWrapper;

use \Locale;
use \OUTRAGElib\Validate\ConstraintWrapperInterface;
use \Symfony\Component\Translation\Translator;
use \Symfony\Component\Validator\Constraint;
use \Symfony\Component\Validator\ConstraintValidatorFactory;
use \Symfony\Component\Validator\Context\ExecutionContextFactory;
use \Symfony\Component\Validator\Validation;


class Symfony implements ConstraintWrapperInterface
{
	/**
	 *	Is this wrapper actually able to be used?
	 */
	public function isAvailable()
	{
		return class_exists(Constraint::class);
	}
	
	
	/**
	 *	Checks to see whether or not this particular type of constraint
	 *	can be accepted by this object
	 */
	public function isTestable($constraint)
	{
		if(is_object($constraint))
			return $constraint instanceof Constraint;
		
		return false;
	}
	
	
	/**
	 *	Filters an array of constraints and returns ones that can be
	 *	validated
	 */
	public function filterConstraints($constraints)
	{
		$list = array();
		
		foreach($constraints as $constraint)
		{
			# we're wanting to clone as we do not want these error messages to make
			# their way to global scope
			if($this->isTestable($constraint))
				$list[] = clone $constraint;
		}
		
		return $list;
	}
	
	
	/**
	 *	Validates the specified constraints against an input
	 */
	public function validate($constraint, $input, &$errors = [])
	{
		# since Symfony supports locales it's probably in the interest to see if we can at
		# least parse what the client has requested...
		$locale = null;
		
		if(!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			$locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
		
		if(empty($locale))
			$locale = "en_GB";
		
		$constraint_factory = new ConstraintValidatorFactory();
		$context_factory = new ExecutionContextFactory(new Translator($locale));
		
		$validator = $constraint_factory->getInstance($constraint);
		
		$context = $context_factory->createContext(Validation::createValidator(), "root");
		$context->setConstraint($constraint);
		
		$validator->initialize($context);
		$validator->validate($input, $constraint);
		
		# all the error messages are stored within the violations list, need to iterate
		# through these to get all the messages we need to
		foreach($context->getViolations() as $violation)
			$errors[] = $violation->getMessage();
		
		# from what I think is the case, if there are any violations then this will
		# be marked as invalid
		return count($context->getViolations()) == 0;
	}
}