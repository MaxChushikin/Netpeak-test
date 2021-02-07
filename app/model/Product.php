<?php

	namespace app\model;

	use app\core\Model;

	class Product extends Model
	{

		public function getProducts ()
		{
			$this->db->query('SELECT * FROM `products` p LEFT JOIN `users` u on (p.user_id = u.user_id)');

		}
	}