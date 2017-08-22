<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;

Canvas::title(_('Dashboard') . ' | Snotra');

$form = new Form();
$form->setProperty('signin', true);

?>
<script>
	jQuery(($) => {
		$('#addKey').on('submit', (e) => {
			e.preventDefault();
			var $form = $('#addKey');
			$.ajax({
				url: $form.attr('action'),
				method: $form.attr('method'),
				data: $form.serialize()
			}).always((r) => {
				if (r.token !== undefined) {
					$('[name="token"]').val(r.token);
					if (r.errmsg !== undefined) {
						$('#feedback').show();
						$('#feedback').html(r.errmsg);
					}
				}
				if (r.title !== undefined) {
					$('#feedback').hide();
				}
				console.log(r);
			});
		});
	});
</script>
<header id="topBar">
	<div style="">
		<a href="<?=BASE_PATH?>signout">
			<i class="fa fa-sign-out" aria-hidden="true"></i> <?=_('sign out')?>
		</a>
	</div>
	<h1><img src="<?=BASE_PATH?>favicon.png"/> Snotra <span>git</span></h1>
</header>

<main>
	<h1><?=_('Manage ssh keys')?></h1>
	<div id="keyList"></div>
	<form id="addKey" action="<?=MAIN_BASE_PATH?>addsshkey" method="POST">
		<input type="hidden" name="token" value="<?=$form->getId()?>">
		<label>
			<span class="title"><?=_('Title:')?></span>
			<input name="title" type="text" placeholder="<?=_('my-machine-name')?>"/>
			<span class="tip"><?=_('If left blank the value will be taken from the key.')?></span>
		</label>
		<label>
			<span class="title"><?=_('Key:')?></span>
			<textarea name="sshkey" placeholder="ssh-rsa … username@host"></textarea>
		</label>
		<div id="feedback"></div>
		<button><?=_('Add')?></button>
	</form>
</main>
<?php
return true;
