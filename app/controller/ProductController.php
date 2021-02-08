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

		}
	}