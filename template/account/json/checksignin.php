<?php
declare(strict_types=1);
namespace gimle;

function returnError ($errno, $errmsg): void
{
	$form = new Form();
	$form->setProperty('signin', true);

	echo json_encode([
		'errno' => $errno,
		'errmsg' => $errmsg,
		'token' => $form->getId()
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

$checkForm = Form::getInstance($_POST['token']);

if (!$checkForm->validate()) {
	returnError(3, _('Timeout, try again.'));
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
