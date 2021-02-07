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
			$review_model = $this->model->load('review');

			$products = $product_model->getProducts();

			$data['products'] = [];

			if ($products) {
				foreach ($products as $product) {

					$image = (isset($product['image']) && file_exists('/public/image/' . $product['image'])) ? '/public/image/' . $product['image'] : '/public/image/no-image.jpeg';
					$date_added = date('Y m d', strtotime($product['date_added']));
					$user = ($product['user_name'] && !empty($product['user_name'])) ? $product['user_name'] : 'Strange Alien';
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