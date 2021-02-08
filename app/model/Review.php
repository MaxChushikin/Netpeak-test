<?php

	namespace app\model;

	use app\core\Model;

	class Review extends Model
	{
		public function addReview ($data)
		{
			$this->db->query("INSERT INTO `review` SET `product_id` = '" . (int)$data['product_id'] . "', `text` = '" . $this->db->escape($data['text']) . "', `rating` = '" . (int)$data['rating'] . "', `user_id` = '" . (int)$data['user_id'] . "', `date_added` = NOW()");

			return $this->db->getLastId();
		}

		public function editReview ($review_id, $data)
		{
			$this->db->query("UPDATE `review `SET `product_id` = '" . (int)$data['product_id'] . "', `text` = '" . $this->db->escape($data['text']) . "', `rating` = '" . (int)$data['rating'] . "', `user_id` = '" . (int)$data['user_id'] . "' WHERE `product_id` = " . (int)$review_id);

			return $review_id;
		}

		public function getReview ($review_id)
		{
			$query = $this->db->query('SELECT *, u.name as user_name FROM `review` r LEFT JOIN `user` u on (r.user_id = u.user_id) WHERE `review_id` = ' . (int)$review_id . ' LIMIT 1');

			return	($query->num_rows) ? $query->row : false;
		}

		public function getProductAVGRating ($product_id)
		{
			$query = $this->db->query('SELECT AVG(rating) as avg_rating FROM `review` WHERE `product_id` = ' . (int)$product_id);

			return	($query->num_rows) ? $query->row['avg_rating'] : false;
		}

		public function getReviewsByProductId ($product_id)
		{
			$sql = 'SELECT *, u.name as user_name FROM `review` r LEFT JOIN `user` u on (r.user_id = u.user_id)';

			if ($product_id){
				$sql .= ' WHERE `product_id` = ' . (int)$product_id ;
			}

			$query = $this->db->query($sql);

			return	($query->num_rows) ? $query->rows : false;
		}

		public function getTotalReviewsByProductId ($product_id)
		{
			$query = $this->db->query('SELECT count(review_id) as total FROM `review` WHERE product_id = ' . $product_id );

			return	($query->num_rows) ? $query->row['total'] : 0;
		}
	}