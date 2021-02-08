<?php

	namespace app\controller;

	use app\core\Controller;

	class ReviewController extends Controller
	{
		private $error = [];

		public function listAction ()
		{
			if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
				$product_id = $_GET['product_id'];
			} else {
				$product_id = false;
			}

			$data = [];
			$data['title'] = 'Product reviews';

			$review_model = $this->model->load('review');
			$product_model = $this->model->load('product');

			$products = $product_model->getProducts();

			if ($products) {
				foreach ($products as $product) {


					$image = (isset($product['image']) && !empty($product['image'])) ? $product['image'] : '/public/image/no-image.png';
					$avg_rating = $review_model->getProductAVGRating($product['product_id']);

					$data['reviews'] = [];
					$reviews = $review_model->getReviewsByProductId($product['product_id']);

					if ($reviews) {
						foreach ($reviews as $review) {
							$date_added = date('d m Y', strtotime($review['date_added']));
							$user = ($review['user_name'] && !empty($review['user_name'])) ? $review['user_name'] : 'Strange Alien';

							$data['reviews'][] = [
								'product_id' 	=> $review['review_id'],
								'text' 			=> strip_tags(html_entity_decode($review['text'], ENT_QUOTES, 'UTF-8')),
								'date_added' 	=> $date_added,
								'rating' 		=> $review['rating'],
								'user' 			=> $user,
							];
						}
					}
					

					$data['products'][] = [
						'product_id' 	=> $product['product_id'],
						'name' 			=> $product['name'],
						'image' 		=> $image,
						'total_reviews' => isset($total_reviews) ? $total_reviews : 0,
						'reviews' 		=> $data['reviews'],
						'avg_rating' 	=> ($avg_rating) ? round($avg_rating, 1) : false,
						'add' 			=> '/review/add/?product_id=' . $product['product_id'],
					];
				}
			}

			echo $this->view->render('review/review_list', $data);
		}

		public function addAction ()
		{
			if (($_SERVER['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

				$review_model = $this->model->load('review');
				$review_model->addReview($_POST);

				// todo: add message to session

				header("HTTP/1.1 301 Moved Permanently");
				header("Location: /review/");
				exit();
			}

			$this->getForm();
		}

		public function getForm ()
		{

			if (!isset($_GET['review_id'])) {
				$data['action'] = '/review/add?product_id=' . $_GET['product_id'];
				$data['title'] = 'Добавить новый отзыв';
			} else {
				$data['action'] = '/review/edit?product_id=' . $_GET['product_id'] . '&review_id=' . $_GET['review_id'];
				$data['title'] = 'Редактировать отзыв';
			}

			if (isset($this->error)) {
				$data['error'] = $this->error;
			}

			if (isset($_GET['product_id'])) {
				$data['product_id'] = $_GET['product_id'];
			}

			if (isset($_GET['review_id']) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
				$review_info = $this->model->load('review');
				$review_info = $review_info->getReview($_GET['review_id']);
			}

			if (isset($_POST['text'])) {
				$data['text'] = $_POST['text'];
			} elseif (!empty($review_info)) {
				$data['text'] = $review_info['text'];
			} else {
				$data['text'] = '';
			}

			if (isset($_POST['rating'])) {
				$data['rating'] = $_POST['rating'];
			} elseif (!empty($review_info)) {
				$data['rating'] = $review_info['rating'];
			} else {
				$data['rating'] = '';
			}

			if (isset($_GET['product_id'])) {
				$data['product_id'] = $_GET['product_id'];
			} elseif (!empty($review_info)) {
				$data['product_id'] = $review_info['product_id'];
			} else {
				$data['product_id'] = '';
			}

			$user_model = $this->model->load('user');
			$data['users'] = $user_model->getUsers();

			if (isset($_POST['user_id'])) {
				$data['user_id'] = $_POST['user_id'];
			} elseif (!empty($review_info)) {
				$data['user_id'] = $review_info['user_id'];
			} else {
				$data['user_id'] = '';
			}

			echo $this->view->render('review/review_form', $data);
		}

		public function validateForm ()
		{
			if (!isset($_POST['text']) || mb_strlen($_POST['text']) < 2 || mb_strlen($_POST['text']) > 256 ){
				$this->error['text'] = 'Текст отзыва обязателен и должен быть от 10 до 500 символов';
			}

			if (!isset($_POST['rating']) || $_POST['rating'] < 0 || mb_strlen($_POST['rating']) > 10 ){
				$this->error['rating'] = 'Оцените товар';
			}


			if (!isset($_POST['product_id'])){
				$this->error['product_id'] = true;
			}

			$user_model = $this->model->load('user');
			$users = $user_model->getUsers();

			if ($_POST['user_id']){
				if (array_search($_POST['user_id'], array_column($users, 'user_id')) === false){
					$this->error['user'] = 'Такого автора у нас нет';
				}
			} else {
				$this->error['user'] = 'Необходимо выбрать автора';
			}

			return !$this->error;
		}
	}