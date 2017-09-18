<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;

Canvas::title(_('Dashboard') . ' | Snotra');

?>
<script>
	jQuery(($) => {
		$('#addKey').on('submit', (e) => {
			e.preventDefault();
			$('#feedback').show();
			$('#feedback').html('Working…');
			var cache = $('#keyList').html();
			$('#keyList').html('<hr/>Loading…');
			var $form = $('#addKey');
			$.ajax({
				url: $form.attr('action'),
				method: $form.attr('method'),
				data: $form.serialize()
			}).always((r) => {
				if (r.errmsg !== undefined) {
					$('#feedback').show();
					$('#feedback').html(r.errmsg);
				}
				if (r.title !== undefined) {
					$('#feedback').hide();
					$('#keyList').load(gimle.BASE_PATH + 'getsshkeys');
				}
				else {
					$('#keyList').html(cache);
				}
			});
		});
		$('#keyList').load(gimle.BASE_PATH + 'getsshkeys');;
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
	<form id="addKey" action="<?=BASE_PATH?>addsshkey" method="POST">
		<label>
			<span class="title"><?=_('Title:')?></span>
			<input name="title" type="text" placeholder="<?=_('my-key-name')?>" style="padding: 2px 3px;"/>
			<span class="tip"><?=_('If left blank the value will be taken from the key.')?></span>
		</label>
		<label>
			<span class="title"><?=_('Key:')?></span>
			<textarea name="sshkey" placeholder="ssh-rsa … username@host"></textarea>
		</label>
		<div id="feedback"></div>
		<button><?=_('Add')?></button>
	</form>
	<div id="keyList"><hr/>Loading…</div>
</main>
<?php
return true;
