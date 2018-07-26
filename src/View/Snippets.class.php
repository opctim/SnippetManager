<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 09:57
 */

namespace SnippetManager\View;

use Highlight\Highlighter;
use SnippetManager\Model\Categories;
use SnippetManager\Model\Snippet;

class Snippets extends View {
	public static function getTitle(): string {
		return "Snippets";
	}

	public function init() {
		$this->parent->addJavaScriptFiles([
			"select2js"		=> "lib/Select2/select2.min.js",
			"snippets-main"	=> "js/snippets.js",
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
				   
				   	$("select.select2.tags").select2({
				   		tags: true,
				   		tokenSeparators: [",", " "]
				   	});
				});
			</script>
		');
	}

	public function getBody(): string {
		$searchTerm = null;

		if (isset($_SESSION["_sm_enqueued_search"])) {
			$searchTerm = $_SESSION["_sm_enqueued_search"];

			$searchTerm = htmlspecialchars($searchTerm);

			unset($_SESSION["_sm_enqueued_search"]);
		}

		$snippets = \SnippetManager\Model\Snippets::get(null, $searchTerm);

		return '
			<h1>' . $this->getTitle() . ' <small>(<span id="snippet-count">' . count($snippets) . '</span>)</small></h1>
			<div class="row">
				<div class="col-md-8">
					<div class="search-wrapper">
						<input type="text" id="search-field" placeholder="Suchen..." value="' . $searchTerm . '" autofocus>
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>
				<div class="col-md-4">
					<div class="add-snippet"><i class="fa fa-plus-circle"></i> Neu</div>
				</div>
			</div>
			<div id="snippet-list">
				' . $this->renderSnippets($snippets) . '
			</div>
			' . $this->getAddSnippetPopup() . '
			<div id="context-menu">
				<ul>
					<li class="edit"><i class="fa fa-wrench"></i> Bearbeiten</a></li>
					<li class="delete"><i class="fa fa-trash"></i> Löschen</li>
				</ul>
			</div>
			<div id="delete-confirm">
				<div class="panel">
					<h1>Bist du sicher?</h1>
					<div class="body">
						<div class="row">
							<div class="col-md-6">
								<button class="yes">Ja</button>
							</div>
							<div class="col-md-6">
								<button class="no">Nein</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		';
	}

	/**
	 * @param Snippet[] $snippets
	 * @return string
	 */
	public static function renderSnippets($snippets) {
		$output = array();

		$output[] = '<div class="row">';

		foreach ($snippets as $snippet) {
			$textColor = "#ffffff";
			$difference = self::luminosityDifference($textColor, $snippet->getCategory()->getColor());

			if ($difference < 3)
				$textColor = "#000000";

			$snippet->Tags = htmlspecialchars($snippet->Tags);

			$output[] = '
				<div class="col-md-4">
					<div class="snippet" data-sid="' . $snippet->ID . '" style="color: ' . $textColor . ';">
						<div class="row">
							<div class="col-md-12 top-column">
								<a title="Bearbeiten" class="edit-link" href="/edit-snippet?sid=' . $snippet->ID . '">
									<div class="name" style="background-color: ' . $snippet->getCategory()->getColor() . '">' . htmlspecialchars($snippet->Name) . '</div>
								</a>
							</div>
							<div class="col-md-12 middle-column">
								<div class="text">
									' . self::formatText($snippet) . '
									<div class="overlay"></div>
									<div class="category" style="color: ' . $textColor . ';background-color: ' . $snippet->getCategory()->getColor() . '">' . $snippet->getCategory()->Name . '</div>
								</div>
							</div>
							<div class="col-md-12 tag-column">
								<div class="tags" title="Tags">
									' . (trim($snippet->Tags) != "" ? '<span>' : null) . implode("</span><span>", explode(" ", mb_strtolower($snippet->Tags))) . (trim($snippet->Tags) != "" ? '</span>' : null) . '&nbsp;
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}

		$output[] = '</div>';

		return implode(PHP_EOL, $output);
	}

	/**
	 * @param Snippet $snippet
	 * @return string
	 */
	protected static function formatText($snippet) {
		$category = $snippet->getCategory();

		$highlighter = new Highlighter();

		try {
			$result = $highlighter->highlight(mb_strtolower($category->Name), $snippet->Text);
		}
		catch (\Exception $e) {
			return '
				<pre><code>' . $snippet->Text . '</code></pre>
			';
		}

		return '
			<pre class="hljs ' . $result->language . '"><code>' . $result->value . '</code></pre>
		';
	}

	protected function getAddSnippetPopup() {
		return '
			<div id="new-snippet">
				<form>
					<div class="close-btn"><i class="fa fa-times-circle"></i></div>
					<h1>Neues Snippet hinzufügen</h1>
					<div class="form-body">
						<div class="form-row">
							<div class="left-col">
								<div class="group">
									<h5>Kategorie</h5>
									<select style="width: 100% !important;" class="select2" data-placeholder="Kategorie wählen" name="category">
										' . $this->getCategoryOptions() . '
									</select>
								</div>
								
								<div class="group">
									<h5>Name</h5>
									<input type="text" name="name" placeholder="Mein neuer Schnipsel" required>
								</div>
							</div>
							<div class="right-col">
								<div class="group">
									<h5>Code</h5>
									<textarea name="text" placeholder="(new Foo())->bar();"></textarea>
								</div>
							</div>
						</div>
						
						<div class="group">
							<h5>Tags</h5>
							<select style="width: 100% !important;" class="select2 tags" name="tags" multiple></select>
						</div>
						
						<button type="submit" class="btn btn-primary">Speichern</button>
					</div>
				</form>
			</div>
		';
	}

	protected function getCategoryOptions() {
		$output = array();
		$categories = Categories::get();

		foreach ($categories as $category)
			$output[] = '<option value="' . $category->ID . '">' . $category->Name . '</option>';

		return implode(PHP_EOL, $output);
	}
}