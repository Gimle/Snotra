<?php
declare(strict_types=1);
namespace gimle;

function returnError ($errno, $errmsg): void
{
	echo json_encode([
		'errno' => $errno,
		'errmsg' => $errmsg
	]);
}

if ((!isset($_POST['token'])) || (!isset($_POST['username'])) || (!isset($_POST['password']))) {
	returnError(1, _('Invalid form data.'));
	return true;
}

if (($_POST['username'] === '') || ($_POST['password'] === '')) {
	returnError(2, _('Username or password missing.'));
	return true;
}

$result = pam_auth($_POST['username'], $_POST['password']);

if (!$result) {
	returnError(4, _('Incorrect username or password.'));
	return true;
}

$_SESSION['user'] = $_POST['username'];
echo json_encode($result);

return true;
