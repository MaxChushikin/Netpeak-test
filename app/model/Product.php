<?php

	namespace app\model;

	use app\core\Model;

	class Product extends Model
	{
		public function getProducts ()
		{
			$query = $this->db->query('SELECT *, p.name, u.name as user_name FROM `product` p LEFT JOIN `user` u on (p.user_id = u.user_id)');

			return	($query->num_rows) ? $query->rows : false;
		}
	}