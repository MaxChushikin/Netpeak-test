<?php

	namespace app\controller;

	use app\core\Controller;

	class ProductController extends Controller
	{
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
						'edit' 			=> '/product/edit/?product_id=' . $product['product_id'],
						'delete' 		=> '/product/delete/?product_id=' . $product['product_id'],
					];
				}
			}

//			sort-order links
			$url = '';

			$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}

			$data['sort_product_id'] = '/' . '?sort=p.product_id' . $url;
			$data['sort_name'] = '/' . '?sort=p.name' . $url;
			$data['sort_date_added'] = '/' . '?sort=p.date_added' . $url;
			$data['sort_user'] = '/' . '?sort=u.name' . $url;
			$data['sort_reviews'] = '/' . '?sort=r.total_reviews' . $url;

			$data['sort'] = $sort;
			$data['order'] = $order;

			echo $this->view->render('product/product_list', $data);
		}

		public function addAction ()
		{
			$data = [];
			$data['title'] = 'Нобавить новый товар';

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
			$data['title'] = 'Редактировать товар';

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


		public function getForm ()
		{
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

		}
	}