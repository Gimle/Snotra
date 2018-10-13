<?php
declare(strict_types=1);
namespace gimle;

use \gimle\git\Gitolite;
use \gimle\user\User;

function returnError ($errno, $errmsg): void
{
	echo json_encode([
		'errno' => $errno,
		'errmsg' => $errmsg
	]);
}

if (!isset($_POST['name'])) {
	returnError(1, _('Invalid form data.'));
	return true;
}

$gitolite = Gitolite::getInstance();

$key = $gitolite->getSshKey(User::current()['snotra'], $_POST['name']);

if ($key !== null) {
	try {
		$gitolite->deleteSshKey(User::current()['snotra'], $_POST['name']);
		echo json_encode(true);
	}
	catch (Exception $e) {
		returnError(2, _('Could not delete key.'));
	}
	return true;
}

returnError(3, _('Could not delete key.'));
return true;
