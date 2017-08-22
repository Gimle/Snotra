<?php
declare(strict_types=1);
namespace gimle;

if (isset($_POST['lang'])) {
	(i18n::getInstance())->setLanguage($_POST['lang']);
	$_SESSION['lang'] = (i18n::getInstance())->getLanguage();
	echo json_encode($_SESSION['lang']);
	return true;
}

echo json_encode(false);

return true;
