<?php

	namespace app\model;

	use app\core\Model;

	class User extends Model
	{

		public function getUser ($user_id)
		{
			$query = $this->db->query('SELECT * FROM `user` WHERE `user_id` = "' . $user_id . '" LIMIT 1');

			return	($query->num_rows) ? $query->row : false;
		}

		public function getUsers ()
		{
			$query = $this->db->query('SELECT * FROM `user`');

			return	($query->num_rows) ? $query->rows : false;
		}
	}