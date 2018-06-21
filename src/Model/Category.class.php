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

	public function __construct($databaseData) {
		$this->internalId = $databaseData->CATEGORY_ID;

		$this->ID = $databaseData->CATEGORY_ID;
		$this->Name = $databaseData->CATEGORY_NAME;
		$this->Description = $databaseData->CATEGORY_DESCRIPTION;
	}

	public function writeToDatabase() {
		$db = Database::getInstance();

		$categoryName = $db->real_escape_string($this->Name);
		$categoryDescription = $db->real_escape_string($this->Description);

		$db->query("UPDATE category SET CATEGORY_NAME = '$categoryName', CATEGORY_DESCRIPTION = '$categoryDescription' WHERE CATEGORY_ID = " . $this->internalId);

		return $db->affected_rows > 0;
	}
}