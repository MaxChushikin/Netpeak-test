<?php


	namespace app\core;


	class View
	{
		public $twig = 'default';

		public function render ($template, $data)
		{
			require_once 'vendor/autoload.php';

			$loader = new \Twig\Loader\FilesystemLoader('app/view');

			$twig = new \Twig\Environment($loader);

			return $twig->render($template . '.twig', $data);
		}
	}