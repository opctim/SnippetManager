<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 08:35
 */

namespace SnippetManager\Controller;

use SnippetManager\Model\Redirect;
use SnippetManager\View\Category;
use SnippetManager\View\Document;
use SnippetManager\View\EditSnippet;
use SnippetManager\View\Menu;
use SnippetManager\View\Snippets;

class MainController {
	public function __construct() {
		if (SM_DEBUG) {
			error_reporting(E_ALL);
			ini_set("display_errors", "1");
		}
		else {
			error_reporting(0);
			ini_set("display_errors", "0");
		}
	}

	public function handleRequest() {
		$ajaxRequestManager = new AjaxRequestManager();

		$ajaxRequestManager->add("snippets", function($requestData){
			echo Snippets::renderSnippets(\SnippetManager\Model\Snippets::get($requestData));
		});

		$ajaxRequestManager->setDefault(function(){
			$document = new Document("SnippetManager");

			$menu = new Menu();

			$menu->setFallback(function(){
				Redirect::target("/");
			});

			$menu->registerMenuItem("/", Snippets::getTitle(), "fa-sticky-note", function() use ($document) {
				$document->setView(new Snippets());
			});

			$menu->registerMenuItem("/edit-snippet", EditSnippet::getTitle(), null, function() use ($document) {
				$document->setView(new EditSnippet());
			}, true, "/");

			$menu->registerMenuItem("/categories", Category::getTitle(), "fa-folder-open", function() use ($document) {
				$document->setView(new Category());
			});

			$document->setMenu($menu);

			echo $document->render();
			echo "hi";
		});
	}
}