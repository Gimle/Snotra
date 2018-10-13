<?php
declare(strict_types=1);
namespace gimle;

use \gimle\router\Router;
use \gimle\canvas\Canvas;
use \gimle\user\User;

/**
 * The local absolute location of the site.
 *
 * @var string
 */
define('gimle\\SITE_DIR', substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR) + 1));

require SITE_DIR . 'module/gimle/init.php';

Canvas::title(null, 'template');
Canvas::title('Snotra', 'sitename');
$router = Router::getInstance();

$router->setCanvas(BASE_PATH_KEY);

$router->bind('pc', 'manifest.json', function () use ($router) {
	$router->setCanvas('manifest');
	return $router->setTemplate('manifest');
});

inc(SITE_DIR . 'inc/session.php');
if (isset($_SESSION['gimle']['user'])) {
	if (!isset($_SESSION['gimle']['user']['snotra'])) {
		if (isset($_SESSION['gimle']['user']['pam'])) {
			$_SESSION['gimle']['user']['snotra'] = $_SESSION['gimle']['user']['pam'];
		}
	}
}

$router->bind('pc', 'setLanguage', function () use ($router) {
	$router->setCanvas('json');
	return $router->setTemplate('setlanguage');
}, Router::R_POST);

/* -- Account -- */
$router->bind('pc', 'process/:canvas', function () use ($router) {
	inc(SITE_DIR . 'inc/session.php');
	$router->setCanvas('process' . page('canvas'));
}, ['canvas' => 'signin|signout'], Router::R_POST | Router::R_GET);
/* == Account == */

if (User::current() === null) {
	$router->bind('pc', '.*', function () use ($router) {
		$router->setCanvas('unsigned');
		return $router->setTemplate('account/html/login');
	});
}
else {
	$gitolite = git\Gitolite::getInstance();
	$gitoliteConfig = $gitolite->configToXml();

	$router->bind('pc', '', function () use ($router) {
		return $router->setTemplate('dashboard');
	});
	$router->bind('pc', 'account/sshkeys', function () use ($router) {
		return $router->setTemplate('account/html/sshkeys');
	});
	$router->bind('pc', 'signout', function () use ($router) {
		return $router->setCanvas('dosignout', false);
	});
	if ((is_array(Config::get('play'))) && (in_array(User::current()['snotra'], Config::get('play')))) {
		$router->bind('pc', 'playground', function () use ($router) {
			return $router->setTemplate('playground', false);
		});
	}
	if ($gitoliteConfig->isAdmin(User::current()['snotra'])) {
		$router->bind('pc', ':template', function () use ($router) {
			return $router->setTemplate(page('template'));
		}, ['template' => 'gitomin']);
	}

	$router->bind('pc', 'keepalive', function () use ($router) {
		$router->setCanvas('json');
		return $router->setTemplate('keepalive');
	});
	$router->bind('pc', 'addsshkey', function () use ($router) {
		$router->setCanvas('json');
		return $router->setTemplate('account/json/addsshkey');
	}, Router::R_POST);
	$router->bind('pc', 'deletesshkey', function () use ($router) {
		$router->setCanvas('json');
		return $router->setTemplate('account/json/deletesshkey');
	}, Router::R_POST);
	$router->bind('pc', 'getsshkeys', function () use ($router) {
		$router->setCanvas('json');
		return $router->setTemplate('account/html/getsshkeys');
	});
}

$router->dispatch();

return true;
