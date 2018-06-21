<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 14:10
 */

namespace SnippetManager\View;

class EditSnippet extends View {
	public static function getTitle(): string {
		return "Snippet bearbeiten";
	}

	public function getBody(): string {
		return '
			Edit
		';
	}
}