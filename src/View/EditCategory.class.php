<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 14:10
 */

namespace SnippetManager\View;

class EditCategory extends View {
	protected $category;

	public function __construct($id) {
		$this->category = \SnippetManager\Model\Categories::get($id);
	}

	public function init() {
		$this->parent->addJavaScriptFiles([
			"iro-js"			=> "lib/iro.js/iro.min.js",
			"categories-main"	=> "js/categories.js",
		]);

		$this->parent->addCssFiles([
			"categories-css"	=> "css/categories.page.css",
		]);

		$this->parent->addHeadHtml('
			<script type="text/javascript">
				$(document).ready(function(){
				    var colorPicker = new iro.ColorPicker("#color-picker", {
						width: 300,
						height: 300,
						color: "'.$this->category->getColor().'"
					});
				   	
				   	colorPicker.on("color:change", function(color){
						$("#color-picker-input").val(color.hexString);
					});
				});
			</script>
		');
	}

	public static function getTitle(): string {
		return "Kategorie bearbeiten";
	}

	public function getBody(): string {
		return '
			<h1>' . $this->getTitle() . '</h1>
			<br>
			<br>
			<form action="/categories" method="post" id="edit-category">
				<div class="form-body">
					<div class="row">
						<div class="col-md-8">
							<div class="group">
								<h5>Name</h5>
								<input type="text" style="max-width: 300px" name="name" value="' . $this->category->Name . '" placeholder="Meine neue Kategorie" required>
							</div>
							<br>
							<div class="group">
								<h5>Beschreibung</h5>
								<textarea name="description" placeholder="Lorem ipsum">' . $this->category->Description . '</textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div id="color-picker" class="pull-right"></div>
							<input type="hidden" name="color" id="color-picker-input" value="' . $this->category->getColor() . '">
						</div>
					</div>
					
					<br>
					
					<button type="submit" class="btn btn-primary">Speichern</button>
				</div>
			</form>
		';
	}
}