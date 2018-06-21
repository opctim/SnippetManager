<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 10:32
 */

namespace SnippetManager\View;

class Menu {
	protected $noMenuItemActiveDetected = true;
	protected $defaultCallback = null;
	protected $menuItems = array();

	public function registerMenuItem($url, $title, $faClass, callable $callback, $hidden = false, $parent = null) {
		$item = (object)array(
			"url"       => $url,
			"title"     => $title,
			"faClass"   => $faClass,
			"active"    => false
		);

		if (!$hidden)
			$this->menuItems[$url] = $item;

		if ($this->checkUrlActive($url) && !is_null($parent) && isset($this->menuItems[$parent]))
			$this->menuItems[$parent]->forceActive = true;

		if ($this->checkUrlActive($url)) {
			$this->noMenuItemActiveDetected = false;

			call_user_func($callback);
		}
	}

	public function renderMenuItems() {
		$output = array();

		if ($this->noMenuItemActiveDetected) {
			if (!is_null($this->defaultCallback)) {
				call_user_func($this->defaultCallback);
			}
		}

		$this->setItemActiveState();

		foreach ($this->menuItems as $menuItem)
			$output[] = '<li'.($menuItem->active ? ' class="active"' : null).'><a href="'.$menuItem->url.'"><i class="fa '.$menuItem->faClass.'"></i><span class="hide-mobile"> '.$menuItem->title.'</span></a></li>';

		return implode(PHP_EOL, $output);
	}

	protected function setItemActiveState() {
		foreach ($this->menuItems as $menuItem) {
			if (isset($menuItem->forceActive) && $menuItem->forceActive == true) {
				$this->menuItems[$menuItem->url]->active = true;

				break;
			}
			else {
				$this->menuItems[$menuItem->url]->active = $this->checkUrlActive($menuItem->url);
			}
		}
	}

	protected function checkUrlActive($url) {
		$pageUrl = (object)parse_url(SM_FULL_URL);

		$pageIdentifier = (isset($pageUrl->path) ? $pageUrl->path : null);

		$itemUrl = (object)parse_url($url);
		//remove last slash if exists
		$itemUrl->path = preg_replace("/\/$/u", null, $itemUrl->path);
		$itemUrl->path = preg_quote($itemUrl->path, "/");
		//making the last slash optional
		$itemUrl->path = $itemUrl->path . "\/?";

		$itemIdentifier = (isset($itemUrl->path) ? $itemUrl->path : null);

		$pattern = '/^' . $itemIdentifier . '$/ui';

		return preg_match($pattern, $pageIdentifier) !== 0;
	}

	public function setFallback(callable $callback) {
		$this->defaultCallback = $callback;
	}
}