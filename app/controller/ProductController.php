<?php

	namespace app\controller;

	use app\core\Controller;
	use app\lib\Db;

	class ProductController extends Controller
	{
		public function listAction ()
		{
			$data['test'] = 'test';

			$product_model = $this->model->load('product');
			$user_model = $this->model->load('user');

			$product_model->getProducts();

			echo $this->view->render('product/product_list', $data);
		}

		public function addAction ()
		{

		}
	}