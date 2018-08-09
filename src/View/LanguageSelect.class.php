<?php
/**
 * muahsystems GmbH
 */

namespace SnippetManager\View;

class LanguageSelect {
	public static function render($language = null) {
		return '
			<select name="language" class="select2" required>
				<option value="text/x-php"' . ($language == "text/x-php" ? "selected" : null) . '>PHP</option>
				<option value="htmlmixed"' . ($language == "htmlmixed" ? "selected" : null) . '>HTML</option>
				<option value="text/css"' . ($language == "text/css" ? "selected" : null) . '>CSS</option>
				<option value="text/javascript"' . ($language == "text/javascript" ? "selected" : null) . '>JavaScript</option>
				<option value="text/plain"' . ($language == "text/plain" ? "selected" : null) . '>Sonstige</option>
			</select>
		';
	}
}