<?php
declare(strict_types=1);
namespace gimle;

if (isset($_SESSION['user'])) {
	unset($_SESSION['user']);
}

header('Location: ' . BASE_PATH);

return true;
