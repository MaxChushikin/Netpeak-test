<?php

	namespace app\controller;

	use app\core\Controller;
	use app\lib\Db;

	class ProductController extends Controller
	{
		public function listAction ()
		{
			$data = [];
			$data['title'] = 'Product list';

			$product_model = $this->model->load('product');
			$user_model = $this->model->load('user');
			$review_model = $this->model->load('user');

			$products = $product_model->getProducts();

			$data['products'] = [];

			if ($products) {
				foreach ($products as $product) {

					$image = (isset($product['image']) && file_exists('/public/image/' . $product['image'])) ? '/public/image/' . $product['image'] : '/public/image/no-image.jpeg';
					$date_added = date('Y m d', $product['data_added']);
					$user_info = $user_model->getUser($product['user_id']);
					$user = (isset($user_info['name']) && !empty($user_info['name'])) ? $user_info['name'] : 'Strange Alien';
					$total_reviews = $review_model->getTotalReviewsByProductId($product['product_id']);

					$data['products'][] = [
						'product_id' 	=> $product['product_id'],
						'name' 			=> $product['name'],
						'image' 		=> $image,
						'date_added' 	=> $date_added,
						'user' 			=> $user,
						'total_reviews' => $total_reviews,
					];
				}
			}

			$product_model->getProducts();

			echo $this->view->render('product/product_list', $data);
		}

		public function addAction ()
		{

		}
	}