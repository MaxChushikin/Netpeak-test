<?php


	namespace app\core;

	use app\lib\Db;

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

			$this->model = $this->loadModel($route['controller']);

			echo $this->view->render('index', ['name' => 'Fabien']);
		}

		public function loadModel ($model)
		{
			$model_class = ucfirst($model);

			$path = 'app\model\\' . $model_class;

			if (class_exists($path)) {
				return new $path;
			} else {
				exit('Model ' . $model_class . ' not found');
			}
		}
	}