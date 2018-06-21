<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 13:21
 */

namespace SnippetManager\Model;

class Redirect {
	public static function target($string) {
		header("Location: $string");

		exit;
	}
}