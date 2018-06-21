<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 13:47
 */

namespace SnippetManager\Model;

use SnippetManager\Model\Database\Database;

class Snippets {
	/**
	 * @param string $searchTerm
	 * @return Snippet[]
	 */
	public static function get($searchTerm = null) {
		$output = array();
		$db = Database::getInstance();

		if (!is_null($searchTerm)) {
			$searchTerm = $db->real_escape_string($searchTerm);

			$result = $db->query("
				SELECT * 
				FROM snippet 
					LEFT JOIN category USING(CATEGORY_ID) 
				WHERE 
					CATEGORY_NAME LIKE '%$searchTerm%' OR 
					CATEGORY_DESCRIPTION  LIKE '%$searchTerm%' OR
					SNIPPET_TAGS LIKE '%$searchTerm%' OR
					SNIPPET_TEXT LIKE '%$searchTerm%' OR
					SNIPPET_NAME LIKE '%$searchTerm%'
			");
		}
		else {
			$result = $db->query("SELECT * FROM snippet LEFT JOIN category USING(CATEGORY_ID)");
		}

		foreach ($result->getRows() as $row)
			$output[] = new Snippet($row);

		return $output;
	}
}