<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 14:20
 */

namespace SnippetManager\View;

class Category extends View {
	public static function getTitle(): string {
		return "Kategorien";
	}

	public function getBody(): string {
		return '
			Kategorien
		';
	}
}