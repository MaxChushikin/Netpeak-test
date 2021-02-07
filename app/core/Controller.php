<?php


	namespace app\core;


	abstract class Controller
	{

		private $route;

		public function __construct ($route)
		{
			$this->route = $route;
		}

	}