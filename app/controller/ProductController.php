<?php

	namespace app\controller;

	use app\core\Controller;
	use app\lib\Db;

	class ProductController extends Controller
	{
		public function listAction ()
		{
			$data['test'] = 'test';

			$this->model->getProducts();

			echo $this->view->render('product/product_list', $data);
		}

		public function addAction ()
		{

		}
	}