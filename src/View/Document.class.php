<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 08:48
 */

namespace SnippetManager\View;

class Document {
	protected $baseTitle = null;
	protected $documentTitle = null;
	protected $javaScriptFiles = array();
	protected $cssFiles = array();
	protected $headHtml = null;
	/** @var View | null $view */
	protected $view = null;
	/** @var Menu | null $menu */
	protected $menu = null;

	public function __construct($baseTitle = null) {
		$this->baseTitle = $baseTitle;
	}

	public function setTitle($documentTitle) {
		$this->documentTitle = $documentTitle;
	}

	public function render() {
		$easterEgg = null;

		if (SM_EASTER_EGG) {
			$easterEgg = '
				<div class="bottom">
					<label class="switch">
						<input id="easter" type="checkbox">
						<span class="slider round"></span>
					</label>
					
					<span> Easter Egg</span>
				</div>
			';
		}

		return '<!DOCTYPE html>
<html lang="de">
	<head>
		' . $this->getHead() . '
	</head>
	<body>
		<div class="outer-wrapper">
			<div class="sidebar">
				<a style="display: block; text-decoration: none !important;" href="/"><h1 class="logo"><i class="fa fa-sticky-note"></i> SnippetManager<small>v1</small></h1></a>
				<ul class="nav">
				' . $this->menu->renderMenuItems() . '
				</ul>
				' . $easterEgg . '
			</div>
			<div class="content">
				' . $this->view->getBody() . '
			</div>
		</div>
	</body>
</html>';
	}

	protected function getHead() {
		$output = array();

		$easterEgg = null;

		if (SM_EASTER_EGG) {
			$easterEgg = '
				<script type="text/javascript">
					$(document).ready(function(){
						var clickedElements = [];
						
						var handler = function(e){
							if (!$(e.target).is("#easter") && !$(e.target).is(".content") && !$(e.target).is(".sidebar")) {
								e.preventDefault();
								
								$(e.target).css("animation", "fall-down 300ms linear forwards");
								
								clickedElements.push($(e.target));
								
								return false;
							}
						};
						
						$("#easter, #easter ~ .slider").click(function(e){
							e.stopPropagation();
						 
							if ($(this).is(":checked")) {
								$(document).on("click", handler);
							}
							else {
								$(document).unbind("click", handler);
								
								for (var i = 0; i < clickedElements.length; i++)
									clickedElements[i].css("animation", "");
							}
						});
					});
				</script>
			';
		}

		$output[] = '
			<title>' . (!is_null($this->baseTitle) ? $this->baseTitle . " | " : null) . $this->documentTitle . '</title>
			<meta charset="utf8">
			<link rel="stylesheet" href="lib/FontAwesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="lib/Bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" href="lib/Highlighter/css/darcula.min.css">
			<link rel="stylesheet" href="css/style.css">
			<script type="text/javascript" src="lib/jQuery/jquery.min.js"></script>
			' . $easterEgg . '
		';

		foreach ($this->cssFiles as $handle => $cssFile)
			$output[] = '<link rel="stylesheet" href="' . $cssFile . '" id="' . $handle . '">';

		foreach ($this->javaScriptFiles as $handle => $javaScriptFile)
			$output[] = '<script type="text/javascript" src="' . $javaScriptFile . '" id="' . $handle . '"></script>';

		$output[] = $this->headHtml;

		return implode(PHP_EOL, $output);
	}

	public function addJavaScriptFiles(array $files) {
		$this->javaScriptFiles = array_merge($this->javaScriptFiles, $files);
	}

	public function removeJavaScriptFile($handle) {
		unset($this->javaScriptFiles[$handle]);
	}

	public function addCssFiles(array $files) {
		$this->cssFiles = array_merge($this->cssFiles, $files);
	}

	public function removeCssFile($handle) {
		unset($this->cssFiles[$handle]);
	}

	public function addHeadHtml($headHtml) {
		$this->headHtml .= $headHtml;
	}

	public function setView(View $view) {
		$view->setParent($this);
		$this->setTitle($view->getTitle());

		if (method_exists($view, "init"))
			$view->init();

		$this->view = $view;
	}

	public function setMenu(Menu $menu) {
		$this->menu = $menu;
	}
}