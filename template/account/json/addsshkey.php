<?php
declare(strict_types=1);
namespace gimle;

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

$usedTitles = [];
$usedKeys = [];
if (file_exists(Config::get('dir.gitolite-admin') . 'keydir/' . $_SESSION['user'])) {
	foreach (new \DirectoryIterator(Config::get('dir.gitolite-admin') . 'keydir/' . $_SESSION['user']) as $fileInfo) {
		$fileName = $fileInfo->getFilename();
		if (substr($fileName, 0, 1) === '.') {
			continue;
		}
		$usedTitles[] = $fileName;
		$fullName = Config::get('dir.gitolite-admin') . 'keydir/' . $_SESSION['user'] . '/' . $fileName . '/' . $_SESSION['user'] . '.pub';
		$usedKeys[] = trim(file_get_contents($fullName));
	}
}

if (in_array($title, $usedTitles)) {
	returnError(5, _('Title in use.'));
	return true;
}

if (in_array(trim($_POST['sshkey']), $usedKeys)) {
	returnError(6, _('Key in use.'));
	return true;
}

$form = new Form();
$form->setProperty('addsshkey', true);

echo json_encode([
	'title' => $title,
	'token' => $form->getId(),
]);

return true;
