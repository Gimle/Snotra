<?php
declare(strict_types=1);
namespace gimle;

use \gimle\user\User;

header('Content-type: text/html; charset=' . mb_internal_encoding());

$gitolite = git\Gitolite::getInstance();
$gitoliteConfig = $gitolite->configToXml();

?>
<!doctype html>
<html lang="%lang%">
	<head>
		<meta charset="<?=mb_internal_encoding()?>">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>%title%</title>

		<meta name="generator" content="Gimle">
		<meta name="description" content="Snotra git">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<link rel="author" href="<?=BASE_PATH?>humans.txt">

		<link rel="shortcut icon" href="<?=BASE_PATH?>favicon.png" type="image/x-icon">
		<script src="<?=BASE_PATH?>js/jquery-3.2.1.min.js"></script>

		<link rel="stylesheet" href="<?=BASE_PATH?>font-awesome-4.7.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="<?=BASE_PATH?>css/pc.css">

		<script>
			window.gimle = {};
			gimle.BASE_PATH = '<?=BASE_PATH?>';

			jQuery(($) => {
				var keepAlive = function () {
					setTimeout(() => {
						$.ajax({
							url: gimle.BASE_PATH + 'keepalive',
							method: 'GET'
						});
						keepAlive();
					}, 300000);
				};
				keepAlive();
			});
		</script>
	</head>
	<body>
		<header id="topBar">
			<div style="width: 1000px; margin: auto;">
				<div>
					<i class="fa fa-bars" aria-hidden="true"></i>
				</div>
				<h1><a href="<?=BASE_PATH?>" style="text-decoration: none; color: #fff;"><img src="<?=BASE_PATH?>favicon.png"/> Snotra <span>git</span></a></h1>
			</div>
		</header>

		<div style="width: 1000px; margin: auto; display: grid; grid-template-columns: 200px 800px;">
			<div style="padding: 20px 0;">
				<ul style="list-style-type: none; padding: 0; margin: 0;">
<?php
if (User::current() !== null) {
?>
					<li style="margin-bottom: 4px;"><a href="<?=BASE_PATH?>account/sshkeys" style="text-decoration: none;"><i class="fa fa-key" aria-hidden="true"></i> <?=_('My ssh keys')?></a></li>
					<li style="margin-bottom: 4px;"><a href="<?=BASE_PATH?>myrepos" style="text-decoration: none;"><i class="fa fa-code-fork" aria-hidden="true"></i> <?=_('My repos')?></a></li>
					<?php
	if ($gitoliteConfig->isAdmin(User::current()['snotra'])) {
?>
					<li style="margin-bottom: 4px;"><a href="<?=BASE_PATH?>gitomin" style="text-decoration: none;"><i class="fa fa-cog" aria-hidden="true"></i> <?=_('Gitolite Admin')?></a></li>
<?php
	}
	if ((is_array(Config::get('play'))) && (in_array(User::current()['snotra'], Config::get('play')))) {
?>
					<li style="margin-bottom: 4px;"><a href="<?=BASE_PATH?>playground" style="text-decoration: none;"><i class="fa fa-gamepad" aria-hidden="true"></i> <?=_('Playground')?></a></li>
<?php
	}
?>
					<li style="margin-bottom: 4px;"><a href="<?=BASE_PATH?>process/signout" style="text-decoration: none;"><i class="fa fa-sign-out" aria-hidden="true"></i> <?=_('Sign out')?></a></li>
<?php
}
?>
				</ul>
			</div>
			<main style="height: 100%;">
				%content%
			</main>
		</div>
	</body>
</html>
<?php
return true;
