<?php

	namespace app\model;

	use app\core\Model;

	class Product extends Model
	{

		public function getProducts ()
		{
			echo '<pre style="background: #272727; padding: 10px 15px; color: #088000; text-align: left; font-size: 13px;">';
			    var_dump ($this->db);
			echo '</pre>';
			die ();
		}
	}