<?php


	namespace app\core;

	abstract class Controller
	{

		private $route;
		protected $view;

		public function __construct ($route)
		{
			$this->route = $route;
			$this->view = new View;

			echo $this->view->render('index', ['name' => 'Fabien']);
		}
	}