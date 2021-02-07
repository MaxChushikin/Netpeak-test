<?php


	namespace app\core;

	use app\lib\Db;

	class Model
	{

		public $db;

		public function __construct ()
		{
			$this->db = new Db;
		}

		public function load ($model)
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