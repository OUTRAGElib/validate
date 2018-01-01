<?php


namespace OUTRAGElib\Validate;


interface ElementGetterInterface
{
	/**
	 *	Returns a list of all accessable parent properties in this scope.
	 *	Do I still need this?
	 */
	public function getter_property_tree();
	
	
	/**
	 *	Get the name of this particular component.
	 *
	 *	Rather than cache it, I'll just generate its resolved name every time.
	 *	Shouldn't cause too many problems, right?
	 */
	public function getter_name();
	
	
	/**
	 *	Find the top-most element of this component. This is very likely, if not
	 *	always, going to be a template.
	 */
	public function getter_root();
}