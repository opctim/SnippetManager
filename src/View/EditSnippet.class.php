<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 14:10
 */

namespace SnippetManager\View;


class EditSnippet extends View {
	protected $snippet;

	public function __construct($id) {
		$this->snippet = \SnippetManager\Model\Snippets::get($id);
	}

	public function init() {
		$files = [
			"select2js"					=> "lib/Select2/select2.min.js",
			"codemirror"				=> "lib/CodeMirror/lib/codemirror.js",
			"codemirror-matchbrackets"	=> "lib/CodeMirror/addon/edit/matchbrackets.js",
			"codemirror-xml"			=> "lib/CodeMirror/mode/xml/xml.js",
			"codemirror-javascript"		=> "lib/CodeMirror/mode/javascript/javascript.js",
			"codemirror-css"			=> "lib/CodeMirror/mode/css/css.js",
			"codemirror-clike"			=> "lib/CodeMirror/mode/clike/clike.js",
			"codemirror-htmlmixed"		=> "lib/CodeMirror/mode/htmlmixed/htmlmixed.js",
		];

		$languageName = strtolower($this->snippet->getCategory()->Name);

		if (file_exists(SM_WEBFOLDER_PATH . "/lib/CodeMirror/mode/$languageName/$languageName.js"))
			$files["codemirror_language"] = "lib/CodeMirror/mode/$languageName/$languageName.js";

		$this->parent->addJavaScriptFiles($files);

		$this->parent->addCssFiles([
			"select2css"		=> "lib/Select2/select2.min.css",
			"codemirror-css"	=> "lib/CodeMirror/lib/codemirror.css",
			"select2-style"		=> "css/select2.style.css",
			"snippets-css"		=> "css/snippets.page.css",
		]);

		$this->parent->addHeadHtml('
			<script type="text/javascript">
				$(document).ready(function(){
				   	$("select.select2:not(.tags)").each(function(){
				   	    if (typeof $(this).data("placeholder") !== "undefined")
				   	    	$(this).select2({
				   	    		placeholder: $(this).data("placeholder")
				   	    	});
				   	    else
				   	        $(this).select2();
				   	});
				   
				   	var tags = $("#tags");
				   	
				   	tags.select2({
				   		tags: true,
				   		multiple: true,
				   		tokenSeparators: [",", " "]
				   	});
				   	
				   	tags.val(' . $this->getSnippetTagValues() . ').trigger("change");
				   	
				   	code = CodeMirror.fromTextArea(document.getElementById("code-textarea"), {
						lineNumbers: true,
						mode: "text/x-php"
					});
				});
			</script>
		');
	}

	public static function getTitle(): string {
		return "Snippet bearbeiten";
	}

	public function getBody(): string {

		return '
			<h1>' . $this->getTitle() . '</h1>
			<br>
			<br>
			<div id="edit-snippet">
				<form action="' . SM_FULL_URL . '" method="post">
					<input type="hidden" name="editFormSubmit" value="1">
					<input type="hidden" name="sid" value="' . $this->snippet->ID . '">
					<div class="body">
						<div class="row">
							<div class="col-md-4">
								<div class="group">
									<h5>Kategorie</h5>
									<select style="width: 100% !important;" class="select2" data-placeholder="Kategorie wählen" name="category">
										' . $this->getCategoryOptions() . '
									</select>
								</div>
								
								<div class="group">
									<h5>Name</h5>
									<input type="text" name="name" placeholder="Mein neuer Schnipsel" value="' . $this->snippet->Name . '" required>
								</div>
							</div>
							<div class="col-md-8">
								<div class="group">
									<h5>Code</h5>
									<textarea name="text" id="code-textarea" placeholder="(new Foo())->bar();">' . $this->snippet->Text . '</textarea>
								</div>
							</div>
						</div>
						
						<div class="group">
							<h5>Tags</h5>
							<select id="tags" style="width: 100% !important;" class="select2 tags" name="tags[]" multiple>
								' . $this->getSnippetTagSelectOptions() . '
							</select>
						</div>
						
						<button type="submit" class="btn btn-primary">Speichern</button>
					</div>
				</form>
			</div>
		';
	}

	protected function getCategoryOptions() {
		$output = array();
		$categories = \SnippetManager\Model\Categories::get();

		foreach ($categories as $category)
			$output[] = '<option value="' . $category->ID . '"' . ($this->snippet->getCategory()->ID == $category->ID ? ' selected' : null) . '>' . $category->Name . '</option>';

		return implode(PHP_EOL, $output);
	}

	protected function getSnippetTagSelectOptions() {
		$output = array();
		$tags = $this->getTags();

		if (count($tags) == 0)
			return null;

		foreach ($tags as $tag)
			$output[] = '<option value="' . $tag . '" data-select2-tag="true">' . $tag . '</option>';

		return implode(PHP_EOL, $output);
	}

	protected function getSnippetTagValues() {
		$tags = $this->getTags();

		if (count($tags) == 0)
			return "[]";

		return '["' . implode('","', $tags) . '"]';
	}

	protected function getTags() {
		$tags = preg_split("/\s+/", $this->snippet->Tags);

		if (count($tags) == 1 && $tags[0] === "")
			$tags = array();

		return $tags;
	}
}