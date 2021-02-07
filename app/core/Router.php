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

				$class_name = ucfirst($this->params['controller']) . 'Controller';
				$path = 'app\controller\\' . $class_name;

				if (class_exists($path)){
					$action = $this->params['action'] . 'Action';
					if (method_exists($path, $action)){
						$controller = new $path($this->params);
						$controller->$action();
					} else {
						exit("Method $action not found");
					}
				} else {
					exit("Class $class_name not found");
				}

			} else {
				exit('Page not found');
			}
		}
	}