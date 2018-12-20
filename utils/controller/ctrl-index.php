<?php 

	class IndexController {

		public static function getInstance() {
			if (!self::$instance)
			self::$instance = new IndexController();

			return self::$instance;
		}

	 }
?>