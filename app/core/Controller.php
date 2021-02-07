<?php


	namespace app\core;

	use app\lib\Db;
	use app\core\Model;

	abstract class Controller
	{

		private $route;
		protected $view;
		protected $model;

		public function __construct ($route)
		{
			$this->route = $route;
			$this->view = new View;
			$this->db = new Db();
			$this->model = new Model();

			echo $this->view->render('index', ['name' => 'Fabien']);
		}
	}