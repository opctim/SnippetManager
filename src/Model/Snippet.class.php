<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 13:50
 */

namespace SnippetManager\Model;

use SnippetManager\Model\Database\Database;

class Snippet {
	protected $databaseData;
	protected $internalId;

	public $ID = null;
	public $Name = null;
	public $Text = null;
	public $Tags = null;

	public function __construct($databaseData) {
		$this->databaseData = $databaseData;
		$this->internalId = $databaseData->SNIPPET_ID;

		$this->ID = $databaseData->SNIPPET_ID;
		$this->Name = $databaseData->SNIPPET_NAME;
		$this->Text = $databaseData->SNIPPET_TEXT;
		$this->Tags = $databaseData->SNIPPET_TAGS;
	}

	public function getCategory() {
		return new Category($this->databaseData);
	}

	public function writeToDatabase() {
		$db = Database::getInstance();

		$snippetName = $db->real_escape_string($this->Name);
		$snippetText = $db->real_escape_string($this->Text);
		$snippetTags = $db->real_escape_string($this->Tags);

		$db->query("UPDATE snippet SET SNIPPET_NAME = '$snippetName', SNIPPET_TEXT = '$snippetText', SNIPPET_TAGS = '$snippetTags' WHERE SNIPPET_ID = " . $this->internalId);

		return $db->affected_rows > 0;
	}
}