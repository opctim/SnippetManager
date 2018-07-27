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
		$this->parent->addJavaScriptFiles([
			"select2js"		=> "lib/Select2/select2.min.js",
		]);

		$this->parent->addCssFiles([
			"select2css"	=> "lib/Select2/select2.min.css",
			"select2-style"	=> "css/select2.style.css",
			"snippets-css"	=> "css/snippets.page.css",
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
									<textarea name="text" placeholder="(new Foo())->bar();">' . $this->snippet->Text . '</textarea>
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
		$tags = preg_split("/\s+/", $this->snippet->Tags);

		if (count($tags) == 0)
			return null;

		foreach ($tags as $tag)
			$output[] = '<option value="' . $tag . '" data-select2-tag="true">' . $tag . '</option>';

		return implode(PHP_EOL, $output);
	}

	protected function getSnippetTagValues() {
		$tags = preg_split("/\s+/", $this->snippet->Tags);

		if (count($tags) == 0)
			return "[]";

		return '["' . implode('","', $tags) . '"]';
	}
}