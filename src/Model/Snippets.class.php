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
	 * @param int | null $id
	 * @param string | null $searchTerm
	 * @return Snippet[] | Snippet
	 */
	public static function get($id = null, $searchTerm = null) {
		$output = array();
		$db = Database::getInstance();

		if (!is_null($id)) {
			$id = $db->real_escape_string($id);

			$result = $db->query("SELECT * FROM snippet LEFT JOIN category USING(CATEGORY_ID) WHERE SNIPPET_ID = $id");
		}
		else {
			if (!is_null($searchTerm)) {
				if (preg_match('/(?:(?<REGULAR_SEARCHTERM>.*)\s+)?category:"(?<CATEGORY>[^"]+)"$/ui', $searchTerm, $matches)) {
					$regularSearchTerm = $db->real_escape_string($matches["REGULAR_SEARCHTERM"]);
					$category = $db->real_escape_string($matches["CATEGORY"]);

					$result = $db->query("
						SELECT * 
						FROM snippet 
							LEFT JOIN category USING(CATEGORY_ID) 
						WHERE 
							CATEGORY_NAME = '$category' AND (
								CATEGORY_DESCRIPTION  LIKE '%$regularSearchTerm%' OR
								SNIPPET_TAGS LIKE '%$regularSearchTerm%' OR
								SNIPPET_TEXT LIKE '%$regularSearchTerm%' OR
								SNIPPET_NAME LIKE '%$regularSearchTerm%'
							)
						ORDER BY SNIPPET_CREATED DESC
					");
				}
				else if (preg_match('/(?:(?<REGULAR_SEARCHTERM>.*)\s+)?tag:"(?<TAG>[^"]+)"$/ui', $searchTerm, $matches)) {
					$regularSearchTerm = $db->real_escape_string($matches["REGULAR_SEARCHTERM"]);
					$tag = $db->real_escape_string($matches["TAG"]);

					$result = $db->query("
						SELECT * 
						FROM snippet 
							LEFT JOIN category USING(CATEGORY_ID) 
						WHERE 
							CONCAT(' ', SNIPPET_TAGS, ' ') LIKE '% $tag %' AND (
								CATEGORY_NAME LIKE '%$searchTerm%' OR 
								CATEGORY_DESCRIPTION  LIKE '%$regularSearchTerm%' OR
								SNIPPET_TEXT LIKE '%$regularSearchTerm%' OR
								SNIPPET_NAME LIKE '%$regularSearchTerm%'
							)
						ORDER BY SNIPPET_CREATED DESC
					");
				}
				else {
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
						ORDER BY SNIPPET_CREATED DESC
					");
				}
			}
			else {
				$result = $db->query("SELECT * FROM snippet LEFT JOIN category USING(CATEGORY_ID) ORDER BY SNIPPET_CREATED DESC");
			}
		}

		foreach ($result->getRows() as $row)
			$output[] = new Snippet($row);

		if (count($output) == 1 && !is_null($id))
			return $output[0];


		return $output;
	}
}