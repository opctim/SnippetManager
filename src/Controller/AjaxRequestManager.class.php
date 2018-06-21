<?php
/**
 * nelles websolutions
 * Date: 13.07.18
 * Time: 18:42
 */

namespace SnippetManager\Controller;

class AjaxRequestManager {
	protected $default;
	protected $ajaxFunctions = array();

	public function add($handle, callable $callback) {
		$this->ajaxFunctions[$handle] = $callback;
	}

	public function setDefault(callable $callback) {
		$this->default = $callback;
	}

	public static function setJsonHeader() {
		header("Content-Type: application/json");
	}

	public function __destruct() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if (AJAX_CALL_LEGAL) {
				foreach ($this->ajaxFunctions as $handle => $callback) {
					if (isset($_REQUEST[$handle])) {
						call_user_func($callback, $_REQUEST[$handle]);

						exit;
					}
				}
			}
		}

		call_user_func($this->default);
	}
}