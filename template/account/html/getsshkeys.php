<?php
declare(strict_types=1);
namespace gimle;

use \gimle\git\Gitolite;
use \gimle\user\User;

$gitolite = Gitolite::getInstance();

$keys = $gitolite->getSshKeys(User::current()['snotra'], true);
if (empty($keys)) {
?>
<p>No ssh keys added.</p>
<?php
}
else {
?>
<script>
	jQuery(($) => {
		$('.deletesshkey').on('click', function (e) {
			e.preventDefault();
			var $form = $(this);
			var $feedback = $form.parent().find('.deleteFeedback');
			$feedback.html('Loading…');
			$.ajax({
				url: $form.attr('action'),
				method: $form.attr('method'),
				data: $form.serialize()
			}).always((r) => {
				if (r.errmsg !== undefined) {
					$feedback.html(r.errmsg);
				}
				else {
					$('#keyList').load(gimle.BASE_PATH + 'getsshkeys');
				}
			});
		});
	});
</script>
<?php
	asort($keys);
	foreach ($keys as $title => $key) {
?>
<hr/>
<div style="float: right;">
	<span class="deleteFeedback"></span>
	<form class="deletesshkey" style="display: inline-block;" action="<?=BASE_PATH?>deletesshkey" method="POST">
		<input type="hidden" name="name" value="<?=htmlspecialchars($title)?>"/>
		<button style="border: 1px solid #900; background-color: #c00; color: #fff; padding: 4px 8px; margin-top: 20px; border-radius: 4px;"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
	</form>
</div>
<h2><?=$title?></h2>
<div style="font-family: Arial; font-size: 13px;">
	<p><b>Fingerprint:</b> <span style="font-family: Monospace;"><?=$key['fingerprint']?></span></p>
	<p><b>Added</b>: <?=($key['datetime'] !== null ? date('d.m.Y H:i:s', strtotime($key['datetime'])) : 'Unknown')?></p>
</div>
<?php
	}
}
return true;
