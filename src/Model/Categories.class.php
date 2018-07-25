<?php
/**
 * muahsystems GmbH
 */

namespace SnippetManager\Model;

use SnippetManager\Model\Database\Database;


class Categories {
	/**
	 * @param int | null $id
	 * @return Category[]
	 */
	public static function get($id = null) {
		$output = array();
		$db = Database::getInstance();

		if (is_null($id)) {
			$result = $db->query("SELECT * FROM category");
		}
		else {
			$id = $db->real_escape_string($id);

			$result = $db->query("SELECT * FROM category WHERE CATEGORY_ID = $id");
		}

		foreach ($result->getRows() as $row)
			$output[] = new Category($row);

		return $output;
	}
}