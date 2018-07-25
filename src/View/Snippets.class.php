<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 09:57
 */

namespace SnippetManager\View;

use Highlight\Highlighter;
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
			"snippets-css"	=> "css/snippets.page.css",
		]);

		$this->parent->addHeadHtml('
			<script type="text/javascript">
				$(document).ready(function(){
				   	$("select.select2:not(.tags)").select2();
				   	$("select.select2.tags").select2({
				   		tags: true,
				   		tokenSeparators: [",", " "]
				   	});
				});
			</script>
		');
	}

	public function getBody(): string {
		return '
			<h1>' . $this->getTitle() . '</h1>
			<div class="row">
				<div class="col-md-8">
					<div class="search-wrapper">
						<input type="text" id="search-field" placeholder="Suchen..." autofocus>
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>
				<div class="col-md-4">
					<div class="add-snippet"><i class="fa fa-plus-circle"></i> Neu</div>
				</div>
			</div>
			<div id="snippet-list">
				' . $this->renderSnippets(\SnippetManager\Model\Snippets::get()) . '
			</div>
			' . $this->getAddSnippetPopup() . '
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
			$output[] = '
				<div class="col-md-4">
					<div class="snippet">
						<div class="row">
							<div class="col-md-12 top-column">
								<a href="/edit-snippet?sid=' . $snippet->ID . '">
									<div class="name">' . $snippet->Name . '</div>
								</a>
							</div>
							<div class="col-md-12 middle-column">
								<div class="text">
									' . self::formatText($snippet) . '
									<div class="overlay"></div>
									<div class="category">' . $snippet->getCategory()->Name . '</div>
								</div>
							</div>
							<div class="col-md-12 tag-column">
								<div class="tags" title="Tags">
									' . (trim($snippet->Tags) != "" ? '<span>' : null) . implode("</span><span>", explode(" ", mb_strtolower($snippet->Tags))) . (trim($snippet->Tags) != "" ? '</span>' : null) . '
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
					<div class="close-btn" onclick="$(\'#new-snippet\').fadeOut()"><i class="fa fa-times-circle"></i></div>
					<h1>Neues Snippet hinzufügen</h1>
					<div style="padding: 40px;">
						<br>
						<h5>Kategorie</h5>
						<select class="select2" name="category">
							
						</select>
						<br>
						<br>
						<input type="text" name="name" placeholder="Name" required>
						<textarea name="text" placeholder="(new Foo())->bar();"></textarea>
						<br>
						<br>
						<h5>Tags</h5>
						<select class="select2 tags" multiple></select>
						<button type="submit" class="btn btn-primary">Speichern</button>
					</div>
				</form>
			</div>
		';
	}
}