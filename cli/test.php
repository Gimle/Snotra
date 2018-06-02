#!/usr/bin/env php
<?php
namespace gimle;

/**
 * The local absolute location of the site.
 *
 * @var string
 */
define('gimle\\SITE_DIR', substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR) + 1));

require SITE_DIR . 'module/gimle/init.php';

// echo 'Testing invalid user…' . "\n";
// d(pam_auth('invalid', 'user'));
echo 'Testing valid user…' . "\n";
d(pam_auth(Config::get('user.auth.local.test.username'), Config::get('user.auth.local.test.password')));
echo "Trying to read server repositories…";
d(is_readable(Config::get('gitolite.path') . 'repositories'));
echo "Trying to write server repositories…";
d(is_writable(Config::get('gitolite.path') . 'repositories'));

class Gitolite
{
	public function parse_config ($config): void
	{
	}

	public function unparse_config (): string
	{
		return '';
	}
}

function parse_gitolite_config ()
{
	$lines = file(Config::get('git.gitolite-admin.path') . 'conf/gitolite.conf', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if ($lines === false) {
		return null;
	}

	$sxml = new xml\SimpleXmlElement('<gitolite/>');
	$inRepo = false;
	foreach ($lines as $linenum => $linestr) {
		$linestr = trim(preg_replace('/\s+/s', ' ', $linestr));
		if ((substr($linestr, 0, 1) === '#') || ($linestr === '')) {
			continue;
		}

		if (($inRepo === false) && (substr($linestr, 0, 1) === '@')) {
			continue;
		}

		if (substr($linestr, 0, 5) === 'repo ') {
			$inRepo = true;
			$repo = $sxml->addChild('repo');
			$repo['name'] = substr($linestr, 5);
			continue;
		}

		if ($inRepo === false) {
			throw new Exception(';(');
		}

		$linestr = explode('=', $linestr);
		if (count($linestr) !== 2) {
			throw new Exception(';(');
		}

		d($linestr);
	}
	return $sxml;
}
$config = parse_gitolite_config();
d($config->pretty());

echo "Done.\n\n";
