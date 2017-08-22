<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;

Canvas::title(_('Sign in') . ' | Snotra');

$form = new Form();
$form->setProperty('signin', true);

?>
<script>
	jQuery(($) => {
		$('#doSignIn').on('submit', (e) => {
			e.preventDefault();
			var $form = $('#doSignIn');
			$('#feedback').html('Working…');
			$('.signInButton').html('<?=_('Signing in, please wait…')?>');
			$('.signInButton').prop('disabled', true);
			$.ajax({
				url: $form.attr('action'),
				method: $form.attr('method'),
				data: $form.serialize()
			}).always((r) => {
				if (r === true) {
					location.reload();
				}
				else {
					if (r.token !== undefined) {
						$('[name="token"]').val(r.token);
					}
					if (r.errmsg !== undefined) {
						$('#feedback').show();
						$('#feedback').html(r.errmsg);
						$('.signInButton').prop('disabled', false);
						$('.signInButton').html('<?=_('Sign in')?>');
					}
				}
			});
		});
	});

	var setLanguage = function (lang) {
		$.ajax({
			url: gimle.BASE_PATH + 'setLanguage',
			method: 'POST',
			data: {lang: lang}
		}).always((r) => {
			location.reload();
		});
	};
</script>
<h1 class="signin"><img src="<?=BASE_PATH?>gfx/Git-Icon-1788C.png"/>  Snotra <span>git</span></h1>
<div id="signinBox">
	<div>
		<div id="feedback"></div>

		<form action="<?=MAIN_BASE_PATH?>checkSignIn" method="POST" id="doSignIn">
			<input type="hidden" name="token" value="<?=$form->getId()?>">
			<label>
				<i class="fa fa-user"></i>
				<input name="username" type="text" placeholder="<?=_('Username')?>"></input>
			</label>
			<label>
				<i class="fa fa-lock"></i>
				<input name="password" type="password" placeholder="<?=_('Password')?>"></input>
			</label>

			<button name="action" value="local" class="signInButton"><?=_('Sign in')?></button>
		</form>

		<div id="langPicker">
			<div onclick="setLanguage('no')" style="background-image: url(<?=BASE_PATH?>gfx/flag/no.svg);"></div>
			<div onclick="setLanguage('en')" style="background-image: url(<?=BASE_PATH?>gfx/flag/uk.svg);"></div>
		</div>
	</div>
	<p><?=sprintf(_('A %s product.'), '<a href="https://www.quixotic.no/">Quixotic</a>')?></p>
</div>
<p>
<?php
return true;
