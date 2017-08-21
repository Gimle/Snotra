<?php
declare(strict_types=1);
namespace gimle;

use \gimle\canvas\Canvas;

Canvas::title(_('Dashboard') . ' | Snotra');

?>
<header id="topBar">
	<div style="">
		<a href="<?=BASE_PATH?>signout">
			<i class="fa fa-sign-out" aria-hidden="true"></i> <?=_('sign out')?>
		</a>
	</div>
	<h1><img src="<?=BASE_PATH?>favicon.png"/> Snotra <span>git</span></h1>
</header>
<?php
return true;
