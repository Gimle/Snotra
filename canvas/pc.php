<?php
declare(strict_types=1);
namespace gimle;

header('Content-type: text/html; charset=' . mb_internal_encoding());
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
		<script src="https://www.trollgården.no/js/jquery-3.2.1.min.js"></script>

		<link rel="stylesheet" href="<?=BASE_PATH?>font-awesome-4.7.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="<?=BASE_PATH?>css/pc.css">

		<script>
			gimle = {};
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
		%content%
	</body>
</html>
<?php
return true;
