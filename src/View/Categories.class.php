<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 14:20
 */

namespace SnippetManager\View;

use SnippetManager\Model\Category;


class Categories extends View {
	public static function getTitle() {
		return "Kategorien";
	}

	public function init() {
		$this->parent->addJavaScriptFiles([
			"select2js"			=> "lib/Select2/select2.min.js",
			"iro-js"			=> "lib/iro.js/iro.min.js",
			"categories-main"	=> "js/categories.js",
		]);

		$this->parent->addCssFiles([
			"select2css"		=> "lib/Select2/select2.min.css",
			"select2-style"		=> "css/select2.style.css",
			"categories-css"	=> "css/categories.page.css",
		]);

		$this->parent->addHeadHtml('
			<script type="text/javascript">
				$(document).ready(function(){
				   	var colorPicker = new iro.ColorPicker("#color-picker", {
						width: 300,
						height: 200,
						color: "#ffffff"
					});
				   	
				   	colorPicker.on("color:change", function(color){
						$("#color-picker-input").val(color.hexString);
						
						$("#color-picker").css("border-color", color.hexString);
					});
				   	
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

	public function getBody() {
		$categories = \SnippetManager\Model\Categories::get();

		return '
			<h1>' . $this->getTitle() . ' <small>(<span id="category-count">' . count($categories) . '</span>)</small></h1>
			<div class="row">
				<div class="col-md-12">
					<div class="search-wrapper">
						<input type="text" id="search-field" placeholder="Suchen..." autofocus>
						<i class="fa fa-spinner fa-spin"></i>
					</div>
				</div>
				<div class="col-md-12">
					<div class="add-category"><i class="fa fa-plus-circle"></i> Neu</div>
				</div>
			</div>
			<br>
			<br>
			<div id="category-list">
				' . $this->renderCategories($categories) . '
			</div>
			' . $this->getAddCategoryPopup() . '
			<div id="context-menu">
				<ul>
					<li class="edit"><i class="fa fa-wrench"></i> Bearbeiten</li>
					<li class="show-snippets"><i class="fa fa-list-alt"></i> Schnipsel anzeigen</li>
					<li class="delete"><i class="fa fa-trash"></i> Löschen</li>
				</ul>
			</div>
			<div id="delete-confirm">
				<div class="panel">
					<h1>Bist du sicher?</h1>
					<div class="body">
						<p>Wenn du das tust, werden auch alle zugehörigen Schnipsel gelöscht!</p>
						<br>
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
	 * @param Category[] $categories
	 * @return string
	 */
	public static function renderCategories($categories) {
		$output = array();

		$output[] = '<div class="row">';

		foreach ($categories as $category) {
			$textColor = "#ffffff";
			$difference = self::luminosityDifference($textColor, $category->getColor());

			if ($difference < 3)
				$textColor = "#000000";

			$output[] = '
				<div class="col-md-2">
					<a title="Bearbeiten (' . $category->Name . ')" class="category-link" data-cid="' . $category->ID . '" data-cn="' . $category->Name . '" href="/edit-category?cid=' . $category->ID . '">
						<div class="category" style="color: ' . $textColor . ';background-color: ' . $category->getColor() . ';">
							' . $category->Name . '
						</div>
					</a>
				</div>
			';
		}

		$output[] = '</div>';

		return implode(PHP_EOL, $output);
	}

	protected function getAddCategoryPopup() {
		return '
			<div id="new-category">
				<form>
					<div class="close-btn"><i class="fa fa-times-circle"></i></div>
					<h1>Neue Kategorie hinzufügen</h1>
					<div class="form-body">
						<div class="form-row">
							<div class="left-col">
								<div class="group">
									<h5>Name</h5>
									<input type="text" name="name" placeholder="Meine neue Kategorie" required>
								</div>
								<div class="group">
									<h5>Sprache</h5>
									' . LanguageSelect::render() . '
								</div>
								<div class="group">
									<h5>Farbe wählen</h5>
									<div id="color-picker"></div>
									<input type="hidden" name="color" id="color-picker-input" value="' . SM_DEFAULT_CATEGORY_COLOR . '">
								</div>
							</div>
							<div class="right-col">
								<div class="group">
									<h5>Beschreibung</h5>
									<textarea name="description" placeholder="Lorem ipsum"></textarea>
								</div>
							</div>
						</div>
						
						<button type="submit" class="btn btn-primary">Speichern</button>
					</div>
				</form>
			</div>
		';
	}
}