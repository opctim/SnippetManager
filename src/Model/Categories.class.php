<?php
/**
 * muahsystems GmbH
 */

namespace SnippetManager\Model;

use SnippetManager\Model\Database\Database;


class Categories {
	/**
	 * @param int | null $id
	 * @param string | null $searchTerm
	 * @return Category[] | Category
	 */
	public static function get($id = null, $searchTerm = null) {
		$output = array();
		$db = Database::getInstance();

		if (!is_null($searchTerm)) {
			$result = $db->query("
				SELECT * 
				FROM category  
				WHERE 
					CATEGORY_NAME LIKE '%$searchTerm%' OR 
					CATEGORY_DESCRIPTION  LIKE '%$searchTerm%'
				ORDER BY CATEGORY_NAME ASC
			");
		}
		else {
			if (is_null($id)) {
				$result = $db->query("SELECT * FROM category ORDER BY CATEGORY_NAME ASC");
			}
			else {
				$id = $db->real_escape_string($id);

				$result = $db->query("SELECT * FROM category WHERE CATEGORY_ID = $id");
			}
		}

		foreach ($result->getRows() as $row)
			$output[] = new Category($row);

		if (count($output) == 1 && !is_null($id))
			return $output[0];

		return $output;
	}
}