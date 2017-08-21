<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;

if (session_status() === PHP_SESSION_NONE) {
	session_start();

	if (isset($_SESSION['lang'])) {
		(i18n::getInstance())->setLanguage($_SESSION['lang']);
	}
	else {
		(i18n::getInstance())->setLanguage();
	}

	Canvas::lang((i18n::getInstance())->getLanguage());
}

return true;
