<?php

	namespace app\controller;

	use app\core\Controller;

	class ProductController extends Controller
	{
		private $error = [];

		public function listAction ()
		{

			$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'p.product_id';
			$order = (isset($_GET['order'])) ? $_GET['order'] : 'DESC';

			$data = [];
			$data['title'] = 'Product list';

			$product_model = $this->model->load('product');
			$review_model = $this->model->load('review');

			$filter_data = array(
				'sort'            => $sort,
				'order'           => $order,
			);

			$products = $product_model->getProducts($filter_data);

			$data['products'] = [];

			if ($products) {
				foreach ($products as $product) {
					$image = (isset($product['image']) && !empty($product['image'])) ? $product['image'] : '/public/image/no-image.png';
					$date_added = date('d m Y', strtotime($product['date_added']));
					$user = ($product['user_name'] && !empty($product['user_name'])) ? $product['user_name'] : 'Strange Alien';
					$total_reviews = $review_model->getTotalReviewsByProductId($product['product_id']);

					$data['products'][] = [
						'product_id' 	=> $product['product_id'],
						'name' 			=> $product['name'],
						'image' 		=> $image,
						'date_added' 	=> $date_added,
						'user' 			=> $user,
						'total_reviews' => $total_reviews,
						'review' 		=> '/review/?product_id=' . $product['product_id'],
						'edit' 			=> '/product/edit/?product_id=' . $product['product_id'],
						'delete' 		=> '/product/delete/?product_id=' . $product['product_id'],
					];
				}
			}

//			sort-order links
			$url = '';

			$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

			$data['sort_product_id']		= '/' . '?sort=p.product_id' . $url;
			$data['sort_name'] 				= '/' . '?sort=p.name' . $url;
			$data['sort_date_added'] 		= '/' . '?sort=p.date_added' . $url;
			$data['sort_user'] 				= '/' . '?sort=user_name' . $url;
			$data['sort_reviews'] 			= '/' . '?sort=review_total' . $url;

			$data['sort'] = $sort;
			$data['order'] = $order;

			echo $this->view->render('product/product_list', $data);
		}

		public function addAction ()
		{
			if (($_SERVER['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

				$product_model = $this->model->load('product');
				$product_model->addProduct($_POST);

				// todo: add message to session

				header("HTTP/1.1 301 Moved Permanently");
				header("Location: /");
				exit();
			}

			$this->getForm();
		}

		public function editAction ()
		{
			if (($_SERVER['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

				$product_model = $this->model->load('product');
				$product_model->editProduct($_GET['product_id'], $_POST);

				// todo: add message to session

				header("HTTP/1.1 301 Moved Permanently");
				header("Location: /");
				exit();
			}

			$this->getForm();
		}


		public function getForm ()
		{
			if (!isset($_GET['product_id'])) {
				$data['action'] = '/product/add/';
				$data['title'] = 'Добавить новый товар';
			} else {
				$data['action'] = '/product/edit?product_id=' . $_GET['product_id'];
				$data['title'] = 'Редактировать товар';
			}

			if (isset($this->error)) {
				$data['error'] = $this->error;
			}

			if (isset($_GET['product_id']) && ($_SERVER['REQUEST_METHOD'] != 'POST')) {
				$product_model = $this->model->load('product');
				$product_info = $product_model->getProduct($_GET['product_id']);
			}

			if (isset($_POST['name'])) {
				$data['name'] = $_POST['name'];
			} elseif (!empty($product_info)) {
				$data['name'] = $product_info['name'];
			} else {
				$data['name'] = '';
			}

			if (isset($_POST['image'])) {
				$data['image'] = $_POST['image'];
			} elseif (!empty($product_info)) {
				$data['image'] = $product_info['image'];
			} else {
				$data['image'] = '';
			}

			if (isset($_POST['price'])) {
				$data['price'] = $_POST['price'];
			} elseif (!empty($product_info)) {
				$data['price'] = $product_info['price'];
			} else {
				$data['price'] = '';
			}

			$user_model = $this->model->load('user');
			$data['users'] = $user_model->getUsers();

			if (isset($_POST['user_id'])) {
				$data['user_id'] = $_POST['user_id'];
			} elseif (!empty($product_info)) {
				$data['user_id'] = $product_info['user_id'];
			} else {
				$data['user_id'] = '';
			}

			echo $this->view->render('product/product_form', $data);
		}

		public function validateForm ()
		{
			if (!isset($_POST['name']) || mb_strlen($_POST['name']) < 2 || mb_strlen($_POST['name']) > 256 ){
				$this->error['name'] = 'Название товара обязательно и должно быть от 2 до 256 символов';
			}

			if (isset($_POST['image']) && !filter_var($_POST['image'], FILTER_VALIDATE_URL)){
				$this->error['image'] = 'Ссылка указана некорректно';
			}

			if (isset($_POST['price']) && !filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT)){
				$this->error['price'] = 'Цена товара обязательна';
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