<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;
use \gimle\user\User;

Canvas::title(_('My repos'), 'template', -1);

$gitolite = git\Gitolite::getInstance();
$gitoliteConfig = $gitolite->configToXml();
$repos = $gitolite->getRepos();
$access = $gitoliteConfig->getUserRepos('tux');

?>
<div style="padding: 20px;">
	<h2><?=_('Existing repos you have access to:')?></h2>
	<div style="display: grid; grid-template-columns: auto auto 120px;">
		<div style="font-weight: bold;">
			Repository
		</div>
		<div style="font-weight: bold;">
			Address
		</div>
		<div style="font-weight: bold;">
			Permissions
		</div>
<?php
foreach ($repos as $repo) {
	foreach ($access as $path) {
		if (preg_match('#^' . $path['name'] . '$#', $repo)) {
?>
		<div>
			<?=$repo?>
		</div>
		<div>
			<?=Config::get('gitolite.remote')?>:<?=$repo?>.git
		</div>
		<div>
			<?=$path['perm']?>
		</div>
<?php
		}
	}
}
?>
	</div>


	<h2><?=_('Paths you have access to:')?></h2>
	<div style="display: grid; grid-template-columns: auto auto;">
		<div style="font-weight: bold;">
			Path
		</div>
		<div style="font-weight: bold;">
			Permissions
		</div>
<?php
foreach ($access as $path) {
	if (substr($path['name'], -4, 4) === '/..*') {
?>
		<div>
			<?=substr($path['name'], 0, -3)?>
		</div>
		<div>
			<?=$path['perm']?>
		</div>
<?php
	}
}
?>
	</div>


	<h2><?=_('Non-existing repos you have access to:')?></h2>
	<div style="display: grid; grid-template-columns: auto auto 120px;">
		<div style="font-weight: bold;">
			Repository
		</div>
		<div style="font-weight: bold;">
			Address
		</div>
		<div style="font-weight: bold;">
			Permissions
		</div>
<?php
foreach ($access as $path) {
	if (substr($path['name'], -4, 4) === '/..*') {
		continue;
	}
	if (!in_array($path['name'], $repos)) {
?>
		<div>
			<?=$path['name']?>
		</div>
		<div>
			<?=Config::get('gitolite.remote')?>:<?=$path['name']?>.git
		</div>
		<div>
			<?=$path['perm']?>
		</div>
<?php
	}
}
?>
	</div>
</div>
<?php
return true;
