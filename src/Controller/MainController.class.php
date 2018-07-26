<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 08:35
 */

namespace SnippetManager\Controller;

use SnippetManager\Model\Category;
use SnippetManager\Model\Redirect;
use SnippetManager\Model\Snippet;
use SnippetManager\View\Categories;
use SnippetManager\View\Document;
use SnippetManager\View\EditCategory;
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
		if (isset($_REQUEST["snippet-search"])) {
			$_SESSION["_sm_enqueued_search"] = $_REQUEST["snippet-search"];

			Redirect::target("/");
		}

		$ajaxRequestManager = new AjaxRequestManager();

		$ajaxRequestManager->add("snippets", function($requestData){
			echo Snippets::renderSnippets(\SnippetManager\Model\Snippets::get(null, $requestData));
		});

		$ajaxRequestManager->add("newSnippet", function($requestData){
			$defaults = [
				"categoryId"	=> null,
				"name"			=> null,
				"text"			=> null,
				"tags"			=> null,
			];

			$requestData = (object)array_replace($defaults, $requestData);

			Snippet::create($requestData->categoryId, $requestData->name, $requestData->text, $requestData->tags);
		});

		$ajaxRequestManager->add("deleteSnippet", function($requestData){
			Snippet::delete($requestData);
		});

		$ajaxRequestManager->add("categories", function($requestData){
			echo Categories::renderCategories(\SnippetManager\Model\Categories::get(null, $requestData));
		});

		$ajaxRequestManager->add("newCategory", function($requestData){
			$defaults = [
				"name"			=> null,
				"description"	=> null,
				"color"			=> null,
			];

			$requestData = (object)array_replace($defaults, $requestData);

			Category::create($requestData->name, $requestData->description, $requestData->color);
		});

		$ajaxRequestManager->add("deleteCategory", function($requestData){
			Category::delete($requestData);
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
				if (empty($_REQUEST["sid"]))
					Redirect::target("/");

				$document->setView(new EditSnippet($_REQUEST["sid"]));
			}, true, "/");

			$menu->registerMenuItem("/categories", Categories::getTitle(), "fa-folder-open", function() use ($document) {
				$document->setView(new Categories());
			});

			$menu->registerMenuItem("/edit-category", EditCategory::getTitle(), null, function() use ($document) {
				if (empty($_REQUEST["cid"]))
					Redirect::target("/");

				$document->setView(new EditCategory($_REQUEST["cid"]));
			}, true, "/categories");

			$document->setMenu($menu);

			echo $document->render();
		});
	}
}