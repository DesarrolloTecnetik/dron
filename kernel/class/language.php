<?php 

	class language {

		private $key;

		// define main language
		public function __construct($k = 'es') {

			$this->key = $k;

		}

		// load language for user
		public function get($translate) {

			// file route language
			$file = PATH.'/kernel/class/language/'.$this->key.'.php';

			// check that exist file
			if(is_file($file)) {

				// exist the language file
				require $file;

			} else {

				// dont exist? add main language [ES]
				require PATH.'/kernel/class/language/es.php';

			}

			return $lang[$translate];

		}

	}

?>