<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;

Canvas::title(_('Gitolite admin'), 'template', -1);

$gitolite = git\Gitolite::getInstance();
$gitoliteConfig = $gitolite->configToXml();

?>
<div style="padding: 20px;">
	<h1>Gitolite admin</h1>
<?php
$groups = $gitoliteConfig->xpath('/gitolite/group');
if ((bool) $groups) {
	foreach ($groups as $group) {
	}
}
else {
?>
	<p>No groups defined.</p>
<?php
}
?>
	<style>
		label > span {
			display: block;
		}
	</style>
	<h2>Add group</h2>
	<label>
		<span>Group name:</span>
		<input type="text" value="" placeholder="<?=_('name')?>"/>
	</label>
	<label>
		<span>Group contents:</span>
		<input type="text" value="" placeholder="<?=_('members')?>"/>
	</label>
	<br/>
	<button><?=_('add')?></button>

	<h2>You have changes</h2>
	<button><?=_('reset')?></button>

	<h3>Add and commit</h3>
	<label>
		<span>message:</span>
		<textarea placeholder="<?=_('commit message')?>"/></textarea>
	</label>
	<br/>
	<button><?=_('commit')?></button>
</div>
<?php
return true;
