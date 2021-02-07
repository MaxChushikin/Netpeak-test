<?php

	namespace app\core;

	class Router
	{

		protected $routes = [];
		protected $params = [];

		public function __construct ()
		{
			$routes = require 'app/config/routes.php';

			foreach ($routes as $route => $params) {
				$this->add($route, $params);				
			}
		}

		public function add ($route, $params)
		{
			$route = '#^' . $route . '$#';
			$this->routes[$route] = $params;
		}

		public function match ()
		{
			$url = trim($_SERVER['REQUEST_URI'], '/');

			foreach ($this->routes as $route => $params) {
				if (preg_match($route, $url, $matches)){
					$this->params = $params;

					return true;
				}
			}

			return false;
		}

		public function run ()
		{
			if ($this->match()) {
				$controller = 'app\controller\\' . ucfirst($this->params['controller']) . 'Controller.php';

				if (class_exists($controller)){
					echo 'Class isset';
				} else {
					exit('Class not found');
				}


				echo $controller;
			} else {
				echo '404';
			}
		}
	}