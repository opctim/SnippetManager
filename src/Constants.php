<?php

define("SM_SOURCEFOLDER_PATH", realpath(__DIR__ . "/../src"));
define("SM_LIBRARYFOLDER_PATH", realpath(__DIR__ . "/../lib"));
define("SM_WEBFOLDER_PATH", realpath(__DIR__ . "/../www"));

define("SM_DB_HOST", "localhost");
define("SM_DB_USER", "root");
define("SM_DB_PASSWORD", "");
define("SM_DB_NAME", "snippetmanager");

define("SM_EASTER_EGG", false);

define("SM_DEBUG", true);

define("SM_DEFAULT_CATEGORY_COLOR", "#d60d31");

define("SM_FULL_URL", (isset($_SERVER["HTTPS"]) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
define("AJAX_CALL_LEGAL", isset($_SERVER["HTTP_REFERER"]) && preg_match("/^".preg_quote((isset($_SERVER["HTTPS"]) ? "https" : "http") . "://$_SERVER[HTTP_HOST]", "/")."/", $_SERVER["HTTP_REFERER"]));