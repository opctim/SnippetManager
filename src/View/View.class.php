<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 09:35
 */

namespace SnippetManager\View;

abstract class View {
	/** @var Document $parent */
	protected $parent;

	public function setParent($parent) {
		$this->parent = $parent;
	}

	abstract public static function getTitle(): string;

	abstract public function getBody(): string;
}