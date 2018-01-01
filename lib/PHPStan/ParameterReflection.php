<?php


namespace OUTRAGElib\Validate\PHPStan;

use \PHPStan\Reflection\ParameterReflection as PHPStanParameterReflection;
use \PHPStan\Type\Type as PHPStanType;
use \PHPStan\Type\MixedType as PHPStanMixedType;

class ParameterReflection implements PHPStanParameterReflection
{
	/**
	 *	Store the parameter name
	 */
	protected $name = null;
	
	
	/**
	 *	Is the parameter optional?
	 */
	protected $is_optional = false;
	
	
	/**
	 *	What is the type?
	 */
	protected $type = null;
	
	
	/**
	 *	Is this passed via reference?
	 */
	protected $passed_by_ref = false;
	
	
	/**
	 *	Initialise our reflection
	 */
	public function __construct($name, $is_optional = false, $type = null, $passed_by_ref = false)
	{
		$this->name = $name;
		$this->is_optional = $is_optional;
		$this->type = $type;
		$this->passed_by_ref = $passed_by_ref;
	}
	
	
	/**
	 *	Retrieve the parameter name
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	
	/**
	 *	Retrieve the optional parameter status
	 */
	public function isOptional(): bool
	{
		return $this->is_optional;
	}
	
	
	/**
	 *	Retrieve the type
	 */
	public function getType(): PHPStanType
	{
		return new PHPStanMixedType();
	}
	
	
	/**
	 *	Retrieve the passed by ref status
	 */
	public function isPassedByReference(): bool
	{
		return $this->passed_by_ref;
	}
	
	
	/**
	 *	Is this parameter varadic?
	 */
	public function isVariadic(): bool
	{
		return false;
	}
}