<?php

	namespace app\controller;

	use app\core\Controller;

	class ProductController extends Controller
	{
		public function listAction ()
		{

			$data['test'] = 'test';

			echo $this->view->render('product/product_list', $data);
		}

		public function addAction ()
		{

		}
	}