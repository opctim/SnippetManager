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

	/**
	 * @param string $color1
	 * @param string $color2
	 * @return float
	 */
	protected static function luminosityDifference($color1, $color2) {
		list($r1, $g1, $b1) = sscanf($color1, "#%02x%02x%02x");
		list($r2, $g2, $b2) = sscanf($color2, "#%02x%02x%02x");

		$color1 = 	0.2126 * pow($r1 / 255, 2.2) +
			  		0.7152 * pow($g1 / 255, 2.2) +
			  		0.0722 * pow($b1 / 255, 2.2);

		$color2 = 	0.2126 * pow($r2 / 255, 2.2) +
			  		0.7152 * pow($g2 / 255, 2.2) +
					0.0722 * pow($b2 / 255, 2.2);

		if ($color1 > $color2)
			return ($color1 + 0.05) / ($color2 + 0.05);
		else
			return ($color2 + 0.05) / ($color1 + 0.05);
	}
}