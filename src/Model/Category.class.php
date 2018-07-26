<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 13:54
 */

namespace SnippetManager\Model;

use SnippetManager\Model\Database\Database;

class Category {
	protected $internalId;

	public $ID = null;
	public $Name = null;
	public $Description = null;
	public $Color = null;

	public function __construct($databaseData) {
		$this->internalId = $databaseData->CATEGORY_ID;

		$this->ID = $databaseData->CATEGORY_ID;
		$this->Name = $databaseData->CATEGORY_NAME;
		$this->Description = $databaseData->CATEGORY_DESCRIPTION;
		$this->Color = $databaseData->CATEGORY_COLOR;
	}

	public function getColor() {
		if (empty($this->Color))
			return SM_DEFAULT_CATEGORY_COLOR;

		return $this->Color;
	}

	public function writeToDatabase() {
		$db = Database::getInstance();

		$categoryName = $db->real_escape_string($this->Name);
		$categoryDescription = $db->real_escape_string($this->Description);
		$categoryColor = $db->real_escape_string($this->Color);

		$db->query("UPDATE category SET CATEGORY_NAME = '$categoryName', CATEGORY_DESCRIPTION = '$categoryDescription', CATEGORY_COLOR = '$categoryColor' WHERE CATEGORY_ID = " . $this->internalId);

		return $db->affected_rows > 0;
	}

	public static function create($name, $description, $color) {
		$db = Database::getInstance();

		$name = $db->real_escape_string($name);
		$description = $db->real_escape_string($description);
		$color = $db->real_escape_string($color);

		$db->query("INSERT INTO category(CATEGORY_NAME, CATEGORY_DESCRIPTION, CATEGORY_COLOR) VALUES('$name', '$description', '$color')");

		if ($db->insert_id == 0)
			return false;

		$databaseData = new \stdClass();

		$databaseData->CATEGORY_ID = $db->insert_id;
		$databaseData->CATEGORY_NAME = $name;
		$databaseData->CATEGORY_DESCRIPTION = $description;
		$databaseData->CATEGORY_COLOR = $color;

		return new Category($databaseData);
	}

	public static function delete($id) {
		$db = Database::getInstance();

		$id = $db->real_escape_string($id);

		$db->query("DELETE FROM category WHERE CATEGORY_ID = $id");

		return $db->affected_rows > 0;
	}
}