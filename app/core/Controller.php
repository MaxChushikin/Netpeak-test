<?php


	namespace app\core;

	use app\lib\Db;

	abstract class Controller
	{

		private $route;
		protected $view;

		public function __construct ($route)
		{
			$this->route = $route;
			$this->view = new View;
			$this->db = new Db();

			echo $this->view->render('index', ['name' => 'Fabien']);
		}
	}