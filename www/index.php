<?php
/**
 * nelles websolutions
 * Date: 13.06.18
 * Time: 08:29
 */

session_start();

require_once __DIR__ . "/../src/Constants.php";

require_once SM_SOURCEFOLDER_PATH . "/Autoload.php";
require_once SM_LIBRARYFOLDER_PATH . "/vendor/autoload.php";


new Autoload("SnippetManager");

$controller = new \SnippetManager\Controller\MainController();

$controller->handleRequest();
