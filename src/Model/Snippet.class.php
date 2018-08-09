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
	public $CategoryId = null;
	public $Name = null;
	public $Text = null;
	public $Tags = null;
	public $Created = null;

	public function __construct($databaseData) {
		$this->databaseData = $databaseData;
		$this->internalId = $databaseData->SNIPPET_ID;

		$this->ID = $databaseData->SNIPPET_ID;
		$this->CategoryId = $databaseData->CATEGORY_ID;
		$this->Name = $databaseData->SNIPPET_NAME;
		$this->Text = $databaseData->SNIPPET_TEXT;
		$this->Tags = $databaseData->SNIPPET_TAGS;
		$this->Created = $databaseData->SNIPPET_CREATED;
	}

	public function getCategory() {
		return new Category($this->databaseData);
	}

	public function writeToDatabase() {
		$db = Database::getInstance();

		if (is_array($this->Tags))
			$this->Tags = implode(" ", $this->Tags);

		$categoryId = $db->real_escape_string($this->CategoryId);
		$snippetName = $db->escapeAddQuotesOrSetNull($this->Name);
		$snippetText = $db->escapeAddQuotesOrSetNull($this->Text);
		$snippetTags = $db->escapeAddQuotesOrSetNull($this->Tags);

		$db->query("UPDATE snippet SET CATEGORY_ID = '$categoryId', SNIPPET_NAME = $snippetName, SNIPPET_TEXT = $snippetText, SNIPPET_TAGS = $snippetTags WHERE SNIPPET_ID = " . $this->internalId);

		return $db->affected_rows > 0;
	}

	public static function create($categoryId, $name, $text, $tags) {
		$db = Database::getInstance();

		$categoryId = $db->real_escape_string($categoryId);
		$name = $db->escapeAddQuotesOrSetNull($name);
		$text = $db->escapeAddQuotesOrSetNull($text);
		$tags = $db->escapeAddQuotesOrSetNull($tags);

		$db->query("INSERT INTO snippet(CATEGORY_ID, SNIPPET_NAME, SNIPPET_TEXT, SNIPPET_TAGS) VALUES($categoryId, $name, $text, $tags)");

		if ($db->insert_id == 0)
			return false;

		$stdClass = new \stdClass();

		$stdClass->ID = $db->insert_id;
		$stdClass->Name = $name;
		$stdClass->Text = $text;
		$stdClass->Tags = $tags;
		$stdClass->Created = date("Y-m-d H:i:s");

		$stdClass = (object)array_merge((array)$stdClass, (array)Categories::get($categoryId));

		return new Snippet($stdClass);
	}

	public static function delete($id) {
		$db = Database::getInstance();

		$id = $db->real_escape_string($id);

		$db->query("DELETE FROM snippet WHERE SNIPPET_ID = $id");

		return $db->affected_rows > 0;
	}
}