<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 08:09
 */

namespace SnippetManager\Model\Database;

class Result extends \mysqli_result {
	public function getRows() {
		$output = array();

		while ($row = $this->fetch_object())
			$output[] = $row;

		return $output;
	}

	public function getRow() {
		return $this->fetch_object();
	}
}
