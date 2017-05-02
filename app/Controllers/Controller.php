<?php

namespace App\Controllers;

/**
* The main Controller or super class for all controllers
* 
* @filesource Controller.php
* @author Tlotliso Mafantiri
*/
class Controller
{
	/**
	 * @var Object $container
	 */
	protected $container;
	
	/**
	 * Constructor. Initialize the Controller
	 * 
	 * @param Object $container
	 * @return void
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

	/**
	 * Get the property
	 * 
	 * @param mixed $property
	 * @return mixed
	 */
	public function __get($property)
	{
		if ($this->container->{$property}) {
			return $this->container->{$property};
		}
	}
}