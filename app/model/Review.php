<?php

	namespace app\model;

	use app\core\Model;

	class Review extends Model
	{
		public function getTotalReviewsByProductId ($product_id)
		{
			$query = $this->db->query('SELECT count(review_id) as total FROM `review` WHERE product_id = ' . $product_id );

			return	($query->num_rows) ? $query->row['total'] : 0;
		}
	}