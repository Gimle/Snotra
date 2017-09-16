<?php
declare(strict_types=1);
namespace gimle;

use \gimle\git\Gitolite;

function returnError ($errno, $errmsg): void
{
	$form = new Form();
	$form->setProperty('addsshkey', true);

	echo json_encode([
		'errno' => $errno,
		'errmsg' => $errmsg,
		'token' => $form->getId()
	]);
}

if ((!isset($_POST['token'])) || (!isset($_POST['title'])) || (!isset($_POST['sshkey']))) {
	returnError(1, _('Invalid form data.'));
	return true;
}

if ($_POST['sshkey'] === '') {
	returnError(2, _('No key.'));
	return true;
}

if (!preg_match('/^ssh-rsa AAAA[0-9A-Za-z+\/]+[=]{0,3} [^@]+@([^@]+)$/', trim($_POST['sshkey']), $matches)) {
	returnError(2, _('Invalid key.'));
	return true;
}

$title = $_POST['title'];
if ($title === '') {
	$title = $matches[1];
}
if (!filter_var($title, FILTER_VALIDATE_FILENAME)) {
	returnError(3, _('Invalid title.'));
	return true;
}

$checkForm = Form::getInstance($_POST['token']);

if (!$checkForm->validate()) {
	returnError(4, _('Timeout, try again.'));
	return true;
}

// Ok, so tests did pass, now lets add the key!
$gitolite = Gitolite::getInstance();
try {
	$gitolite->addSshKey($_POST['sshkey'], $_SESSION['user'], $title);
}
catch (Exception $e) {
	$form = new Form();
	$form->setProperty('addsshkey', true);

	echo json_encode([
		'errno' => 7,
		'errmsg' => $e->getMessage(),
		'token' => $form->getId()
	]);
	return true;
}

$form = new Form();
$form->setProperty('addsshkey', true);

echo json_encode([
	'title' => $title,
	'token' => $form->getId(),
]);

return true;
