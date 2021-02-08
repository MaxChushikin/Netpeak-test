<?php

	namespace app\model;

	use app\core\Model;

	class Product extends Model
	{
		public function addProduct ($data)
		{
			$this->db->query("INSERT INTO `product` SET `name` = '" . $this->db->escape($data['name']) . "', `price` = '" . (float)$data['price'] . "', `image` = '" . $this->db->escape($data['image']) . "', `user_id` = '" . (int)$data['user_id'] . "', `date_added` = NOW()");

			return $this->db->getLastId();
		}

		public function editProduct ($product_id, $data)
		{
			$this->db->query("UPDATE `product` SET `name` = '" . $this->db->escape($data['name']) . "', `price` = '" . (float)$data['price'] . "', `image` = '" . $this->db->escape($data['image']) . "', `user_id` = '" . (int)$data['user_id'] . "' WHERE `product_id` = " . (int)$product_id);

			return $product_id;
		}

		public function getProduct ($product_id)
		{
			$query = $this->db->query('SELECT *, p.name, u.name as user_name FROM `product` p LEFT JOIN `user` u on (p.user_id = u.user_id) WHERE `product_id` = ' . (int)$product_id . ' LIMIT 1');

			return	($query->num_rows) ? $query->row : false;
		}

		public function getProducts ($data = [])
		{
			$sql = 'SELECT *, p.name, u.name as user_name, (SELECT COUNT(*) FROM review r WHERE r.product_id = p.product_id) as `review_total` FROM `product` p LEFT JOIN `user` u on (p.user_id = u.user_id)';

			$sort_data = array(
				'p.name',
				'p.product_id',
				'p.date_added',
				'u.name',
				'user_name',
				'review_total',
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.product_id";
			}

			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}

			$query = $this->db->query($sql);

			return	($query->num_rows) ? $query->rows : false;
		}

	}