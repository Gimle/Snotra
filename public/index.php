<?php
declare(strict_types=1);
namespace gimle;

use gimle\router\Router;

/**
 * The local absolute location of the site.
 *
 * @var string
 */
define('gimle\\SITE_DIR', substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR) + 1));

require SITE_DIR . 'module/gimle/init.php';

$router = Router::getInstance();

$router->setCanvas(BASE_PATH_KEY);

$router->bind('pc', 'manifest.json', function () use ($router) {
	$router->setCanvas('manifest');
	return $router->setTemplate('manifest');
});

inc(SITE_DIR . 'inc/session.php');
$router->bind('pc', 'setLanguage', function () use ($router) {
	$router->setCanvas('json');
	return $router->setTemplate('setlanguage');
}, Router::R_POST);

if (!isset($_SESSION['user'])) {
	$router->bind('pc', '', function () use ($router) {
		$router->setCanvas('unsigned');
		return $router->setTemplate('account/html/login');
	});
	$router->bind('pc', 'checksignin', function () use ($router) {
		$router->setCanvas('json');
		return $router->setTemplate('account/json/checksignin');
	}, Router::R_POST);
}
else {
	$router->bind('pc', '', function () use ($router) {
		return $router->setTemplate('dashboard');
	});
	$router->bind('pc', 'signout', function () use ($router) {
		return $router->setCanvas('dosignout', false);
	});
	if ((is_array(Config::get('play'))) && (in_array($_SESSION['user'], Config::get('play')))) {
		$router->bind('pc', 'playground', function () use ($router) {
			return $router->setTemplate('playground', false);
		});
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
