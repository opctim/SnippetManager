<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 08:21
 */

namespace SnippetManager\Model\Database;


class Database extends \mysqli {
	private static $instance = null;

	public function __construct() {
		$result = parent::__construct(SM_DB_HOST, SM_DB_USER, SM_DB_PASSWORD, SM_DB_NAME);

		if (!$result && error_reporting() !== E_ALL) {
			echo "<h1>Invalid database connection.</h1>";

			exit;
		}

		$this->set_charset("utf8");
	}

	public static function getInstance() {
		if (is_null(self::$instance))
			self::$instance = new self();

		return self::$instance;
	}

	/**
	 * @param string $query The SQL Query to execute.
	 * @param mixed $resultMode = null DISABLED / NOT WORKING
	 * @return false|Result
	 */
	public function query($query, $resultMode = null) {
		if (!$this->real_query($query))
			return false;

		return new Result($this);
	}

	public function escapeAddQuotesOrSetNull($string) {
		$string = $this->real_escape_string($string);

		if (empty($string))
			return "NULL";

		return "'" . $string . "'";
	}

	/** @return bool | Result */
	public function handleResult($result) {
		if (!$result)
			die($this->error);

		return $result;
	}

	public function __destruct() {
		$this->close();
	}
}