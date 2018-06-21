<?php

class Autoload {
	protected $rootNamespace;

	public function __construct($rootNamespace) {
		$this->rootNamespace = $rootNamespace;

		spl_autoload_register(function($nameSpace){
			$nameSpace = preg_replace("/^" . preg_quote($this->rootNamespace, "/") . "/ui", SM_SOURCEFOLDER_PATH, $nameSpace);

			$nameSpace = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $nameSpace);

			$fileTypes = array(
				"$nameSpace.class.php",
				"$nameSpace.interface.php",
				"$nameSpace.trait.php",
			);

			foreach ($fileTypes as $fileType)
				if (file_exists($fileType))
					require_once $fileType;
		});
	}
}