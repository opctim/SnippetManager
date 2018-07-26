<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 14:10
 */

namespace SnippetManager\View;

use SnippetManager\Model\Snippet;


class EditSnippet extends View {
	protected $snippet;

	public function __construct($id) {
		$this->snippet = \SnippetManager\Model\Snippets::get($id);
	}

	public static function getTitle(): string {
		return "Snippet bearbeiten";
	}

	public function getBody(): string {
		return '
			Edit
		';
	}
}